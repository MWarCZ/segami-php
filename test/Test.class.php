<?php

class Test {
  public static function init() {
    // Active assert and make it quiet
    assert_options(ASSERT_ACTIVE, 1);
    assert_options(ASSERT_WARNING, 0);
    // Set up the callback
    assert_options(ASSERT_CALLBACK, 'Test::assert_handler');
    // CSS style
    echo '
    <style>
      body {
        background: hsl(0deg 0% 12%);
        color: white;
        font-family: monospace;
        font-size: 16px;
      }
      .test-ok, .test-ko {
        padding: 0.1rem 0.5rem;
        margin-bottom: 2px;
      }
      .test-ok {
        background: hsl(95deg 100% 50% / .3);
      }
      .test-ko {
        background: hsl(0deg 100% 50% / .3);
      }

      .group {
        padding-left: 1rem;
        background: hsl(0deg 0% 0%);
        overflow: hidden;
      }
      .group__title {
        padding: 2px 0;
        cursor: pointer;
        margin-left: -0.5rem;
      }
    </style>
    ';
    Test::$counter = 0;
    Test::$counter_ok = 0;
    Test::$counter_ko = 0;
  }
  public static $counter_ok = 0;
  public static $counter_ko = 0;
  public static $counter = 0;
  public static $a_tmp_error = [];
  public static function assert_handler($file, $line, $code, $desc = null) {
    Test::$a_tmp_error[] = $file.' : '.$line.' : '.$code;
  }
  public static function test($desc, $result) {
    Test::$a_tmp_error = [];
    Test::$counter += 1;
    $err = false;
    try {
      if(is_callable($result)) { $result(); } else { assert($result); }
      Test::$counter_ok += 1;
    }
    catch (AssertionError $e) {
      $err = true;
      Test::$counter_ko += 1;
    }
    $err_count = count(Test::$a_tmp_error);
    echo '<div class="'.($err ? 'test-ko' : 'test-ok').'">'.'<span>'.Test::$counter.') </span>'.(
      $err
      ? '<span>✕</span> '.$desc.'<pre>'.implode(Test::$a_tmp_error).'</pre>'
      : '<span>✓</span> '.$desc
    ).'</div>';
  }

  public static function group($desc, $result) {
    echo '<details class="group" open>';
    echo '<summary class="group__title">'.$desc.'</summary>';
    if(is_callable($result)) { $result(); }
    echo '</details>';
  }

  public static function summary() {
    echo '<div>'
      .'<div style="font-weight:bold;">Souhrn:</div>'
      .'<div><span>✓</span> '.Test::$counter_ok.'</div>'
      .'<div><span>✕</span> '.Test::$counter_ko.'</div>'
    .'</div>';
  }
}

