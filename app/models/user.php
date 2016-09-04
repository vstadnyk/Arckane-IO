<?
final class ModelUser extends Model {
	public function get () {
		$this->data['table'] = 'user';
		$this->data['order'] = 'status';
		
		return $this->db->select($this->data)->row;
	}
}