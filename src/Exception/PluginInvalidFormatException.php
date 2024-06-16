<?php
//* Project: segami-php
//* File: src/Exception/PluginInvalidFormatException.php
namespace MWarCZ\Segami\Exception;

// PluginInvalidFormatException
class PluginInvalidFormatException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is invalid format query for CorePlugin.';
    return $errorMsg;
  }
}
