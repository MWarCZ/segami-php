<?php

interface ImageLogger {

  /**
   * @param String $file_path Absolutní cesta k souboru
   */
  public function access($file_path);

  /**
   * @param String $dir_path Cesta ke složce se soubory
   * @param Int|String $mtime Čas dělící staré soubory od nových
   * @return String[] Seznam absolutní cesty k dlouhodobě nepoužitým souborům
   */
  public function &getUnusedFiles($dir_path, $mtime);

  /**
   * @param String $dir_path Cesta ke složce se soubory
   * @param String $img_name Základní název obrázku
   * @param String $img_separator_props Oddělovač názvu souboru a přípony s vlastnostmi
   *                                    vygenerovaného obrázku
   * @return String[] Seznam absolutní cesty k souborům
   */
  public function &getFiles($dir_path, $img_name, $img_separator_props);

}
