<?php
namespace MWarCZ\Segami;

interface Image {

  function get();
  /**
   * @return self
   */
  function read($srcFile);
  /**
   * @return self
   */
  function write($destFile);
  /**
   * @return self
   */
  function strip();
  /**
   * @return self
   */
  function setFormat($format);
  /**
   * @return self
   */
  function resizeFill($width, $height);
  /**
   * @return self
   */
  function resizeContain($width, $height);
  /**
   * @return self
   */
  function resizeCover($width, $height);
  /**
   * @return self
   */
  function cropImage($width, $height, $s_x, $s_y);
  /**
   * @return self
   */
  function compression($quality);

}
