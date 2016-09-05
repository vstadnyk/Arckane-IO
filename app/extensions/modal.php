<?
final class ExtensionModal extends Extension {
	public $message = array();
	
	function __construct ($data, $page) {
		$this->scripts[] = '/assets/lib/arckane/models/modal.js';
		$this->scripts[] = '/assets/lib/arckane/models/form.js';
		$this->scripts[] = '/assets/lib/arckane/models/elements.js';
		
		parent::__construct($data, $page);
		
		$this->message = array(
			'type' => 'error',
			'message' => $this->lang->get('error_load')
		);
	}
	public function get() {
		$this->message['type'] = 'success';
		$this->message['message'] = $this->lang->get('success_load');		
		
		if (in_array('module', array_keys($this->data))) {
			$module = $this->extension('modules')->get($this->data['module'], $this->data);
			
			if ($module) {
				$this->message['content'] = $module;
				$this->message['scripts'] = array_merge($this->scripts, $module->scripts);
			} else {
				$this->message['message'] = $this->lang->get('error_load').' - '.$this->data['module'];
			}
		}
		
		if (in_array('action', array_keys($this->data))) {
			$exe = $this->extension($this->data['action'], $this->data);
			$this->message['content'] = $exe->render();
			$this->message['scripts'] = array_merge($this->scripts, $exe->scripts);
		}
		
		return json_encode($this->message);
	}
}