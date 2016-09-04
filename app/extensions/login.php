<?
final class ExtensionLogin extends Extension {
	private $user = false;
	
	function __construct ($data = array(), $page) {
		$this->scripts[] = '/assets/lib/arckane/models/form.js';
		$this->scripts[] = '/assets/lib/arckane/models/login.js';
		
		parent::__construct($data, $page);
		
		$this->form = $this->extension('form', array_merge(array(
			'form' => 'login',
			'vars' => array(
				'form_title' => $this->lang->get('form_title'),
				'button_login' => $this->lang->get('button_login'),
			)
		), $this->data));
		
		$this->id = session_id();
	}
	public function render () {
		return $this->form->render();
	}
	public function submit() {
		$this->_get();
		
		if (!$this->user) {
			$confirm = array(
				'type' => 'success',
				'message' => $this->lang->get('success_login')
			);
			
			if ($this->form->validate() && !count($this->form->message)) {
				$this->data['status'] = 1;
				
				!$this->user && $this->get() ? $this->_set() : $confirm = array(
					'type' => 'error',
					'message' => $this->lang->get('error_login'),
				);
			}
		} else {
			$confirm = array(
				'type' => 'error',
				'message' => $this->lang->get('already_login')
			);
		}		
		
		return json_encode(array_merge(json_decode($this->form->confirm(), true), $confirm));
	}
	public function logout() {
		$this->_destruct();
		
		return json_encode(array(
			'type' => 'success',
		));
	}
	public function get() {
		$result = $this->model('user', $this->data)->get();
		$result = count((array)$result) ? $result : false;
		
		if (!$result) return false;
		
		unset($result->pass);
		unset($result->shown);

		return $this->user = $result;
	}
	public function _login () {
		$this->_get();
		return $this->user;
    }
	private function _get () {
		if (isset($_SESSION[$this->id])) {
			$this->user = $_SESSION[$this->id];
		}
	}
	private function _set () {
		$_SESSION[$this->id] = $this->user;
	}
	private function _destruct () {
		unset($_SESSION[$this->id]);
    }
}