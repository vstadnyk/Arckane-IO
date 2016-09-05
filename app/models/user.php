<?
final class ModelUser extends Model {
	public function get () {
		$this->data['table'] = 'user';
		$this->data['order'] = 'status';
		
		return $this->db->select($this->data)->row;
	}
	public function set() {
		$this->data['table'] = 'user';
		$this->data['status'] = 0;
		$this->data['category'] = 11;
		$this->data['secret'] = sha1($this->data['mail']);
		$this->data['join'] = array(
			'table' => 'user_data',
			'on' => 'user_data.user = user.id'
		);
		
		//print_r($this->db->insert($this->data));
		
		return $this->db->insert($this->data);
	}
}