<?
final class View extends Prototype {
	public $page;
	public $content;
	private $headers = array();
	private $metatags = array();
	private $links = array();
	private $scripts = array();
	private $robots = 'index, follow';
	
	function __construct ($page = array(), $headers = array()) {
		parent::__construct();
		$this->page = $page;
		$this->headers = $headers;
	}
	public function render ($render = true) {
		$this->headers = array_unique($this->headers);
		
		foreach ($this->headers as $header) {
			header($header, true);
		}

		$this->set_link($this->modules->links);
		$this->set_script($this->modules->scripts);
		
		if (isset($this->page->links)) {
			$this->set_link($this->to_array($this->page->links));
		}
		
		if (isset($this->page->scripts)) {
			$this->set_script($this->to_array($this->page->scripts));
		}
		
		$this->links = array_unique($this->links);
		$this->scripts = array_unique($this->scripts);
		$this->metatags = array_unique($this->metatags);

		//$this->content = $this->layout($this->page->template.'/'.$this->page->file, $this->data);

		if (!$render) return $this->content;

		echo $this->layout('index');
	}
	public function init () {
		$this->content = $this->extension('content');
		$this->modules = $this->extension('modules');
		$this->img = $this->extension('image');
		
		if (isset($this->page->keywords) && isset($this->page->keywords)) {
			$this->set_meta(array(
				'keywords' => $this->page->keywords,
				'description' => $this->page->description
			));
		}
		
		if (isset($this->request->get[$this->config['system']['page_items']])) {
			$this->robots = 'noindex, follow';
		}

		$this->set_meta('robots', $this->robots);
	}
	public function get ($key) {
		!is_array($key) ? $key = array($key) : false;
		$result = array();

		foreach ($key as $item) {
			$result[] = count($this->{$item}) ? implode(PHP_EOL.'	', $this->{$item}) : '';
		}

		return implode(PHP_EOL.'	', $result).PHP_EOL;
	}
	public function load ($file = false, $data = false) {
		$file = $this->request->root.$this->page->template.'/'.$file;
		if (file_exists($file)) {
			if (is_array($data)) {
				extract($data);
			}
      		ob_start();
	  		include($file);
	  		$content = ob_get_contents();
     		ob_end_clean();
      		return $content;
    	} else {
      		exit('Error: Could not load layout '.$file.'!');
    	}
	}
	public function layout ($file = false, $data = false) {
		return $this->load($file.'.php', $data);
	}
	public function html_part ($file, $data = false) {
		return $this->load('/content/'.$this->page->view.'/'.$file.'.php', $data);
	}
	public function set_link ($href = array(), $rel = 'stylesheet', $type = false) {
		!is_array($href) ? $href = array($href) : false;
		$type ? $type = ' type="'.$type.'"' : false;

		foreach ($href as $item) {
			$this->links[] = '<link href="'.$item.'" rel="'.$rel.'"'.$type.'>';
		}
	}
	public function set_meta ($metatags = array(), $content = '') {
		!is_array($metatags) ? $metatags = array($metatags => $content) : false;
		
		foreach ($metatags as $name => $content) {
			$this->metatags[$name] = '<meta name="'.$name.'" content="'.$content.'">';
		}
	}
	public function set_share_meta ($metatags = array(), $content = '') {
		!is_array($metatags) ? $metatags = array($metatags => $content) : false;
		
		foreach ($metatags as $name => $content) {
			$this->metatags[$name] = '<meta property="'.$name.'" content="'.$content.'">';
		}
	}
	public function set_script ($href = array()) {
		!is_array($href) ? $href = array($href) : false;

		foreach ($href as $item) {
			$this->scripts[] = '<script src="'.$item.'"></script>';
		}
	}
	public function set_header ($header) {
		$this->headers[] = $header;
	}
	public function __get ($method) {
		return false;
	}
}