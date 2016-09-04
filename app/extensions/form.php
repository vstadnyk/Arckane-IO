<?
final class ExtensionForm extends Extension {
	public $form;
	public $message = array();
	public $config = array();
	public $uploaded = array();
	public $encoding = 'CP1251';
	public $fields = array();
	public $required = array();
	public $error = array();
	
	function __construct ($data = array(), $page) {
		parent::__construct($data, $page);
		$this->init();
	}
	public function init () {
		$this->template = $this->page->template.'/extensions/form/';
		$this->config = $this->request->config('extensions/form/main');
		
		$this->form = isset($this->data['form']) ? $this->data['form'] : 'default';
		$this->form_config = $this->request->config('extensions/form/forms/'.$this->form);
		$this->fields = $this->form_config['fields'];
		
		isset($this->data['vars']) && is_array($this->data['vars']) ? $this->extend($this->data['vars']) : false;
	
		return $this;
	}	
	public function send () {
		$this->form_config['table'] ? $this->upload($_FILES) : false;

		if ($this->validate() && !count($this->message)) {
			$this->form_config['table'] ? $this->insert() : false;
			$this->form_config['mail-report'] ? $this->email() : false;
		}
		
		return $this->confirm();
	}
	public function upload ($files = false) {
		if (!$files || !count($files['files'])) return;

		$this->uploaded = array();
		
		$dir = isset($this->config[$this->form]['uploaded_dir']) ? $this->config[$this->form]['uploaded_dir'] : $this->config['default']['uploaded_dir'];
			
		$dir = $this->request->root.$dir;

		if(!is_dir($dir)) {
			if (!mkdir($dir, 0777)) return $this->message[] = 'Unable to create directory - '.$dir;
		}
	
		foreach ($files['files']['name'] as $key => $item) {
			$item = str_replace(' ', '_', $item);
			$file = $files['files']['tmp_name'][$key];
		
			if (file_exists($dir.$item)) {
				$item = date('H-i-s d.m.Y').'-'.$item;
			}
			
			if (!isset($this->config[$this->form]['files_keep_names']) || !$this->config[$this->form]['files_keep_names']) {
				$table = isset($this->config[$this->form]['table']) ? $this->config[$this->form]['table'] : $this->config['default']['table'];
				$last = $this->db->get('SELECT id, MAX(id) FROM '.$table)->row['id'] + 1 + $key;
				$pathinfo = pathinfo($item);
				$item = 'file_'.$last.'.'.$pathinfo['extension'];
			}
			
			$this->uploaded[] = $item;
			if (!move_uploaded_file($file, $dir.$item)) return $this->message[] = 'Unable upload file '.$dir.$item;
		}
	}
	public function render () {
		$fields = array();
		
		if (!is_array($this->fields)) {
			exit('Error: In fields array in form - '.$this->form.'!');
		}

		foreach ($this->fields as $name => $field) {
			$fields[] = $this->render_field($name, $field);
		}
		
		if (!isset($this->form_config['form']['tag'])) {
			$this->form_config['form']['tag'] = 'form';
		}
		
		$data = array(
			'config' => $this->to_object($this->form_config),
			'attributes' => $this->render_attributes($this->form_config['form']),
			'fields' => implode(PHP_EOL, $fields)
		);
		
		return $this->tmpl->get($this->template.'form.php', $data);
	}
	private function render_attributes ($data) {
		$result = array();
	
		foreach ($data as $name => $value) {
			if (in_array($name, $this->config['system_attributes'])) continue;
			
			if (in_array($name, array('value', 'placeholder'))) {
				$value = $this->execute($value);
			}
			
			$result[] = $name.'="'.$value.'"';
		}
		
		return implode(' ', $result);
	}
	public function render_field ($name, $field) {
		!is_array($field) ? $field = array($field) : false;
		if (is_numeric($name)) {
			$tmp = $name;
			$name = $field[$tmp];
			unset($field[$tmp]);
			unset($tmp);
		}
		
		$field = array_merge(array(
			'name' => $name,
			'placeholder' => true,
			'required' => false,
			'type' => 'text',
			'tag' => 'input',
			'text' => '',
			'icon' => '',
			'options' => '',
			'html' => false,
			'p' => true,
			'render' => array()
		), $field);
		
		if ($field['placeholder']) {
			$placeholder = $field['required'] ? '* ' : '';
			$field['placeholder'] = $placeholder.$this->lang->get(is_bool($field['placeholder']) ? $name : $field['placeholder']);
		} else {
			unset($field['placeholder']);
		}
	
		if ($field['tag'] == 'button') {
			$field['value'] = !isset($field['value']) ? $this->lang->get($name) : $this->execute($field['value']);
			unset($field['name']);
		}
		
		if (!$field['required']) {
			unset($field['required']);
		}

		if ($field['type'] == 'hidden' || $field['tag'] == 'button') {
			unset($field['placeholder']);
			unset($field['required']);
		}
		
		if ($field['tag'] == 'textarea') {
			unset($field['type']);
			unset($field['value']);
		}
		
		if ($field['html']) {
			unset($field['name']);
			unset($field['type']);
			unset($field['value']);
			unset($field['placeholder']);
			unset($field['required']);
		}
		
		if ($field['tag'] == 'select') {
			unset($field['type']);
		}
		
		if (in_array($field['tag'], array('option', 'progress'))) {
			unset($field['name']);
			unset($field['type']);
			unset($field['placeholder']);
			unset($field['required']);
		}
		
		$field['text'] = $this->execute($field['text']);
		$field['options'] = json_decode($this->execute($field['options']));
		
		unset($field['html']);
		
		$data = array(
			'form' => $this,
			'field' => $this->to_object($field), 
			'attr' => $this->render_attributes($field)
		);

		return $this->tmpl->get($this->template.$field['tag'].'.php', $data);
	}
	private function clear ($data = '') {
		$data = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $data);
		$data = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $data);
		return $data;
	}
	public function validate () {
		$this->post = $this->data;
		$this->data = array();
		
		foreach ($this->fields as $name => $value) {
			
			if ((isset($value['private']) && $value['private']) || (isset($value['html']) && $value['html'])) {
				unset($this->post[$name]);
				continue;
			}
			
			if (!isset($this->post[$name])) {
				$this->message[] = 'Unable to validate form. Unknown field - '.$name;
				continue;
			}
			
			if (isset($value['required']) && $value['required'] == 'true' && empty($this->post[$name])) {
				$this->error['required'][] = $name;
			}
			
			if (isset($value['type']) && $value['type'] == 'email' && !empty($this->post[$name]) && !filter_var($this->post[$name], FILTER_VALIDATE_EMAIL)) {
				$this->error['email'][] = $name;
			}
			
			if (isset($value['tag']) && $value['tag'] == 'file' && count($this->uploaded)) {
				$this->post[$name] = implode(', ', $this->uploaded);
			}
			
			$this->data[$name] = array_merge($value, array('value' => $this->clear($this->post[$name])));
		}
		
		if (!isset($this->error['email']) && !isset($this->error['required'])) {
			return true;
		}
		
		return false;
	}
	public function mail_format ($field, $name = false) {
		if (isset($field['tag']) && $field['tag'] == 'file' && count($this->uploaded)) {
			$files = array();
			foreach ($this->uploaded as $file) {
				$files[] = '<a href="'.$this->request->http.$this->form_config['uploaded_dir'].$file.'" download>'.$file.'</a>';
			}
			$field['value'] = implode(PHP_EOL, $files);
		}
		
		if ($name == 'date') {
			$field['value'] = $this->lang->rdate('d.m.Y H:i:s', $field['value']);
		}
		
		return $field['value'];
	}
	public function confirm () {
 		$message = array(
			'type' => 'success',
			'message' => $this->lang->get('success_message')
		);
		
		if (isset($this->error['required']) && count($this->error['required'])) {
			$message = array(
				'type' => 'error',
				'message' => $this->lang->get('error_empty'),
				'error' => $this->error['required']
			);
		}
		
		if (isset($this->error['email']) && count($this->error['email'])) {
			$message = array(
				'type' => 'error',
				'message' => $this->lang->get('error_email'),
				'error' => $this->error['email']
			);
		}
		
		if (count($this->message)) {
			$message = array(
				'type' => 'error',
				'message' => $this->lang->get('error_system').': ('.implode(PHP_EOL, $this->message).')'
			);
		}
		
		return json_encode($message);
	}
	public function insert () {
		$table = $this->form_config['table'];
		
		$fields = $values = array();
		
		if (!in_array($table, array('orders')) && (!isset($this->data['pos']) || empty($this->data['pos']))) {
			$fields[] = 'pos';
			$values[] = $this->db->get('SELECT MAX(pos) AS last FROM '.$table)->row['last'] + 1;
		}
		
		foreach ($this->data as $field => $value) {
			if (!is_array($value)) continue;
			
			$value = $this->clear($value['value']);
			$fields[] = $field;
			$values[] = "'".$value."'";
		}
		
 		$insert = $this->db->get("INSERT INTO ".$table." (".implode(', ', $fields).") values (".implode(', ', $values).")", true);
		
		if (!is_bool($insert)) $this->message[] = 'Unable insert data '.$insert;
	}
	public function email ($message = false, $subject = false, $to = false) {
		if (isset($this->error['email']) || isset($this->error['required'])) {
			return false;
		}
		
		$lang = $this->request->config('main', 'system');
		$lang = $this->to_object(array('lang' => $this->lang->current));
	
		if (!$message) {
			$data = array();
			
			$data = array(
				'form' => $this,
				'fields' => array_diff_key($this->data, array_flip($this->config['report_fields_black_list']))
			);
			
			$message = $this->tmpl->get($this->view.'/mail-reports/'.$this->form_config['mail-report'], $data);
		}
		
 		if (!$subject) {
			$subject = iconv('UTF-8', $this->encoding, $this->lang->get('email_subject').' '.$this->model('settings', $lang)->get('regions')->site_name);
		}
		
		if (!$to) {
			$settings = $this->model('settings', $lang)->get('settings');
			$to = !is_array($settings) ? $settings->mail : 'namirif@ya.ru';
		}
		
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";

		$headers .= 'From: '.$subject.' <noreply@'.getenv('HTTP_HOST').'>'."\r\n";

		if (mail($to, $subject, $this->clear($message), $headers)){
			return true;
		}
		
		return false;
	}
}