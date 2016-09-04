<?
class ModForm extends Prototype {
	function index () {
		$this->options = array_merge(array(
			'form' => 'default',
			'template' => 'form'
		), $this->options);
		
		$this->tmpl_index = $this->options['template'].'.php';
		$this->form = $this->extension('form', array('form' => $this->options['form'], 'lang' => $this->page->lang));
		$this->data['form'] = $this->form;
		
		return true;
	}
}