<?php
//* Project: segami-php
//* File: src/Image/Image.php
namespace MWarCZ\Segami\Image;

interface Image {

  function get();
  /**
   * @param string $srcFile
   * @return self
   */
  function read($srcFile);
  /**
   * @param string $destFile
   * @return self
   */
  function write($destFile);
  /**
   * @return self
   */
  function strip();
  /**
   * @param string $format
   * @return self
   */
  function setFormat($format);
  /**
   * @param int $width
   * @param int $height
   * @return self
   */
  function resizeFill($width, $height);
  /**
   * @param int $width
   * @param int $height
   * @return self
   */
  function resizeContain($width, $height);
  /**
   * @param int $width
   * @param int $height
   * @return self
   */
  function resizeCover($width, $height);
  /**
   * @param int $width
   * @param int $height
   * @param int|string $s_x
   * @param int|string $s_y
   * @return self
   */
  function cropImage($width, $height, $s_x = null, $s_y = null);
  /**
   * @param int $quality
   * @return self
   */
  function compression($quality);

}
