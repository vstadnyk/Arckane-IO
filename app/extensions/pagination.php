<?
final class ExtensionPagination extends Extension {
	function __construct ($data = array(), $page) {
		parent::__construct($data, $page);
		
		$this->data = array_merge(array(
			'count' => 0,
			'arrows' => true,
			'type' => 'items',
			'dots' => false,
			'template' => 'default'
		), $this->data);
		
		$this->data = $this->to_object($this->data);
		isset($this->data->view) ? $this->view = $this->data->view : false;
		$this->view = $this->view.'/extensions/pagination/';
	}
	public function render () {
		$this->pages = ceil($this->data->count / $this->data->limit);
		
		if (!($this->pages-1) || !$this->data->count) return false;

		$i = 0;
		$dots = false;
		$tmpl = '';
		
		$param = $this->config['system']['page_'.$this->data->type];
		$this->data->current = isset($this->request->get[$param]) ? $this->request->get[$param] : 1;
		$current_limit = $this->data->current + 3;
		$get = preg_replace('/[?&]'.$param.'=[^&]+$|([?&])'.$param.'=[^&]+&/', '', $this->request->get_url());
		$parse = parse_url($get);
		
		$url = $parse['scheme'].'://'.$parse['host'].$parse['path'];
		$url .= isset($parse['query']) ? '?'.$parse['query'].'&' : '?';
		$url .= $param.'=';
		
		do {
			$i++;

			if ($this->data->dots && $i == $current_limit && $current_limit < $this->pages - 1) {
				$dots = true;
				break;
			}
			
			$this->data->page = $i;
			$this->data->href = !($i - 1) ? $get : $url.$i;
			
			$tmpl .= $this->tmpl->get($this->view.$this->data->template.'-item.php', (array)$this->data);
		} while ($i < $this->pages);
		
		$this->data->dots = $dots;
		$this->data->last = array('href' => $url.$this->pages, 'text' => $this->pages);
		$this->data->pagination = $tmpl;
		
		$this->data->prev = $this->data->arrows && $this->data->current - 1 ? $url.($this->data->current - 1) : false;
		$this->data->next = $this->data->arrows && ($this->data->current + 1) <= $this->pages ? $url.($this->data->current + 1) : false;
		
		isset($this->data->show_all) ? $this->data->show_all = $url.'0' : '';
		
		return $this->tmpl->get($this->view.$this->data->template.'-index.php', (array)$this->data);
	}
	public function get_limit () {
		$current = 0;
		$limit = $this->data->limit;
		$page_items = $this->config['system']['page_items'];
		
		if(isset($this->request->get[$page_items]) && is_numeric($this->request->get[$page_items])){
			$current = $this->request->get[$page_items];
		}
		
		return $current ? (($current - 1) * $limit).', '.$limit : $limit;
	}
}