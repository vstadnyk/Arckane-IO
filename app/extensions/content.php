<?
final class ExtensionContent extends Extension {
	private $items = false;
	
	function __construct ($data, $page) {
		parent::__construct($data, $page);

		$this->page->title = $this->lang->get('page_title');
	}
}