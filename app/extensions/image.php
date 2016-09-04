<?
final class ExtensionImage extends Extension {
	public function thumb ($file, $resize = false) {
		if (!$resize) return $file;
		if (!$file) $file = '/img/noimage/noimage.png';
		
		$dir = dirname(preg_replace('(/img/)', '', $file));
		$file = $this->request->base_name($file);
		$thumb = $this->config['folders']['thumbs'].$dir.'/'.$resize.'/'.$file;
		
		if (file_exists($this->request->root.$thumb)) {
			$file = $thumb;
		} else {
			$file = '/resize/'.$dir.'/'.$resize.'/'.$file;
		}
		
		return $file;
	}
}