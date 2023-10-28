<?php
namespace MWarCZ\Segami\Exception;

// UnknownInstanceOfModifierException
// Neznámá instance modifikátoru
class UnknownInstanceOfModifierException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is an unknown instance of modifier';
    return $errorMsg;
  }
}
