<?php
//* Project: segami-php
//* File: src/Exception/PluginPluginNotFoundException.php
namespace MWarCZ\Segami\Exception;

// PluginPluginNotFoundException
class PluginPluginNotFoundException extends \Exception {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> CorePlugin is not found.';
    return $errorMsg;
  }
}
