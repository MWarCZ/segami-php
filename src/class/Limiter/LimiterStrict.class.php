<?php
namespace MWarCZ\Segami;

require_once(__DIR__ . '/Limiter.interface.php');

/**
 * Striktní omezovač - omezuje výstupní výšku, šířku, formát a vstupní formát obrázku.
 * Všechny zadané parametry musí být splněny.
 * @example [[input format, output format], [width, height]]
 */
class LimiterStrict implements Limiter {
  protected $i_format;
  protected $o_format;
  protected $o_width;
  protected $o_height;

  /**
   * @param true|int[]  $o_size
   * @param true|string $o_format
   * @param true|string $i_format
   */
  function __construct($o_size = true, $o_format = true, $i_format = true) {
    if ($o_size !== true && !(is_array($o_size) && is_numeric($o_size[0])))
      throw new \InvalidArgumentException('1. parametr $o_size musi být true|number[]');
    if ($o_size === true) {
      $this->o_width = $this->o_height = true;
    } else {
      $this->o_width = $o_size[0];
      $this->o_height = end($o_size);
    }

    if ($o_format !== true && !is_string($o_format))
      throw new \InvalidArgumentException('1. parametr $o_format musi být true|string');
    $this->o_format = $o_format;

    if ($i_format !== true && !is_string($i_format))
      throw new \InvalidArgumentException('1. parametr $i_format musi být true|string');
    $this->i_format = $i_format;
  }

  public function check($o_width, $o_height, $o_format) {
    return (true
      && ($this->o_width === true || $this->o_width == $o_width)
      && ($this->o_height === true || $this->o_height == $o_height)
      && ($this->o_format === true || $this->o_format == $o_format)
      // && ($this->i_format === true || $this->i_format == $i_format)
    );
  }
}
