<?php
error_reporting(E_ALL);

// Melde alle PHP Fehler
error_reporting(-1);

// Dies entspricht error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('default_charset', 'utf-8');

spl_autoload_register(function ($class) {
	include('./classes/'.$class.'.php');
});

Config::sql_connect();

/**
 * bob patters
 */
Bob::$patterns = [
	'numdotjson'   => '[0-9]+\.json',
	'paste'        => '[a-zA-Z0-9]{12}',
	'pastedotjson' => '[a-zA-Z0-9]{12}\.json',
	'pastedotmd'   => '[a-zA-Z0-9]{12}\.md',
	'pastedottxt'  => '[a-zA-Z0-9]{12}\.txt'
];

/**
 * welcome
 */

Bob::get('/', function() {
	View::add('header');
	View::add('editor');
	View::add('footer');
});

/**
 * ui
 */

Bob::get('/help', function() {
	header('location: '.Config::path('base').Config::path('help'));
	exit();
});

Bob::get('/:paste', function($paste) {
	if($paste = get_paste($paste, true)) {
		View::add('header', ['paste' => $paste]);
		View::add('paste', ['paste' => $paste]);
	} else {
		View::add('header');
		View::add('error', ['code' => 404]);
	}

	View::add('footer');
});

Bob::get('/fork/:paste', function($paste) {
	View::add('header');

	if($paste = get_paste($paste, true))
		View::add('fork', ['paste' => $paste]);
	else View::add('error', ['code' => 404]);

	View::add('footer');
});

Bob::get('/delete/:paste/:paste', function($token, $key) {
	View::add('header');

	if(Paste::delete($token, $key))
		View::add('delete');
	else View::add('error', ['code' => 403]);

	View::add('footer');
});

Bob::get('/recent', function() {
	View::add('header');

	$pastes = Paste::get_num(20);

	View::add('recent', ['pastes' => $pastes]);

	View::add('footer');
});

Bob::post('/add', function() {
	if(isset($_POST['brobdingnagian']) and $_POST['brobdingnagian'] == '')
		if($token = Paste::save($_POST['text'], $_POST['parent'], $_POST['hidden']))
			header('location: '.Config::path('base').'/'.$token);
		else header('location: '.Config::path('base').'/');
	else header('location: '.Config::path('base').'/');

	exit();
});

Bob::get('/:pastedotmd', function($paste) {
	if($paste = get_paste(remext($paste), true))
		echo Parsedown::instance()->text($paste['text']);
	else header('location: '.Config::path('base').'/');
});

/**
 * api
 */

Bob::get('/:pastedottxt', function($paste) {
	if($paste = get_paste(remext($paste), true))
		View::add('raw', ['paste' => $paste]);
	else header('Status: 404 Not Found');
});

Bob::get('/:pastedotjson', function($paste) {
	header('Content-type: application/json');

	if($paste = get_paste(remext($paste)))
		echo json_encode($paste);
	else header('Status: 404 Not Found');
});

Bob::get('/recent/:numdotjson', function($num) {
	header('Content-type: application/json');

	$num = remext($num);

	if($num <= 100) {
		$pasts = [];

		foreach(Paste::get_num($num) as $paste)
			$pasts[] = get_paste($paste['token']);

		echo json_encode($pasts);
	} else header('Status: 404 Not Found');
});

/**
 * here you go
 */

Bob::go(Config::path('base'));

/**
 * default 404
 */

Bob::notfound(function() {
	View::add('header');
	View::add('error', ['code' => 404]);
	View::add('footer');
});

/**
 * receive paste and parents
 */

function get_paste($token, $text = false) {
	if($paste = Paste::get($token)) {
		$data = [
			'date'   => $paste['date'],
			'token'  => $paste['token'],
			'hidden' => ($paste['hidden'] == 'true'),
			'url'    => Config::path('url').Config::path('base').'/'.$paste['token'],
			'raw'    => Config::path('url').Config::path('base').'/'.$paste['token'].'.txt',
			'md'     => Config::path('url').Config::path('base').'/'.$paste['token'].'.md',
			'json'   => Config::path('url').Config::path('base').'/'.$paste['token'].'.json',
			'fork'   => Config::path('url').Config::path('base').'/fork/'.$paste['token']
		];

		if($paste['parent'] != '' and $parent = get_paste($paste['parent'], $text))
			$data['parent'] = $parent;

		if($text)
			$data['text'] = Paste::get_text($paste['file']);

		return $data;
	} else return false;
}

function remext($file) {
	return str_replace(strrchr($file, '.'), '', $file);
}
