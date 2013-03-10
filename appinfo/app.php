<?php

/**
* Rondin - Logging because you can!
*
* @author Brice Maron

* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
* License as published by the Free Software Foundation; either 
* version 3 of the License, or any later version.
* 
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU AFFERO GENERAL PUBLIC LICENSE for more details.
*  
* You should have received a copy of the GNU Affero General Public 
* License along with this library.  If not, see <http://www.gnu.org/licenses/>.
* 
*/

class RondinLoader {
  static public function autoload($name) {
    if(strpos($name, 'Monolog\\') === 0) {
      $name = str_replace('\\','/',$name);
      require_once OC_App::getAppPath('rondin').'/3rdparty/vendor/monolog/monolog/src/'.$name.'.php';
    }
    if(strpos($name, 'Psr\\') === 0) {
      $name = str_replace('\\','/',$name);
      require_once OC_App::getAppPath('rondin').'/3rdparty/vendor/psr/log/'.$name.'.php';
    }
  }
}

spl_autoload_register(__NAMESPACE__ .'\RondinLoader::autoload'); // Depuis PHP 5.3.0


function implode_r($glue,$arr){
        $ret_str = "";
        foreach($arr as $a){
                $ret_str .= (is_array($a)) ? implode_r($glue,$a) : "," . $a;
        }
        return $ret_str;
}

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class Rondin extends \OC_Log {
  static $logger = null;

  function __construct() {
    parent::$class = $this;
    parent::$enabled = true;

    //self::$logger = new Logger('name');
    //self::$logger->pushHandler(new StreamHandler('/tmp/your.log', Logger::DEBUG));
  }


  function configure() {
    $config = array('handlers' => array(
        'Stream' => array(
          '/tmp/your.log',
          'formatters' => array(
            'Line' => array("%datetime% OUUPS %channel% %level_name%: %message% -- %extra%\n")
          ),
        ),
      ),
      'processors' => array('MemoryUsage', 'Web'),
    );
    self::$logger = new Logger('name');
    $this->__push(self::$logger, $config['handlers']);
    $this->__push(self::$logger, $config['processors'], 'Processor');
  }

  private function __push($object, $list, $type = 'Handler') {
    if (empty($list)) {
      if ('Handler' == $type) {
        $list = array('Stream' => array('/tpm/monolog.log'));
      }
    }

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
    /*} else if (isset($params['search'])) {
      if (strpos($params['search'], '.php') === false) {
        $params['search'] .= DS . $class . '.php';
      }
      require_once $params['search'];
      unset($params['search']);
*/
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
    self::$logger->$level('App:'. $app .' '. $message);
  }

}

$log = new Rondin();
$log->configure();

