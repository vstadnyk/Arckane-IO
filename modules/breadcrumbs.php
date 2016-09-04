<?
class ModBreadcrumbs extends Prototype {
	function index () {
		if ($this->page->is_home || !isset($this->page->parents) || !is_array((array)$this->page->parents)) {
			return false;
		}

		$this->options = array_merge(array(
			'field' => 'razdel.name, razdel.main',
			'lang' => $this->page->lang,
			'id' => array_merge((array)$this->page->parents, array($this->page->parent_id)),
			'or' => 'razdel.main = 1',
			'order' => 'id',
			'data_text' => false,
			'template' => '',
			'item_template' => '',
			'separator' => ' > '
		), $this->options);

		$query = $this->db->select($this->options);
		
		$this->data['breadcrumbs'] = '';
		$this->data['current'] = $this->page->title;
		$this->data['separator'] = $this->options['separator'];
		
		$this->tmpl_index = $this->options['template'].'index.php';
		
		if (count($query)) {
			foreach ($query as $key => $item) {
				$this->data['item'] = $this->to_object($item);
				$this->data['breadcrumbs'] .= $this->tmpl->get($this->template.'/'.$this->options['item_template'].'item.php', $this->data);
			}
			
			return true;
		}
		
		return false;
	}
}