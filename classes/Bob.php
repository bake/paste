<?php
class Bob {
	private static $passed  = 0;
	private static $refused = 0;
	private static $url     = '';
	private static $method  = '';
	private static $routes  = [];
	private static $gone    = false;

	public static function add($methods, $pattern, $callback) {
		$methods = (is_array($methods)) ? $methods : [$methods];
		$methods = array_map('strtoupper', $methods);

		static::$routes[] = [
			'methods'  => $methods,
			'pattern'  => $pattern,
			'callback' => $callback
		];
	}

	public static function get($pattern, $callback) {
		static::add(['get'], $pattern, $callback);
	}

	public static function post($pattern, $callback) {
		static::add(['post'], $pattern, $callback);
	}

	public static function put($pattern, $callback) {
		static::add(['put'], $pattern, $callback);
	}

	public static function delete($pattern, $callback) {
		static::add(['delete'], $pattern, $callback);
	}

	public static function notfound($callback) {
		if(static::$passed == 0) static::summary($callback);
	}

	private static function url_elements($url) {
		$elements = explode('/', trim(str_replace('//', '/', $url), '/'));
		$elements = static::trim_arr($elements);

		return $elements;
	}

	private static function trim_arr($arr) {
		return array_filter($arr, function($var) {
			return !empty($var);
		});
	}

	private static function is_function($value, $function) {
		if($function[0] == ':')
			return function_exists(substr($function, 1)) and  call_user_func(substr($function, 1), $value);
		else if($function[0] == '!')
			return function_exists(substr($function, 1)) and !call_user_func(substr($function, 1), $value);
		else return false;
	}

	public static function go($base = '') {
		static::$url    = $_SERVER['REQUEST_URI'];
		static::$url    = explode('?', static::$url)[0];
		static::$url    = str_ireplace($base, '', static::$url);
		static::$url    = static::url_elements(static::$url);
		static::$method = (isset($_GET['method'])) ? $_GET['method'] : $_SERVER['REQUEST_METHOD'];
		static::$method = strtoupper(static::$method);

		foreach(static::$routes as $route)
			if(static::execute($route['methods'], $route['pattern'], $route['callback']))
				static::$passed++;
			else static::$refused++;

		static::$gone = true;
	}

	private static function execute($methods, $pattern, $callback) {
		$arguments = [];
		$pattern   = static::url_elements($pattern);

		if(in_array(static::$method, $methods) and count($pattern) == count(static::$url)) {
			for($i = 0; $i < count($pattern); $i++)
				if(static::is_function(static::$url[$i], $pattern[$i]))
					$arguments[] = static::$url[$i];
				else if($pattern[$i] != static::$url[$i])
					return false;

			call_user_func_array($callback, $arguments);
			return true;
		}

		return false;
	}

	public static function summary($callback) {
		if(static::$gone) call_user_func_array($callback, [
			'passed'  => static::$passed,
			'refused' => static::$refused
		]);
	}
}
