<?
final class ExtensionRegister extends Extension {
	function __construct ($data = array(), $page) {
		$this->scripts[] = '/assets/lib/arckane/models/form.js';
		
		parent::__construct($data, $page);
		
		$this->form = $this->extension('form', array_merge(array(
			'form' => 'register',
			'vars' => array(
 				'form_title' => $this->lang->get('form_title'),
				'button_register' => $this->lang->get('button_register'),
				'select_category' => $this->lang->get('select_category'),
				'select_categories' => json_encode($this->db->select(array(
					'table' => 'category',
					'type' => 'talent',
					'status' => 1
				))->rows)
			)
		), $this->data));
	}
	public function render () {
		return $this->form->render();
	}
}