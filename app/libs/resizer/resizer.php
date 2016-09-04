<?
/*
	$_GET['image'] 	- Имя файла
	$_GET['type'] 	- Тип файла для составления пути
	$_GET['width'] 	- Ширина требуемого размера
	$_GET['height']	- Высота требуемого размера
	$_GET['method']	- Метод сжатия (параметры: exact, portrait, landscape, auto, crop)
*/

$thumbs = array(
	'enable' => true,
	'dir' => $_SERVER['DOCUMENT_ROOT'].'/img/thumbs/',
	'http' => 'http://'.getenv('HTTP_HOST').'/img/thumbs/'
);

include_once 'resize_class.php';

if (isset($_GET['image'])){
	$get = $_GET;
	$file = $get['image'];
	$dir = $get['type'].'/'.$get['method'].'/'.$get['width'].'/'.$get['height'];
	
	if (!file_exists($thumbs['dir'].$dir.'/'.$file)) {
		$resize = new resize(getenv('DOCUMENT_ROOT').'/img/'.$get['type'].'/'.$file);
		$resize->resizeImage($get['width'], $get['height'], $get['method']);
		
		if(!$thumbs['enable']){
			$resize->outputImage('80');
			return false;
		}
		
		$resize->addDir($thumbs['dir'].$dir);
		$resize->saveImage($thumbs['dir'].$dir.'/'.$file, '80');
	}
	
	header('Location: '.$thumbs['http'].$dir.'/'.$file);
	
} else {
	return false;
}