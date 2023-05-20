<?php
namespace MWarCZ\Segami;

interface ImageName {

  /**
   * Získání požadovaných vlastností obrázku z názvu souboru.
   * @param String Název souboru obsahující požadované vlastnosti
   * @return ImageProps Struktura obsahující požadované vlastnosti obrázku
   */
  public function parseName($req_name);

  /**
   * Vytvoření názvu souboru ze zadaných vlastností obrázku
   * @param ImageProps Struktura obsahující vlastnosti obrázku
   * @return String Název souboru se zadanými vlastnostmi
   */
  public function createName($props);

  /**
   * @return String Vrátí znak, který odděluje název originálního obrázku od vlastností.
   */
  public function getPropsSeparator();

}
