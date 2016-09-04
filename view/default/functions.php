<?
$this->set_link(array(
	'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css',
	'https://fonts.googleapis.com/css?family=Roboto:400,100italic,100,300,300italic,400italic,500,500italic,700,700italic,900,900italic&subset=latin,cyrillic-ext,latin-ext,cyrillic',
	'/assets/lib/magnific-popup/magnific-popup.min.css',
	'/assets/lib/arckane/arckane.css',
	'/assets/css/typo.css',
	'/assets/css/styles.css'
));

$this->set_script(array(
	'https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js',
	'/assets/lib/magnific-popup/magnific-popup.min.js',
	'/assets/lib/es6.js',
	'/assets/lib/arckane/arckane.js',
	'/assets/lib/arckane/models/element.js',
	'/assets/lib/arckane/models/router.js'
));

$this->menu = $this->modules->get('menu', array(
	'category' => 1,
	'status' => 1,
	'use_attributes' => true
));

$this->user_menu = $this->modules->get('menu', array(
	'category' => 2,
	'status' => 1,
	'use_attributes' => true
));

if ($this->page->user) {
	
} else {
	$this->set_script(array(
		'/assets/lib/md5.min.js'
	));
}