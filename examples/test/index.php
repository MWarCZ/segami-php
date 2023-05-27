<?php
require_once(__DIR__ . '/../../test/Test.class.php');

Test::init();

require_once(__DIR__ . '/../../test/ImageName.test.php');
require_once(__DIR__ . '/../../test/Limiter.test.php');
require_once(__DIR__ . '/../../test/ImageLoggerNone.test.php');
require_once(__DIR__ . '/../../test/ImageLoggerFS/ImageLoggerFS.test.php');
require_once(__DIR__ . '/../../test/ImageLoggerPDO/ImageLoggerPDO.test.php');

Test::summary();


// Test::init();

// Test::test('Test v1', 2<1);
// Test::test('Test v2', function() { assert(2<1); });
// Test::test('Test v3', function() { assert(2>1); });

// Test::group('Skupina v1', function() {
//   Test::test('Test v4', function() { assert(2<1); });
//   Test::test('Test v5', function() { assert(2>1); });
// });
// Test::group('Skupina v2', function() {
//   Test::test('Test v6', function() { assert(2<1); });
//   Test::test('Test v7', function() { assert(2>1); });
// });
// Test::test('Test v8', function() { assert(2>1); });

// Test::summary();

// p_debug(SupportedFormat::MAP_EXTENSION);

// p_debug([
//   'a'=>in_array(5, [1, 2, 3]),
//   'b'=>in_array(5, [1, 2, 5]),
//   'c'=>in_array([5], [[1], [2], [3]]),
//   'd'=>in_array([5], [[1], [2], [5]]),
//   'e'=>in_array([5, 'a'], [[1, 'a'], [2, 'b'], [3, 'c']]),
//   'f'=>in_array([5, 'a'], [[5, 'a'], [2, 'b'], [3, 'c']]),
// ]);
