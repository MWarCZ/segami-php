<?php

interface ImageInterface {

  function get();

  function read($srcFile);

  function write($destFile);

  function strip();

  function setFormat($format);

  // function resizeFilter($_resizeFilter);

  function resizeFill($width, $height);

  function resizeContain($width, $height);

  function resizeCover($width, $height);

  function cropImage($width, $height, $s_x, $s_y);

  function compression($quality);

}
