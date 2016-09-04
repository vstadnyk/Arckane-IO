<?
final class ModelMenu extends Model {
	public function __construct($data = array()) {
		$data = array_merge(array(
			'table' => 'menu',
			'join' => array(
				'table' => 'router',
				'fields' => 'id, url',
				'on' => 'menu.router = router.id'
			)
		), $data);
		
		parent::__construct($data);
	}
	public function get () {
		$menu = $this->db->select($this->data);
		
		if ($menu->num_rows) {
			foreach ($menu->rows as $key => $value) {
				$menu->rows->{$key}->url = $value->router_url;
			}
		}
		
		return $menu;
	}
	public function get_tree () {
		return $this->get()->num_rows ? $this->tree($this->to_array($this->get()->rows)) : array();
	}
}