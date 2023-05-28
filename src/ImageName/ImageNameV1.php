<?php
namespace MWarCZ\Segami\ImageName;

use MWarCZ\Segami\ImageProps;

///////////////////////////////////////////////
// * Aktuální:
// image@r200x100=80.png = Resize
// image@c200x100=80.png = Crop
// * Plán:
// image@<props>.<format>
// props:
// - R300x200 - Resize(width=300, height=200)
//   - R200x300
//   - R200
// - C200x100 - Crop(width=200, height=100)
//   - C100x200
//   - C100
// - =75 - Compress(75)
///////////////////////////////////////////////
class ImageNameV1 implements ImageName {

  public $props_separator = '@';
  public $extension_separator = '.';
  public $quality_separator = '=';
  public $size2_separator = 'x';

  function __construct() {
    // TODO nastavení separátorů
  }

  /**
   * Získání požadovaných vlastností obrázku z názvu souboru.
   * @param string Název souboru obsahující požadované vlastnosti
   * @return ImageProps|false Struktura obsahující požadované vlastnosti obrázku
   */
  public function parseName($req_name) {
    // Získání názvu souboru
    $a_tmp = explode($this->props_separator, $req_name);
    $tmp = array_pop($a_tmp);
    $name = implode($this->props_separator, $a_tmp);

    // Kontrola přípony s vlastnostmi
    if (!$this->checkRequestProps($tmp)) {
      return false;
    }

    // Získání typu (přípona souboru)
    list($tmp, $extension) = explode($this->extension_separator, $tmp);

    // Získání komprese
    list($tmp, $quality) = explode($this->quality_separator, $tmp . $this->quality_separator . '100'); // '=100' přípona s výchozí hodnotou pro kvalitu

    // Získání funkce pro zpracování rozměrů
    $fn = '';
    if (strlen($tmp)) {
      $fn = strtolower($tmp[0]);
      $tmp = substr($tmp, 1);
    }

    // Získání rozměrů
    list($width, $height) = explode($this->size2_separator, $tmp . $this->size2_separator . $tmp); // 'x'.$tmp přípona s výchozí hodnotou pro výšku (čtverec)

    return new ImageProps($name, strtolower($extension), (int) $quality, (int) $width, (int) $height, $fn);
  }

  /**
   * Vytvoření názvu souboru ze zadaných vlastností obrázku
   * @param ImageProps Struktura obsahující vlastnosti obrázku
   * @return string Název souboru se zadanými vlastnostmi
   */
  public function createName($props) {
    return ''
      . $props->name . $this->props_separator . (
        $props->width == 0
        ? ''
        : $props->fn . (
          $props->width == $props->height
          ? $props->width
          : $props->width . $this->size2_separator . $props->height
        )
      ) . (
        $props->quality < 100
        ? $this->quality_separator . $props->quality
        : ''
      ) . $this->extension_separator . $props->extension
    ;
  }

  /**
   * @return string Vrátí znak, který odděluje název originálního obrázku od vlastností.
   */
  public function getPropsSeparator() {
    return $this->props_separator;
  }

  /** Pomocná funkce upravující vstupní znak pro použití v regex */
  private function escRegex($text, $b_double_quote = false) {
    if (in_array(
      $text,
      ['.', ',', '|', '?', '!', '&', '%', '-', '"', "'", '(', ')', '[', ']', '{', '}']
    )) {
      return '\\' . $text;
    }
    return $text;
  }

  /**
   * Kontrola formátu koncovky s požadovanými vlastnostmi obrázku.
   * @param string Koncovka s požadovanými vlastnostmi obrázku
   * @return bool Odpovídá koncovka požadovanému formátu? Ano = true, Ne = false
   */
  private function checkRequestProps($props) {
    $r_number = '[0-9][0-9]*';
    $r_fn = '(c|r)'; // R = Resize; C = Crop
    $r_size_v1 = $r_number;
    $r_size_v2 = $r_number . $this->escRegex($this->size2_separator) . $r_number;
    $r_extension = $this->escRegex($this->extension_separator) . '[a-zA-Z0-9_-]+';
    $r_quality = $this->escRegex($this->quality_separator) . $r_number;

    // /(([1-9][0-9]*)|([1-9][0-9]*x[1-9][0-9]*))?(=[1-9][0-9]*)?(\.[a-z]+)/i
    $r_full = "($r_fn(($r_size_v1)|($r_size_v2)))?($r_quality)?($r_extension)";

    $is_ok = preg_match('/^' . $r_full . '$/i', $props);

    // p_debug([$is_ok, $props]);
    return $is_ok;
  }

}
