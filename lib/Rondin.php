<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Rondin extends \OC_Log {
  static $logger = null;

  function __construct() {
    parent::$class = $this;
    parent::$enabled = true;
  }


  function configure($config) {
    self::$logger = new Logger('owncloud');
    foreach($config as $handler) {
      $this->initLogger(self::$logger, $handler['name'], 'Handler', $handler['params']);
    }
  }

  private function __push($object, $list, $type = 'Handler') {
    foreach ($list as $name => $params) {
      if (is_numeric($name)) {
        $name = $params;
        $params = array();
      }
      $this->initLogger($object, $name, $type, $params);
    }
  }

  protected function initLogger($object , $name, $type, $params) {
    $extras = array('formatters', 'processors');

    $class = $name;
    if (strpos($class, $type) === false) {
      $class = "\Monolog\\$type\\$name$type";
    } elseif (isset($params['search'])) {
      /*if (strpos($params['search'], '.php') === false) {
        $params['search'] .= DS . $class . '.php';
      }*/
      require_once $params['search'];
      unset($params['search']);
    }

    if ('Handler' === $type) {
      foreach ($extras as $k) {
        if (isset($params[$k])) {
          $$k = $params[$k];
          unset($params[$k]);
        }
      }
    }

    $method = "push$type";
    if ('Formatter' === $type) {
      $method = 'setFormatter';
    }
    $tmp_class = new ReflectionClass($class);
    $_class = $tmp_class->newInstanceArgs($params);
    $object->$method($_class);

    foreach ($extras as $k) {
      if (!empty($$k)) {
        $this->__push($_class, (array) $$k, ucfirst(substr($k, 0, strlen($k) - 1)));
      }
    }
  }

  static function write($app, $message, $lvl) {
    if(OC_Log::DEBUG == $lvl) $level = 'debug';
    elseif(OC_Log::INFO == $lvl) $level = 'info';
    elseif(OC_Log::WARN == $lvl) $level = 'warning';
    elseif(OC_Log::ERROR == $lvl) $level = 'error';
    elseif(OC_Log::FATAL == $lvl) $level = 'critical';
    $back_traces = debug_backtrace(0);
    $back_traces = array_slice($back_traces, 3); //Remove 3 first lines corresponding to the logger
    self::$logger->$level($message, array('app' => $app, 'traceback' => $back_traces));
  }

}