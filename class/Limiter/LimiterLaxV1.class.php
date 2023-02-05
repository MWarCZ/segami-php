<?php

require_once(__DIR__.'/Limiter.interface.php');

// [[input format, ...], [output format, ...], [size, ...]]
class LimiterLaxV1 implements LimiterInterface {
  public $a_i_format;
  public $a_o_format;
  public $a_o_size;

  function __construct($a_o_size = true, $a_o_format = true, $a_i_format = true) {
    if($a_o_size !== true && !is_array($a_o_size)) throw new Exception('$a_o_size musi být ...');
    $this->a_o_size = $a_o_size;

    if($a_o_format !== true && !is_array($a_o_format)) throw new Exception('$a_o_format musí být ...');
    $this->a_o_format = $a_o_format;

    if($a_i_format !== true && !is_array($a_i_format)) throw new Exception('$a_i_format musí být ...');
    $this->a_i_format = $a_i_format;
  }

  public function check($o_width, $o_height, $o_format) {
    return (true
      && ($this->a_o_size     === true || in_array([$width, $height], $this->a_o_size) || ($width == $height && in_array([$width], $this->a_o_size)))
      && ($this->a_o_format === true || in_array($o_format, $this->a_o_format))
      // && ($this->a_i_format === true || in_array($i_format, $this->a_i_format))
    );
  }
}
