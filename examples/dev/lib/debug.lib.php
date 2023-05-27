<?php


function s_debug($obj) {
  return '<pre>' . print_r($obj, true) . '</pre>';
}
function p_debug($obj) {
  echo s_debug($obj);
}
