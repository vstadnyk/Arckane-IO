<?
final class ExtensionUser extends Extension {
	public $login = false;
	
	function __construct ($data = array(), $page) {
		parent::__construct($data, $page);
		$this->login = $this->extension('login')->_login();
	}
}