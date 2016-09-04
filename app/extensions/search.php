<?
final class ExtensionSearch extends Extension {
	public $encoding;
	public $pagination = false;
	public $string = false;
	public $words = array();
	public $items = array();
	
	function __construct ($data = array(), $page) {
		parent::__construct($data, $page);

		$this->encoding = $this->config['search']['encoding'];
		
		require_once ($this->request->root.'/app/libs/phpmorphy/src/common.php');
		
		$phpmorphy_dir = $this->request->root.'/app/libs/phpmorphy/dicts';
		$phpmorphy_lang = $this->lang->get('locale');
		$phpmorphy_opts = array(
			'storage' => PHPMORPHY_STORAGE_FILE,
		);
		
		try {
			$this->morphy = new phpMorphy($phpmorphy_dir, $phpmorphy_lang, $phpmorphy_opts);
		} catch(phpMorphy_Exception $error){
			die('Error occured while creating phpMorphy instance: '.$error->getMessage());
		}
		
		$this->string = trim($this->request->get['query']);
		$this->system_config = $this->config['system'];
		$this->config = $this->config['search'];
		
		$this->pagination = $this->extension('pagination', array('limit' => $this->config['limit']));
		$this->form = $this->extension('form', array('form' => 'search', 'vars' => array('query' => $this->string)));
	}
	public function result () {
		if ($this->error()) {
			return $this->error();
		}
		
		$this->items();
		$result = array();

		foreach ($this->items as $item) {
			$item = $this->relevation($item);
			$item = $this->mark($item);
			$result[] = $item;
		}
		
		function cmp1 ($a, $b) {
			if ($a['relevation'] == $b['relevation']) return 0;
			if ($a['relevation'] > $b['relevation']) return -1;
			if ($a['relevation'] < $b['relevation']) return 1;
		}
		
		uasort($result, 'cmp1');

		$this->items = $result;
		
		return $this->to_object($this->items);
	}
	private function error () {
		if (empty($this->string)) return array('error' => 'empty_string');
		if (mb_strlen($this->string, $this->encoding) < 3) return array('error' => 'short_string');
		
		return false;
	}
	private function relevation ($item) {
		$item['relevation'] = 0;
		
		foreach ($item as $field => $value) {
			foreach ($this->words as $word) {
				if ($field !== 'href' && !empty($value)) {
					$item['relevation'] += substr_count(mb_strtoupper($value, $this->encoding), $word);
				}
			}
		}

		return $item;
	}
	private function mark ($item) {
		foreach ($item as $field => $value) {
			if ($field == 'href' || $field == 'relevation') continue;
			foreach ($this->_explode() as $word) {
				$value = preg_replace('/'.$word.'/is', '<mark>$0</mark>', $value);
			}
			$item[$field] = $value;
		}
		
		return $item;
	}
	public function items () {
		$fields = array();
		$this->items = $this->_get();
		
		if (count($this->items)) {
			foreach ($this->items as $key => $item) {
				if (isset($item['data_announce']) && empty($item['announce'])) {
					$this->items[$key]['announce'] = $item['data_announce'];
				}
				
				if (isset($item['data_content']) && empty($item['content'])) {
					$this->items[$key]['content'] = $item['data_content'];
				}
			}
		}

		return $this->items;
	}
	private function _explode () {
		return explode(' ', $this->string);
	}
	private function _get () {
		$query = $where = $result = array();
		$tables = $this->config['tables'];

		foreach ($this->_explode() as $word) {
			$morphy = $this->morphy->getAllFormsWithGramInfo(mb_strtoupper($word, $this->encoding), true);
			count($morphy[0]['forms']) ? $this->words = array_merge($morphy[0]['forms'], $this->words) : $this->words[] = mb_strtoupper($word, $this->encoding);
		}
		
		$this->words = array_unique($this->words);
		
		foreach ($tables as $table => $fields) {
			foreach ($fields as $field) {
				foreach ($this->words as $word) {
					$where[$table][] = $table.'.'.$field.' LIKE "%'.$word.'%"';
				}
			}
			
			$field = $table.'.id, '.$table.'.parent, '.$table.'.name, '.$table.'.announce, '.$table.'.seo, seo.chpu, seo.parent_chpu';
			$join = ' LEFT JOIN seo ON seo.id = '.$table.'.seo';
			
			$query[] = '(SELECT '.$field.' FROM '.$table.$join.' WHERE '.$table.'.shown = 1 AND ('.implode(' OR ', $where[$table]).'))';
		}
		
		$query = implode(' UNION ALL ', $query);
		
		$this->pagination->data->count = $this->db->get($query)->num_rows;
		$query = $this->db->get($query.' LIMIT '.$this->pagination->get_limit());
		
		
		if ($query->num_rows) {
			$result = $query->rows;

			foreach ($result as $key => $item) {
				if ($item['seo'] || !empty($item['chpu'])) {
					$result[$key]['href'] = $this->request->build_url($item['parent_chpu'].$item['chpu']);
				} else {
					$result[$key] = (array)$this->model('razdel')->get_one($item['parent']);
				}
			}
		}

		return $result;
	}
}