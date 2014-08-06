<?php
class Bob {
	private static $passed   = 0;
	private static $refused  = 0;
	private static $url      = '';
	private static $method   = '';
	private static $routes   = [];
	public  static $patterns = [];

	public static function add($methods, $patterns, $callbacks) {
		static::$routes[] = [
			'methods'   => array_map('strtoupper', (is_array($methods)) ? $methods : [$methods]),
			'patterns'  => (is_array($patterns)) ? $patterns : [$patterns],
			'callbacks' => (is_array($callbacks)) ? $callbacks : [$callbacks]
		];
	}

	public static function get($pattern, $callbacks) {
		static::add('get', $pattern, $callbacks);
	}

	public static function post($pattern, $callbacks) {
		static::add('post', $pattern, $callbacks);
	}

	public static function put($pattern, $callbacks) {
		static::add('put', $pattern, $callbacks);
	}

	public static function delete($pattern, $callbacks) {
		static::add('delete', $pattern, $callbacks);
	}

	public static function notfound($callback) {
		if(static::$passed == 0) static::summary($callback);
	}

	private static function url_elements($url) {
		return explode('/', trim(preg_replace('#/+#', '/', $url), '/'));
	}

	private static function is_parsable($value, $pattern) {
		if(empty($pattern) or !in_array($pattern[0], [':', '!'])) return false;

		if(isset(static::$patterns[substr($pattern, 1)]))
			$return = (preg_match('#^'.static::$patterns[substr($pattern, 1)].'$#', $value) == 1);
		else if(function_exists(substr($pattern, 1)))
			$return = (call_user_func(substr($pattern, 1), $value));
		else $return = false;

		return ($pattern[0] == '!') ? !$return : $return;
	}

	public static function go($base = '') {
		static::$url    = $_SERVER['REQUEST_URI'];
		static::$url    = explode('?', static::$url)[0];
		static::$url    = str_ireplace($base, '', static::$url);
		static::$url    = static::url_elements(static::$url);
		static::$method = (isset($_GET['method'])) ? $_GET['method'] : $_SERVER['REQUEST_METHOD'];
		static::$method = strtoupper(static::$method);

		foreach(static::$routes as $route) {
			$passed = $refused = false;

			foreach($route['patterns'] as $pattern)
				if(static::execute($route['methods'], $pattern, $route['callbacks']))
					$passed = true;
				else $refused = true;

			if($passed) static::$passed++;
			if(!$passed and $refused) static::$refused++;
		}
	}

	private static function execute($methods, $pattern, $callbacks) {
		$arguments = [];
		$pattern   = static::url_elements($pattern);

		if(in_array(static::$method, $methods) and count($pattern) == count(static::$url)) {
			for($i = 0; $i < count($pattern); $i++)
				if(static::is_parsable(static::$url[$i], $pattern[$i]))
					$arguments[] = static::$url[$i];
				else if($pattern[$i] != static::$url[$i])
					return false;

			foreach($callbacks as $callback)
				call_user_func_array($callback, $arguments);

			return true;
		}

		return false;
	}

	public static function summary($callback) {
		call_user_func_array($callback, [
			'passed'  => static::$passed,
			'refused' => static::$refused
		]);
	}
}