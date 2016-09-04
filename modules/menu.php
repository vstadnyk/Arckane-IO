<?
class ModMenu extends Prototype {
	function index () {
		$this->options = array_merge(array(
			'lang' => $this->page->lang,
			'tmpl' => 'default',
			'no_href' => false,
			'use_attributes' => false,
			'append' => array(),
			'get_from_config' => false,
			'get_from_array' => false
		), $this->options);
	
		$this->tmpl_index = $this->options['tmpl'].'.php';
		$this->options['item_template'] = $this->options['tmpl'].'-item';
		
		if ($this->options['get_from_config']) {
			$query = $this->request->config('modules/menu/'.$this->options['get_from_config']);
		} elseif ($this->options['get_from_array']) {
			$query = $this->options['get_from_array'];
		} else {
			$query = $this->model('menu', $this->options)->get_tree();
		}
		
		$this->data['options'] = $this->to_object($this->options);
		$this->data['menu'] = '';
		$this->data['menu'] .= implode('', array_map(array($this, 'item'), $query));
	
		return true;
	}
	private function item ($item) {
		$this->data['childs'] = '';
	
		$item['active'] = isset($this->page->current_id) && $item['id'] == $this->page->current_id ? true : false;
		$this->page->file == 'item' && $item['id'] == $this->page->parent_id ? $item['active'] = true : false;
		
		if (isset($item['childs']) && count($item['childs'])) {
			$data = array(
				'menu' => implode('', array_map(array($this, 'item'), $item['childs']))
			);
			
			$this->data['childs'] = $this->tmpl->get($this->template.'/'.$this->options['tmpl'].'.php', $data);
		}
		
		$this->data['item'] = $this->to_object($item);

		return $this->tmpl->get($this->template.'/'.$this->options['item_template'].'.php', $this->data);
    }
}