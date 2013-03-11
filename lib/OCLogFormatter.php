<?php
/**
* Log formatter to write Monolog logs as ownCloud 5.0 does it
*/
class OCLogFormatter implements Monolog\Formatter\FormatterInterface
{
  
  /**
  * Dummy contruct
  */
  public function __construct(){}

  /**
   * {@inheritdoc}
  */
  public function format(array $record) {
    if(isset($record['context']['app'])) $app = $record['context']['app'];
    else $app = 'core';

    return json_encode(array(
      'app' => $app,
      'message'=> $record['message'],
      'level' => $this->toOCLevel($record['level_name']),
      'time' => $record['datetime']->format('U'),
      ))."\n";
  }

  /**
  * Convert Monolog Level to Oc level
  */
  public function toOCLevel($monologLevel) {
    if('DEBUG' == $monologLevel) return OC_Log::DEBUG;
    elseif('INFO' == $monologLevel) return OC_Log::INFO;
    elseif('WARNING' == $monologLevel) return OC_Log::WARN;
    elseif('ERROR' == $monologLevel)  return OC_Log::ERROR;
    elseif('CRITICAL' == $monologLevel) return OC_Log::FATAL;
  }

  /**
   * {@inheritdoc}
  */
  public function formatBatch(array $records)
  {
    $lines = array();
    foreach($records as $record) {
      $line[] = $this->format($record);
    }
    return json_encode($lines);
  }

}