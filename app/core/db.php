<?
class MySQL extends MySQLi {
	private $connection;
	private $uploaded = array();
	public $request;
	
	function __construct () {
		$this->request = new Request();
		$this->config = $this->request->config('main');
		$this->SQLconfig = $this->request->config('sql');

		extract($this->SQLconfig['connect']);
		
		parent::__construct($hostname, $username, $password, $database);
		
		if ($this->connect_errno) {
            throw new exception($this->connect_errno, $this->connect_errno);
			exit;
        }
		
		$this->set_charset($charset);
		
		/* $this->query("SET NAMES 'utf8'");
		$this->query('SET CHARACTER SET utf8');
		$this->query('SET CHARACTER_SET_CONNECTION = utf8');
		$this->query("SET SQL_MODE = ''"); */
	}
	public function escape ($value) {
		is_array($value) ? $value = $this->real_escape_string(
			json_encode(array_filter($value), JSON_UNESCAPED_SLASHES), $this->connection
		) : false;
		
		!is_numeric($value) ? $result = '"'.$this->real_escape_string($value).'"' : $result = $value;
		
        return $result;
    }
    public function getLastId () {
        return $this->insert_id;
    }
	public function __destruct () {
        //mysql_close($this->connection);
    }
	public function get ($sql, $confirm = false) {
		$data = [];
		
		if ($result = $this->query($sql, MYSQLI_USE_RESULT)) {
			$data['row'] = $result->fetch_assoc();
			$data['rows'] = $result->fetch_all(MYSQLI_ASSOC);
			$data['num_rows'] = $result->num_rows;
			$result->num_rows ? array_unshift($data['rows'], $data['row']) : false;
			$result->free();
		} else {
			exit($this->error);
		}
		
		return json_decode(json_encode($data, JSON_FORCE_OBJECT));
	}
	public function to_object ($array = array()) {
		return json_decode(json_encode($array, JSON_FORCE_OBJECT));
	}
	public function get_fields ($data = false) {
		if (!$data) return '*';
		
		$fields = $data['table'].'.'.array_shift($data['field']);
		
		if (count($data['field'])) {
			foreach ($data['field'] as $field) {
				$fields .= ', '.$data['table'].'.'.$field;
			}
		}
		
		return $fields;
	}
	public function select ($data = array()) {
		$tables = array_keys($this->SQLconfig['tables']);
			
		$data = array_merge(array(
			'table' => array_shift($tables),
			'get_seo' => true
		), $data);
		
		$table_config = $this->SQLconfig['tables'][$data['table']];
		
		if ($data['get_seo'] && in_array('meta', array_keys($table_config['fields']))) {
			$data['join'] = array(
				'table' => 'meta',
				'fields' => 'title, description, keywords',
				'on' => $data['table'].'.meta = meta.id'
			);
		}
		
		return $this->get($this->build_select($data));
	}
	public function insert ($data = array()) {
		if ($result = $this->query($this->build_insert($data)) && isset($data['join']) && isset($data['join']['on'])) {
			$data['table'] = $data['join']['table'];
			
			$join = explode(' = ', $data['join']['on']);
			$join = explode('.', $join[0]);
			$data[array_pop($join)] = $this->getLastId();

			$result = $this->query($this->build_insert($data));
		}
		
		return $result;
	}
	private function build_insert ($data = array()) {
		if (!isset($data['table'])) return false;

		$fields = $values = array();
		$table_config = $this->SQLconfig['tables'][$data['table']];
		$fields_config = array_keys($table_config['fields']);
		
		foreach ($data as $field => $value) {
			if (in_array($field, $fields_config)) {
				$fields[] = $field;
				$values[] = $this->escape($value);
			}
		}
		
		$query = 'INSERT INTO '.$data['table'].'('.implode(', ', $fields).') values ('.implode(', ', $values).')';

		if (!($stmt = $this->prepare($query))) {
			exit ('Error: #'.$this->errno.' in prepare query '.$query);
		}

		return $query;
	}
	private function build_fields ($data = array(), $fields) {
		return array_filter(array_map(function($field) use($data) {
			if ($data['fields'] != '*' && in_array($field, explode(', ', $data['fields']))) {
				$field = $data['table'].'.'.$field;
				
				isset($data['rename']) ? $field .= ' AS '.preg_replace('/\./', '_', $field) : false;
				
				return $field;
			}
		}, $fields));
	}
	private function build_select ($data = array()) {
		if (!isset($data['table'])) return false;
		
		$data = array_merge(array(
			'fields' => '*',
			'order' => 'position',
			'sort' => 'ASC',
			'limit' => false,
			'join' => false,
			'and' => false,
			'or' => false
		), $data);
		
		$query = 'SELECT ';
		
		$table_config = $this->SQLconfig['tables'][$data['table']];
		$fields_config = array_keys($table_config['fields']);
		
		$fields = $data['fields'] == '*' ? array($data['table'].'.'.$data['fields']) : $this->build_fields($data, $fields_config);
		
		if ($data['join']) {
			$join_config = $this->SQLconfig['tables'][$data['join']['table']];
			$data['join']['rename'] = true;
			$fields = array_merge($fields, $this->build_fields($data['join'], array_keys($join_config['fields'])));
		}
		
		$where = array_map(function($field) use($data) {
			if (in_array($field, array_keys($data))) {
				$value = $data[$field];
				
				$result = 'AND '.$data['table'].'.'.$field;
				$result .= is_array($value) ? ' IN ('.implode(', ', $value).')' : ' = "'.$value.'"';
				
				return $result;
			}
		}, $fields_config);
		
		$where = array_filter($where);
		
		$query .= implode(', ', $fields);
		$query .= ' FROM '.$data['table'];
		
		$data['join'] ? $query .= ' LEFT JOIN '.$data['join']['table'].' ON '.$data['join']['on'] : false;
		
		$query .= ' WHERE '.$data['table'].'.id != 0 ';
		$query .= implode(' ', $where);
		
		$query .= $data['and'] ? ' AND '.$data['and'] : '';
		$query .= $data['or'] ? ' OR '.$data['or'] : '';
		
		$query .= ' ORDER BY '.$data['table'].'.'.$data['order'].' '.$data['sort'];
		$query .= $data['limit'] ? ' LIMIT '.$data['limit'] : '';
		
		if (!($stmt = $this->prepare($query))) {
			exit ('Error: #'.$this->errno.' in prepare query '.$query);
		}

		return $query;
	}
}