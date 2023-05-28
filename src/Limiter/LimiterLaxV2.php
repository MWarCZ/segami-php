<?php
namespace MWarCZ\Segami\Limiter;

/**
 * Laxní omezovač - omezuje výstupní výšku, šířku, formát a vstupní formát obrázku.
 * Musí být splněny podmínky existence šířky, existence výšky, existence výstupního formátu,
 * existence vstupního formátu.
 * @example [[input format, ...], [output format, ...], [width, ...], [height, ...]]
 */
class LimiterLaxV2 implements Limiter {
  protected $a_i_format;
  protected $a_o_format;
  protected $a_o_width;
  protected $a_o_height;

  /**
   * @param true|int[]    $a_o_width
   * @param true|int[]    $a_o_height
   * @param true|string[] $a_o_format
   * @param true|string[] $a_i_format
   */
  function __construct($a_o_width = true, $a_o_height = true, $a_o_format = true, $a_i_format = true) {
    if ($a_o_width !== true && !is_array($a_o_width))
      throw new \InvalidArgumentException('1. parametr $a_o_width musí být true|int[]');
    $this->a_o_width = $a_o_width;

    if ($a_o_height !== true && !is_array($a_o_height))
      throw new \InvalidArgumentException('2. parametr $a_o_height musí být true|int[]');
    $this->a_o_height = $a_o_height;

    if ($a_o_format !== true && !is_array($a_o_format))
      throw new \InvalidArgumentException('3. parametr $a_o_format musí být true|string[]');
    $this->a_o_format = $a_o_format;

    if ($a_i_format !== true && !is_array($a_i_format))
      throw new \InvalidArgumentException('4. parametr $a_i_format musí být true|string[]');
    $this->a_i_format = $a_i_format;
  }

  public function check($o_width, $o_height, $o_format) {
    return (true
      && ($this->a_o_width === true || in_array($o_width, $this->a_o_width))
      && ($this->a_o_height === true || in_array($o_height, $this->a_o_height))
      && ($this->a_o_format === true || in_array($o_format, $this->a_o_format))
      // && ($this->a_i_format === true || in_array($i_format, $this->a_i_format))
    );
  }
}
