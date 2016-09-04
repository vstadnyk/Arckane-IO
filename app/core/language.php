<?
final class Language {
	private $data = array();
	public $dic = array();
	
	function __construct () {
		$this->request = new Request();
		$this->db = new MySQL();
		$this->config = $this->request->config('main');
	}
	public function get ($key = false) {
		if (count($this->dic)) {
			foreach ($this->dic as $file) {
				$this->extend($file);
			}
		}
		
		if ($key && !isset($this->data[$key])) {
			return $key;
		}
		
		return $key ? $this->data[$key] : $this->data;
	}
	public function extend ($file) {
		$this->data = array_merge($this->data, $this->load($file));
		return $this->data;
	}
	public function load ($file) {
		$json = $this->request->getJSON('/lang/'.$this->current.'/'.$file);

		if (!$json) exit('Error: Could not load dictionary '.$file.'!');
		
		return $json;
	}
	public function rdate () {
		$rdate = $this->get('rdate');
		return func_num_args() ? strtr(date(func_get_arg(0), func_get_arg(1)), $rdate) : strtr(date(func_get_arg(0)), $rdate);
	}
}