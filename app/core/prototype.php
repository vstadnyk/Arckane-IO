<?
abstract class Prototype {
	public $_name;
	public $data = array();
	public $page = false;
	public $dic = array();
	
	function __construct ($page = false) {
		$this->request = new Request();
		$this->config = $this->request->config('main');
		$this->dic[] = 'main.json';
		
		$this->page = $page;
		
		$this->db = new MySQL();
		$this->lang = new Language();
		$this->tmpl = new Template();
		
		$this->lang->current = $this->page && isset($this->page->lang) ? $this->page->lang : $this->config['system']['language'];
		$this->lang->dic = $this->dic;
		
		$this->tmpl->lang = $this->lang;
		$this->tmpl->request = $this->request;
		$this->tmpl->config = $this->config;
		
		$this->_name = get_class($this);
	}
	public function model ($model = false, $data = array()) {
		return $this->request->load('/app/models/'.$model.'.php', 'model-'.$model, $data);
    }
	public function extension ($extension = false, $data = array()) {
		$exe = $this->request->load('/app/extensions/'.$extension.'.php', 'extension-'.$extension, $data, $this->page);
		return $exe;
    }
	public function extend ($data) {
		if (is_array($data)) {
			foreach ($data as $name => $value) {
				$this->{$name} = $value;
			}
		} else {
			$file = $this->request->root.'/'.$data;
			if (file_exists($file)) {
				include_once ($file);
			} else {
				exit('Error: Could not load file '.$file.'!');
			}
		}

		return $this;
	}
	public function to_object ($array = array()) {
		return json_decode(json_encode($array, JSON_FORCE_OBJECT));
	}
	public function to_array ($obj) {
		if(is_object($obj)) {
			$obj = get_object_vars($obj);
		} else {
			return $obj;
		}

		if(is_array($obj)) {
			return array_map(array($this, 'to_array'), $obj);
		} else {
			return $obj;
		}
	}
	public function tree ($data, $parent = 0) {
		$result = array();

		foreach ($data as $item) {
			if ($item['parent'] == $parent) {
				$childs = $this->tree($data, $item['id']);
				if ($childs) {
					$item['childs'] = $childs;
				}
				$result[$item['id']] = $item;
			}
		}
		
		return $result;
	}
	public function __get ($key) {
		return isset($this->{$key}) ? $this->{$key} : $key;
	}
	public function execute ($str) {
		if (strstr($str, '{[')) { /* вызов простой функции - {[fn(arg)]} */
			preg_match_all('/\{\[(.+)\]\}/U', $str, $matches);
			$matches = end($matches);
			$matches = end($matches);

			if (strstr($matches, '(')) {
				preg_match_all('/\((.+)\)/U', $matches, $arg);
				$arg = array_pop($arg);
				$arg = array_pop($arg);
				
				$function = preg_replace('/(\(([^>]+)\))/U', '', $matches);
				$function = str_replace(array('(', ')'), '', $function);

				$str = call_user_func($function, $arg);
			}
		} elseif (strstr($str, '{{')) { /* вызов метода класса - {{this->method}} */
			preg_match_all('/\{\{(.+)\}\}/U', $str, $matches);
			$matches = end($matches);
			
			foreach ($matches as $match) {
				$tmp = explode('->', $match);
				$arg = array_pop($tmp);
				$call = $tmp[0] == 'lang' ? $this->lang->get($arg) : call_user_func_array(array($this, '__get'), array($arg));
				$str = str_replace('{{'.$match.'}}', $call, $str);
			}
		}
		
		return $str;
	}
}