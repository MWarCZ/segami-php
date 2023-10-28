<?php
namespace MWarCZ\Segami\Exception;

// LimiterException
class LimiterException extends \Exception {
  public function __construct($message = "", $code = 0, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> limiter has problem with input.';
    return $errorMsg;
  }
}
