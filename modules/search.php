<?
class ModSearch extends Prototype {
	function index () {
		$this->data['form'] = $this->extension('form', array(
			'form' => 'search',
			'vars' => array(
				'query' => isset($this->request->get['query']) ? $this->request->get['query'] : ''
			)
		));
		
		return true;
	}
}