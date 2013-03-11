<?php

class RondinConfigLoader {
  
  public function getCompatConfig() {
    $defaultLogFile = OC_Config::getValue("datadirectory", OC::$SERVERROOT.'/data').'/owncloud.log';
    $logFile = OC_Config::getValue("logfile", $defaultLogFile);
    $config = array('handlers' => array(
      'Stream' => array(
        $logFile,
        'formatters' => array(
          'OCLogFormatter' => array(),
        ),
      ),
    ),
    );
    return  $config;
  }
}