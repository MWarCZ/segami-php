<!--
//* Project: segami-php
//* File: README.md
-->
# Segami

Segami is simple PHP library that allows edit image dimensions, convert image format, cache generated images, record last access to generated image in cache, remove images from cache that have not been used for long time, all based on image suffix in name.

[![PHP version][php-badge]][php]
[![Packagist version][packagist-badge]][packagist]
[![MIT License][license-badge]](LICENSE)

[php-badge]: https://img.shields.io/packagist/dependency-v/mwarcz/segami/php?logo=php&logoColor=ffffff&label=php&color=8892BF
[packagist-badge]: https://img.shields.io/packagist/v/mwarcz/segami?logo=packagist&logoColor=ffffff
[license-badge]: https://img.shields.io/github/license/MWarCZ/segami-php

[php]: https://www.php.net/supported-versions.php
[packagist]: https://packagist.org/packages/mwarcz/segami

## Key features

- **Convert image format**
  - Basic function of library is to convert image into various raster formats (PNG, GIF, JPEG, WebP, etc.).
  - Example: `sample.png@.jpg`, `sample.png@.webp`
- **Create image crop**
  - Optional function allows you to crop image in required dimensions.
  - Example: `sample.png@c200x100.png`, `sample.png@c300.jpg`
- **Resize image**
  - Optional function that allows you to change size of image in the required dimensions and fill type (fill, contain, cover).
  - Example: `sample.png@r200x100_cover.png`, `sample.png@r300.jpg`
- **Set image quality/compression**
  - Optional feature to set image quality/compression, which affects resulting file size and compression for selected format.
  - Example: `sample.png@q80.jpg`, `sample.png@q50.webp`
- **Store generated images (cache)**
  - Library allows storing generated images in cache directory for quick retrieval of generated image, as name of image precisely defines its properties.
  - Image filenames are normalized in background to avoid unnecessary duplication.
    - ex. 1: `sample.png@c200.png` = `sample.png@c200x200.png`
    - ex. 2: `sample.png@r100x100_cover.png` = `sample.png@r100_r.png`
- **Automatic removal of long-term unused images from cache**
  - Library provides functions for removing previously stored images to help clear disk space of images that have not been used for long time.
- **Limiters limiting names of required images**
  - Optionally, it is possible to restrict image names that modify original image.
  - It is recommended to limit image names when storing to cache is enabled, so that potential attacker has difficult time attacking you.

## Requirements

- PHP 8.1+
  - Mandatory optional:
    - [ext-gd](https://www.php.net/manual/en/book.image)
    - [ext-imagick](https://www.php.net/manual/en/book.imagick.php) s instalovaným [ImageMagick](https://imagemagick.org/)

## Installation

Segami library is available on [Packagist](https://packagist.org/packages/mwarcz/segami) and installing via [Composer](https://getcomposer.org/) is recommended way to install it.

Just use the command in terminal:

```bash
composer require mwarcz/segami
```

Or manually add to `composer.json` file:

```json
{
    "require": {
        "php": "^8.1",
        "mwarcz/segami": "dev-master"
    }
}
```

> **Note:** Replace `dev-master` with [specific version constraint](https://getcomposer.org/doc/articles/versions.md#writing-version-constraints). See [Packagist](https://packagist.org/packages/mwarcz/segami) for available versions.

## Usage

Short example of possible basic use of library:

```php
$segami = new Segami([
  // Selected path to dir with original images
  'path_to_original_images' => __DIR__ . '/original',
  // Selected path to dir with generated images
  'path_to_generated_images' => __DIR__ . '/generated',
  // Selected plugins for generating images
  'plugin' => [
    // CorePlugin is required minimum - enable core name parsing and image format conversion
    'core' => new CorePlugin(),
    // Optional ResizePlugin - enable/add possibility resize image
    'resize' => new ResizePlugin(),
    // Optional QualityPlugin - enable/add possibility quality image
    'quality' => new QualityPlugin(),
  ],
  // Selected limiter with rules for generated images
  'limiter' => new FreeImageLimiter(),
  // Selected image engine
  'image_factory' => new ImageImagickFactory(),
  // Selected logger for logging access to images
  'image_logger' => new ImageLoggerNone(),
]);

try {
  $segami->smartReturnImage($_GET['image'], isset($_GET['cache']));
} catch (\Throwable $e) {
  http_response_code(404);
}
```

Repository contains set of samples of various use case:

- [Example of basic use](examples/basic/)
- [Example of use with LaxImageLimiter](examples/lax/)
- [Test example used in development](examples/dev/)
- TODO

More detailed information about functions and use case of Segami library can be found in *upcoming* [documentation](doc).

- [Creating name for generated images](doc/ImageName.md)
- [Preparation of limiter for limiting generated images](doc/Limiter.md)
- TODO

## License

Segami is licensed under [MIT license](LICENSE).

------------------------------

## TODO roadmap

See [Czech version](README.cs.md).
