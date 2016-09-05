<? session_start();

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('log_errors', true);

if (version_compare(phpversion(), '5.4.0', '<') == true) {
	exit('PHP 5.4+ Required');
}

if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[clean($key)] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_COOKIE = clean($_COOKIE);
}

$root = __DIR__;
$core = $root.'/app/core/';

require_once($core.'prototype.php');

foreach (glob($core.'*.php') as $file) {
	file_exists($file) && is_file($file) ? require_once($file) : exit('Error load core. File '.$file.' not exists.');
}

ini_set('error_log', $root.'/log/error.log');