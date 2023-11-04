<?php
namespace MWarCZ\Segami\Exception;

// MissingImageLoggerException
// Neexistující logger posledního použití obrázku
class MissingImageLoggerException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> image logger is missing';
    return $errorMsg;
  }
}
