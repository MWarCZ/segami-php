<?php
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
class ImageName {
  public $separator;

  function __construct($a_separator = []) {
    $this->separator = new StdClass();
	 $this->separator->props = '@';
	 $this->separator->extension = '.';
	 $this->separator->compression = '=';
	 $this->separator->size2 = 'x';
    /*
	 $this->separator = (object)array_merge(
      [
        'props'=>'@',
        'extension'=>'.',
        'compression'=>'=',
        'size2'=>'x',
      ],
      $a_separator
    );
	 */
  }

  /** Pomocná funkce upravující vstupní znak pro použití v regex */
  function escRegex($text, $b_double_quote = false) {
    if(in_array($text, [
      '.', ',', '|', '?', '!', '&', '%', '-',
      '"', "'",
      '(', ')', '[', ']', '{', '}',
    ])) {
      return '\\'.$text;
    }
    return $text;
  }

  /**
   * Kontrola formátu koncovky s požadovanými vlastnostmi obrázku.
   * @param String Koncovka s požadovanými vlstmostmi obrázku
   * @return Bool Odpovídá koncovka požadovanému formátu? Ano = true, Ne = false
   */
  function checkRequestProps($props) {
    $r_number = '[0-9][0-9]*';
    $r_fn = '(c|r)'; // R = Resize; C = Crop
    $r_size_v1 = $r_number;
    $r_size_v2 = $r_number.$this->escRegex($this->separator->size2).$r_number;
    $r_extension = $this->escRegex($this->separator->extension).'[a-zA-Z0-9_-]+';
    $r_compression = $this->escRegex($this->separator->compression).$r_number;

    // /(([1-9][0-9]*)|([1-9][0-9]*x[1-9][0-9]*))?(=[1-9][0-9]*)?(\.[a-z]+)/i
    $r_full = "($r_fn?(($r_size_v1)|($r_size_v2)))?($r_compression)?($r_extension)";

    $is_ok = preg_match('/^'.$r_full.'$/i', $props);

    // p_debug([$is_ok, $props]);
    return $is_ok;
  }

  /**
   * Získání požadovaných vlastností obrázku z názvu souboru.
   * @param String Název souboru obsahující požadované vlastnosti
   * @return ImageProps Struktura obsahující požadované vlastnosti obrázku
   */
  function parseImageName($req_name) {
    // Získání názvu souboru
    $a_tmp = explode($this->separator->props, $req_name);
    $tmp = array_pop($a_tmp);
    $name = implode($this->separator->props, $a_tmp);

    // Kontrola přípony s vlastnostmi
    if(!$this->checkRequestProps($tmp)) { return false; }

    // Získání typu (přípona souboru)
    list($tmp, $extension) = explode($this->separator->extension, $tmp);

    // Získání komprese
    list($tmp, $compression) = explode($this->separator->compression, $tmp.$this->separator->compression.'100'); // '=100' přípona s výchozí hodnotou pro kompresi

    // Získání funkce pro zpracování rozměrů
    $fn = '';
    if(strlen($tmp)) {
      $fn = strtolower($tmp[0]);
      $tmp = substr($tmp, 1);
    }

    // Získání rozměrů
    list($width, $height) = explode($this->separator->size2, $tmp.$this->separator->size2.$tmp); // 'x'.$tmp přípona s výchozí hodnotou pro výšku (čtverec)

    return new ImageProps($name, strtolower($extension), (int)$compression, (int)$width, (int)$height, $fn);
  }

  /**
   * Vytvoření názvu souboru ze zadaných vlastností obrázku
   * @param ImageProps Struktura obsahující vlastnosti obrázku
   * @return String Název souboru se zadanými vlastnostmi
   */
  function createName($props) {
    return ''
      .$props->name.$this->separator->props.(
        $props->width == 0
      ? ''
      : $props->fn.(
        $props->width == $props->height
        ? $props->width
        : $props->width.$this->separator->size2.$props->height
      )
      ).(
        $props->compression < 100
        ? $this->separator->compression.$props->compression
        : ''
      ).$this->separator->extension.$props->extension
    ;
  }

}
