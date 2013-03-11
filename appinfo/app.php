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

OC::$CLASSPATH['OCLogFormatter'] = 'rondin/lib/OCLogFormatter.php';
OC::$CLASSPATH['Rondin'] = 'rondin/lib/Rondin.php';
OC::$CLASSPATH['RondinLoader'] = 'rondin/lib/RondinLoader.php';

//AutoLoader for Monolog classes
spl_autoload_register(__NAMESPACE__ .'\RondinLoader::autoload'); // Depuis PHP 5.3.0

//Launch Log Stealer
$log = new Rondin();


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
$log->configure($config);

