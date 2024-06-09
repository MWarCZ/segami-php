<?php
//* Project: segami-php
//* File: src/Exception/CorePluginNotFoundException.php
namespace MWarCZ\Segami\Exception;

// CorePluginNotFoundException
class CorePluginNotFoundException extends PluginPluginNotFoundException {
  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> CorePlugin is not found.';
    return $errorMsg;
  }
}
