<?php

class RondinConfigLoader {

	/**
	* Fetch the config necessary to mimic OC logging
	* @return array of param config
	*/
	public function getCompatConfig() {
		$defaultLogFile = OC_Config::getValue("datadirectory", OC::$SERVERROOT.'/data').'/owncloud.log';
		$logFile = OC_Config::getValue("logfile", $defaultLogFile);
		$config = array(
			'name'=> 'Stream',
			'params' => array(
				$logFile,
				'formatters' => array(
					'OCLogFormatter' => array(),
				)
			)
		);
		return  $config;
	}

	public function loadStoredConfig() {
		$filename = OC_Config::getValue("datadirectory", OC::$SERVERROOT.'/data').'/logs.conf';
		$content = file_get_contents( $filename );
		$conf = json_decode($content, true);
		return $conf;
	}

	public function storeConfig($config) {
		$content = json_encode($config);
		$filename = OC_Config::getValue("datadirectory", OC::$SERVERROOT.'/data').'/logs.conf';
		// Write the file
		$result=@file_put_contents( $filename, $content );
	}
}