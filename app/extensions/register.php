<?
final class ExtensionRegister extends Extension {
	function __construct ($data = array(), $page) {
		$this->scripts[] = '/assets/lib/arckane/models/register.js';
		
		parent::__construct($data, $page);
		
		$form_vars = array(
			'switch_form' => $this->lang->get('switch_form'),
			'switch_form_href' => '#modal={"action":"register","type":"company"}',
			'form_title' => $this->lang->get('form_title'),
			'button_register' => $this->lang->get('button_register'),
			'select_category' => $this->lang->get('select_category'),
			'select_categories' => json_encode($this->db->select(array(
				'table' => 'category',
				'type' => 'talent',
				'status' => 1
			))->rows)
		);
		
		$this->form = $this->extension('form', array_merge(array(
			'form' => isset($this->data['type']) && $this->data['type'] == 'company' ? 'register-company' : 'register',
			'vars' => $form_vars
		), $this->data));
	}
	public function render () {
		return $this->form->render();
	}
	public function submit() {
		$confirm = array(
			'type' => 'success',
			'message' => $this->lang->get('success_register')
		);

		if ($this->form->validate() && !count($this->form->message)) {
			$this->data['status'] = 1;
			
			if (!$this->model('user', array('mail' => $this->data['mail']))->get()) {
				$this->model('user', $this->data)->set();
			} else {
				$confirm['type'] = 'error';
				$confirm['message'] = $this->lang->get('mail_exists');
			}			
		}

		return json_encode(array_merge(json_decode($this->form->confirm(), true), $confirm));
	}
}