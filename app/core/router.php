<?
final class Router extends Prototype {
	public $page = array();
	public $headers = array();

	public function ajax () {
		$post = $this->request->post;
		$get = $this->request->get;
		
		$this->init();
		
		if (count($get) && isset($get['module'])) {
			$data = array_diff($get, array(
				'module' => $get['module']
			));
			
			return $this->extension('modules', $this->page)->get($get['module'], $data);
		}
		
		if (count($get) && isset($get['type']) && isset($get['todo'])) {
			echo $this->extension($get['type'], $post)->{$get['todo']}();
		}
	}
	public function init () {
		$this->headers[] = 'Content-Type: text/html; charset=utf-8';

		if ($this->request->server['QUERY_STRING']) {
			parse_str($this->request->server['QUERY_STRING'], $this->page);
		}

		$lang = !isset($this->request->cookie['lang']) ? $this->config['system']['language'] : $this->request->cookie['lang'];
		
		!isset($this->page['lang']) ? $this->page['lang'] = $lang : false;

		$this->request->cookie_set('lang', $this->page['lang']);

		$this->page = $this->to_object($this->page);
		$this->page->user = false;
		$this->page->template = '/view/'.$this->config['system']['template'];
		$this->page->file = 'index';
		$this->page->title = '';
	}
	public function redirect ($url = false) {
		if (!$url) return;
		$this->headers[] = 'Location: '.$url;
	}
}