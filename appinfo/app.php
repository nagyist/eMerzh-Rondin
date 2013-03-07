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

    self::$logger = new Logger('name');
    self::$logger->pushHandler(new StreamHandler('/tmp/your.log', Logger::DEBUG));
  }

  static function write($app, $message, $lvl) {
    if(OC_Log::DEBUG == $lvl) $level = Logger::DEBUG;
    elseif(OC_Log::INFO == $lvl) $level = Logger::INFO;
    elseif(OC_Log::WARN == $lvl) $level = Logger::WARNING;
    elseif(OC_Log::ERROR == $lvl) $level = Logger::ERROR;
    elseif(OC_Log::FATAL == $lvl) $level = Logger::CRITICAL;
    $back_traces = debug_backtrace(0);
    $back_traces = array_slice($back_traces, 3); //Remove 3 first lines corresponding to the logger
    self::$logger->addRecord($level, 'App:'. $app .' '. $message, $back_traces);
  }
}

new Rondin();
