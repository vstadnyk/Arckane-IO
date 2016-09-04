<?
final class Request {
	public $extension = '.html';
	
	function __construct () {
		$this->protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https' : 'http';
		$this->http = $this->protocol.'://'.getenv('HTTP_HOST');
		$this->root = getenv('DOCUMENT_ROOT');
		$this->get = $_GET;
		$this->post = $_POST;
		$this->cookie = $_COOKIE;
		$this->session = $_SESSION;
		$this->files = $_FILES;
		$this->server = $_SERVER;

		$this->uri = $this->server['REQUEST_URI'];
	}
	public function load ($file = false, $name = '', $data = array(), $extend = array()) {
		if (!$file) {
			exit('Error: File not set!');
		}
		
		if (file_exists($this->root.dirname($this->server['PHP_SELF']).$file)) {
			$file = dirname($this->server['PHP_SELF']).$file;
		}
		
		if (file_exists($this->root.$file)) {
			$class = '';
			
			foreach (explode('-', $name) as $part) {
				$class .= ucfirst($part);
			}
			
			include_once $this->root.$file;

			$class = new $class($data, $extend);

			return $class;
		} else {
			exit('Error: Could not load file '.$this->root.$file.'!');
		}
	}
	public function build_url ($chpu = '/', $lang = false, $frontpage = false) {
		$config = $this->config('main', 'system');
		
		$url = $this->http;
		//$url .= $lang != $config['language'] ? '/'.$lang : '';
		$url .= $frontpage ? '/' : $chpu.$this->extension;
		return $url;
	}
	public function get_url () {
		return $this->http.$this->uri;
	}
	public function config ($file, $key = false) {
		$json = $this->getJSON('/config/'.$file.'.json', $key);
		
		if (!$json) exit('Error: Could not load config '.$file.'!');
		
		return $json;
	}
	public function getJSON ($file, $key = false) {
		$json = array();
	
		if (file_exists($this->root.dirname($this->server['PHP_SELF']).$file)) {
			$file = dirname($this->server['PHP_SELF']).$file;
		}
		
		if (file_exists($this->root.$file)) {
			$json = json_decode(file_get_contents($this->root.$file), true);
		} else {
			return false;
		}
		
		return $key ? $json[$key] : $json;
	}
	public function base_name ($file, $suffix = false) {
		if (function_exists('basename')) return basename($file);
		
		if ($suffix) {
			$tmpstr = ltrim(substr($file, strrpos($file, '/')), '/');
			if ((strpos($file, $suffix) + strlen($suffix)) == strlen($file)) {
				return str_ireplace($suffix, '', $tmpstr);
			} else {
				return ltrim(substr($file, strrpos($file, '/')), '/');
			}
		} else {
			return ltrim(substr($file, strrpos($file, '/')), '/');
		}
	}
	public function cookie_set ($name, $value, $path = '/') {
		setcookie($name, $value, time()+60*60*24*365, $path);
	}
	public function cookie_del ($name) {
		setcookie($name, '', time() - 3600);
	}
}