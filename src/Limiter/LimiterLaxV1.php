<?php
namespace MWarCZ\Segami\Limiter;

/**
 * Laxní omezovač - omezuje výstupní výšku, šířku, formát a vstupní formát obrázku.
 * Musí být splněny podmínky existence [šířka, výška], existence výstupního formátu,
 * existence vstupního formátu.
 * @example [[input format, ...], [output format, ...], [size, ...]]
 */
class LimiterLaxV1 implements Limiter {
  protected $a_i_format;
  protected $a_o_format;
  protected $a_o_size;

  /**
   * @param true|int[]    $a_o_size
   * @param true|string[] $a_o_format
   * @param true|string[] $a_i_format
   */
  function __construct($a_o_size = true, $a_o_format = true, $a_i_format = true) {
    if ($a_o_size !== true && !is_array($a_o_size))
      throw new \InvalidArgumentException('1. parametr $a_o_size musí být true|array');
    $this->a_o_size = $a_o_size;

    if ($a_o_format !== true && !is_array($a_o_format))
      throw new \InvalidArgumentException('2. parametr $a_o_format musí být true|array');
    $this->a_o_format = $a_o_format;

    if ($a_i_format !== true && !is_array($a_i_format))
      throw new \InvalidArgumentException('3. parametr $a_i_format musí být true|array');
    $this->a_i_format = $a_i_format;
  }

  public function check($o_width, $o_height, $o_format) {
    return (true
      && (
        $this->a_o_size === true
        || in_array([$o_width, $o_height], $this->a_o_size)
        || ($o_width == $o_height && in_array([$o_width], $this->a_o_size))
      )
      && (
        $this->a_o_format === true
        || in_array($o_format, $this->a_o_format)
      )
      // && ($this->a_i_format === true || in_array($i_format, $this->a_i_format))
    );
  }
}
