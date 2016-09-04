<?
class ModLang extends Prototype {
	function index () {
		$config = array_merge(array($this->config['system']['language']), $this->config['system']['languages']);
		$langs = $url = array();

		foreach ($config as $code) {
			$url['/'.$code] = '';
		}
		
		foreach ($config as $code) {
			if ($this->page->lang == $code) continue;
			$langs[$code] = array(
				'href' => '/'.$code.strtr($this->request->uri, $url)
			);
		}
		
		$this->data['langs'] = $this->to_object($langs);
		
		return true;
	}
}