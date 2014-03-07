<?php
class Config {
	public static $paths = [
		'url'    => 'http://foo.bar',
		'base'   => '',
		'index'  => '/index.php',
		'views'  => 'views/',
		'assets' => '/assets/',
		'pastes' => '/var/www/paste/pastes'
	];

	public static $mysql = [
		'host'   => '',
		'user'   => '',
		'pass'   => '',
		'db'     => ''
	];

	public static $db;

	public static function path($path) {
		return static::$paths[$path];
	}

	public static function mysqli_connect() {
		return static::$db = new mysqli(static::$mysql['host'], static::$mysql['user'], static::$mysql['pass'], static::$mysql['db']);
	}
}
