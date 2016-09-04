<?
final class ModelPhoto extends Model {
	public function get ($data = array()) {
		$data = array_merge(array(
			'table' => 'photos'
		), $data);
		
		return $this->db->select($data);
	}
}