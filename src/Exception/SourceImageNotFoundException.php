<?php
namespace MWarCZ\Segami\Exception;

// SourceImageNotFoundException
// Zdrojový obrázek nebyl nalezen
class SourceImageNotFoundException extends \Exception {
  private $imageName;

  public function __construct($imageName, $message = "", $code = 0, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
    $this->imageName = $imageName;
  }

  public function getImageName() {
    return $this->imageName;
  }

  public function errorMessage() {
    $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
      . ': <b>' . $this->getMessage() . '</b> source image not found: ' . $this->getImageName();
    return $errorMsg;
  }
}
