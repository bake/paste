<?php
class View extends Config {
	public static function add($view, $data = []) {
		if(!is_array($data))
			$data = ['data' => $data];

		foreach($data as $key => $var)
			${$key} = $var;

		include(Config::path('views').$view.'.php');
	}
}
