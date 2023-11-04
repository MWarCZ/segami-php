<?php
namespace MWarCZ\Segami\Exception;

// UnknownModifierException
// Požadavek na vytvoření neznámého modifikátoru
class UnknownModifierException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is an unknown modifier';
    return $errorMsg;
  }
}
