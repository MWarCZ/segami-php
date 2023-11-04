<?php
namespace MWarCZ\Segami\Exception;

// InvalidFormatException
class InvalidFormatException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is invalid format';
    return $errorMsg;
  }
}
