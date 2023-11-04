<?php
namespace MWarCZ\Segami\Exception;

// UnsupportedImageExtensionException
// Nepodporovaná koncovka obrázku
class UnsupportedImageExtensionException extends \Exception {
  private $extension;

  public function __construct($extension, $message = "", $code = 0, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
    $this->extension = $extension;
  }

  public function getExtension() {
    return $this->extension;
  }

  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> is an unsupported image extension: ' . $this->getExtension();
    return $errorMsg;
  }
}
