<?php
//* Project: segami-php
//* File: src/Exception/CorePluginInvalidFormatException.php
namespace MWarCZ\Segami\Exception;

// CorePluginInvalidFormatException
class CorePluginInvalidFormatException extends PluginInvalidFormatException {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is invalid format query for CorePlugin.';
    return $errorMsg;
  }
}
