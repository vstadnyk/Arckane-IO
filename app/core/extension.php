<?
abstract class Extension extends Prototype {
	public $data;
	public $page;
	public $links = array();
	public $scripts = array();
	public $name;
	
	function __construct ($data = array(), $page = array()) {
		parent::__construct();
		$this->data = $data;
		$this->_name = preg_replace('/extension/', '', mb_strtolower($this->_name, 'UTF-8'));
		
		if (count($this->to_array($page))) {
			$dic = 'extensions/'.$this->_name.'.json';

			if (file_exists($this->request->root.dirname($this->request->server['PHP_SELF']).'/lang/'.$this->to_object($page)->lang.'/'.$dic)) {
				$this->lang->dic[] = $dic;
			}
			
			$this->page = $this->to_object(array_merge(
				$this->to_array($page),
				array(
					'folder' => 'extensions/',
					'file' => 'index',
					'view' => $this->_name,
					'table' => isset($page->view) ? $page->view : false,
					'type' => 'extension',
					'is_home' => false,
					'title' => isset($this->to_object($page)->title) ? $this->to_object($page)->title : $this->lang->get('page_title'),
					'h1' => isset($this->to_object($page)->h1) ? $this->to_object($page)->h1 : $this->lang->get('page_h1'),
					'links' => $this->links,
					'scripts' => $this->scripts,
					'lang' => $this->lang->current,
				)
			));
		}
	}
}