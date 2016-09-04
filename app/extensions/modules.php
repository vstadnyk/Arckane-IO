<?
final class ExtensionModules extends Extension {
	public $list = array();
	
	function __construct ($data = array(), $page) {
		parent::__construct($data, $page);

		$this->template = $this->page->template.'/modules/';
		$this->root = $this->request->root.'/modules/';
	}
	public function get ($module = false, $options = array()) {
		if (!$module) return 'Error: Module not set!';
		if (!file_exists($this->root.$module.'.php')) return 'Error: Module '.$module.' not exists!';
		
		$options = array_merge(array(
			'path' => $this->root.$module
		), $options);
		
		$this->list[$module] = $options;

		return $this->render($module);
	}
	public function render ($module_name = false) {
		if (!$module_name) return 'Error: Module not set!';
		
		$module = $this->request->load('/modules/'.$module_name.'.php', 'mod-'.$module_name, $this->page);
		
		$dic = 'modules/'.$module_name.'.json';

		if (file_exists($this->request->root.dirname($this->request->server['PHP_SELF']).'/lang/'.$this->page->lang.'/'.$dic)) {
			$this->lang->dic[] = $dic;
		}
		
		$module->lang = $module->tmpl->lang = $this->lang;
			
		$module->extend(array(
			'name' => $module_name,
			'path' => $this->root.$module_name,
			'view' => $this->template.$module_name.'/',
			'page' => $this->page,
			'options' => $this->list[$module_name],
			'scripts' => array(),
			'links' => array(),
			'template' => $this->template.$module_name.'/',
			'tmpl_index' => 'index.php'
		));
		
		$module->tmpl->page = $module->page;
		
		if ($module->index()) {
			$this->extend(array(
				'scripts' => array_merge($module->scripts, $this->scripts),
				'links' => array_merge($module->links, $this->links)
			));

			$html = $this->tmpl->get($module->template.$module->tmpl_index, $module->data);
			return isset($module->options['wrapper']) ? str_replace('{[module]}', $html, $module->options['wrapper']) : $html;
		}
		
		return false;
	}
}