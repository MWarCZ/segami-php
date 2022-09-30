<?php


function s_debug($obj) { return '<pre>'.print_r($obj, true).'</pre>'; }
function p_debug($obj) { echo s_debug($obj); }

function location($url) { header("Location: $url"); exit; }
