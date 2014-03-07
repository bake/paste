<?php
class Paste {
	public static function get($token) {
		$result = Config::$db->query('SELECT `date`, `token`, `hidden`, `parent`, `file` FROM `paste_dev` WHERE `token` = "'.$token.'"');
		$paste  = $result->fetch_assoc();

		if($paste != null)
			if(file_exists(Config::path('pastes').$paste['file'].'.txt'))
				return $paste;
			else return false;
		else return false;
	}

	public static function get_num($num) {
		$result = Config::$db->query('SELECT `date`, `token`, `parent`, `file` FROM `paste_dev` WHERE `hidden` = "false" ORDER BY `id` DESC LIMIT '.$num);
		$pastes = [];

		while($paste = $result->fetch_assoc())
			if(file_exists(Config::path('pastes').$paste['file'].'.txt'))
				$pastes[] = $paste;

		return $pastes;
	}

	public static function get_text($file) {
		return file_get_contents(Config::path('pastes').$file.'.txt');
	}

	public static function save($text, $parent = '', $hidden = false) {
		if(trim($text) == false)
			return false;

		$date   = ($date) ? date('c', $date) : date('c');
		$parent = (paste($parent)) ? $parent : '';
		$key    = static::generate_key();
		$file   = static::generate_key();
		$hidden = ($hidden) ? 'true' : 'false';

		do {
			$token = static::generate_key();
		} while(static::get($token));

		if($parent != '' and $parent_arr = static::get($parent))
			if(md5($text) == md5(static::get_text($parent_arr['file'])))
				return $parent_arr['token'];

		if(Config::$db->query('INSERT INTO `paste_dev` (`date`, `token`, `key`, `parent`, `hidden`, `file`) VALUES("'.$date.'", "'.$token.'", "'.$key.'", "'.$parent.'", "'.$hidden.'", "'.$file.'")'))
			if($put = file_put_contents(Config::path('pastes').$file.'.txt', $text))
				return $token;

		return false;
	}

	public static function delete($token, $key) {
		if(!$paste = static::get($token))
			return false;

		if($result = Config::$db->query('DELETE FROM `paste_dev` WHERE `token` = "'.$token.'" AND `key` = "'.$key.'";'))
			if(Config::$db->affected_rows == 1 and unlink(Config::path('pastes').$paste['file'].'.txt'))
				return true;

		return false;
	}

	public static function generate_key($length = 12) {
		return substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, $length);
	}

	public static function prettify_date($time) {
		$now     = time();
		$diff    = $now - $time;
		$periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'];
		$lengths = ['60', '60', '24', '7', '4.35', '12', '10'];

		for($j = 0; $diff >= $lengths[$j] and $j < count($lengths) - 1; $j++)
			$diff /= $lengths[$j];

		$diff = round($diff);

		if($diff != 1)
			$periods[$j] .= 's';

		return $diff.' '.$periods[$j].' ago';
	}
}
