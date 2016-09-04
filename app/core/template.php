<?
final class Template {
	public $file = false;
	public $data = array();
	
	public function get ($file = false, $data = array(), $wrapper = false) {
		$this->img = $this->extension('image');
		$this->file = $this->request->root.$file;
		$this->data = $data;
		$this->tmpl = $this;
	
		$html = $this->render();
		return $wrapper ? str_replace('{[html]}', $html, $wrapper) : $html;
	}
	public function model ($model = false, $data = array()) {
		return $this->request->load('/app/models/'.$model.'.php', 'model-'.$model, $data);
    }
	public function extension ($extension = false, $data = array()) {
		return $this->request->load('/app/extensions/'.$extension.'.php', 'extension-'.$extension, $data);
    }
	protected function render () {
		if (file_exists($this->file)) {
			if (is_array($this->data)) {
				extract($this->data);
			}
      		ob_start();
	  		include($this->file);
	  		$content = ob_get_contents();
     		ob_end_clean();
      		return $content;
    	} else {
      		exit('Error: Could not load template '.$this->file.'!');
    	}
	}
}