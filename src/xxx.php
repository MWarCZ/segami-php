<?php
//* Project: segami-php
//* File: src/xxx.php
echo '<pre>' . print_r(\Imagick::queryformats(), true) . '</pre>';
exit;
?>
<?php

// // class LimiterLax {
// //   public function check($o_width, $o_height, $o_format) {
// //     return true;
// //   }
// // }

// // omezení jednotlivých typů - Kombinace parametrů v každé kategorii musí existovat
// $xxx = new LimiterLax([
//   'basic' => [new LimiterBasic(/*...*/), /*...*/],
//   'crop' => [new LimiterCrop( /*...*/), /*...*/],
//   'resize' => [new LimiterResize( /*...*/), /*...*/],
//   'quality' => [new LimiterQuality( /*...*/), /*...*/],
// ]);

// // Kompletní omezení - Musí existovat přesná kombinace
// $xxx = new LimiterStrict([
//   [
//     'basic' => new LimiterBasic(/*...*/),,
//     'crop' => new LimiterCrop( /*...*/),
//     'resize' => new LimiterResize( /*...*/),
//     'quality' => new LimiterQuality( /*...*/),
//   ],
//   /*...*/
// ]);

// $xxx->check([
//   'basic'=>basicProps,
//   /*...*/
// ]);


// new Segami([
//   'plugins'=>[
//     'basic'=>ImagePropsBasic.class,
//   ],
//   'limiter'=>new LimiterLax([
//     'basic'=>[new LimiterBasic(/*...*/), /*...*/],
//   ]),
//   'path_to_original_images'=>__DIR__,
//   'path_to_generated_images'=>__DIR__,
//   'image_factory'=>'', // ...
//   'image_logger'=>'', // ...
// ]);

// //! /////////////////////////////////////////////////////////////////////////

// new Segami([
//   'plugin' => [
//     'basic' => new BasicPlugin(),
//     'crop' => new CropPlugin(),
//     'resize' => new ResizePlugin(),
//   ],
//   'limiter'=>new LaxImageLimiter([
//     'basic'=>[ new BasicPropsLimiter('png', 'png'), ],
//     'crop'=>[ new CropPropsLimiter(100, 100), ],
//     'resize'=>[ new ResizePropsLimiter(100, 200), ],
//   ]),
// ]);

// //! /////////////////////////////////////////////////////////////////////////

// interface Props {
//   public function toQuery(): string;
// }

// interface PropsFactory {
//   /**
//    * @return string
//    */
//   public static function getSymbol(): string;

//   /**
//    * @param string $query
//    */
//   public static function parseQuery($query): self;

//   /**
//    * @param string $query
//    */
//   public static function validQuery($query): bool;

//   public static function validRegex(): string;

//   /**
//    * @param self $image_props
//    */
//   public static function createQuery($image_props): string;
// }

// interface Plugin {
//   /** @return string */
//   public function getSymbol();
//   /** @return ImageProps */
//   public function getPropsFactory();

// }
// class CropPlugin implements Plugin {
//   public function getSymbol() { return 'crop'; }
//   public function getPropsFactory() { return ImagePropsCrop.class; }
// }

// //! /////////////////////////////////////////////////////////////////////////

// // ImageLimiter->check
// throw new \Exception('Nenalezeno platné pravidlo v omezovači');
// throw new \InvalidArgumentException('$required_image must be string');
// // parseImageQuery($full_query)
// throw new \Exception('Chybí CorePlugin');
// throw new \Exception('Chyba validace CorePlugin');
// throw new \Exception('Nebyl nalezen správný Plugin');
// // createAndReturnImage($plugin_manager, $b_cache_new_image = true)
// throw new SourceImageNotFoundException($plugin_manager->core_props->getName());

// throw new MissingImageLoggerException('Není nastaveno rozpoznávání souborů pro smazání.');

// InvalidArgumentException
// SourceImageNotFoundException

// LimiterException
// MissingImageLoggerException
