<?php
class RondinLoader {
	static public function autoload($name) {
		if(strpos($name, 'Monolog\\') === 0) {
			$name = str_replace('\\','/',$name);
			$path = OC_App::getAppPath('rondin').'/3rdparty/vendor/monolog/monolog/src/'.$name.'.php';
		} elseif(strpos($name, 'Psr\\') === 0) {
			$name = str_replace('\\','/',$name);
			$path = OC_App::getAppPath('rondin').'/3rdparty/vendor/psr/log/'.$name.'.php';
		} else {
			return ;
		}
		if(file_exists($path)) {
			require_once $path;
		}
	}
}