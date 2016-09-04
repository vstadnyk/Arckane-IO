<?
abstract class Model extends Prototype {
	public $data;
	
	function __construct ($data = array()) {
		parent::__construct();
		$this->data = $data;
	}
}