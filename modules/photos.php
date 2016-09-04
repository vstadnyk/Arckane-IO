<?
class ModFrontpagePhotos extends Prototype {
	function index () {
		$this->options = array_merge(array(
			'field' => 'photos.*',
			'table' => 'photos',
			'seo' => false,
		), $this->options);
		
		$items = $this->db->select($this->options);
		$this->data['items'] = $this->to_object($this->options);
	}
}