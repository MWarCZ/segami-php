<!--
//* Project: segami-php
//* File: doc/Limiter.md
-->
# Omezovač / Limiter

Je doporučeno používat omezovače pokud plánujete Segami využívat včetně funkce pro ukládání vygenerovaných obrázků (cache).

## Volný omezovač obrázku / FreeImageLimiter

Omezovač, který povolí jakoukoliv dostupnou modifikaci obrázku.

Tento omezovač je doporučeno používat pouze při **nevyužívání** funkce pro ukládání vygenerovaných obrázků (cache) nebo ve fázi vývoje (`$segami->smartReturnImage($req_img, false /* disabled cache for generated images */)` vs `$segami->smartReturnImage($req_img, true /* enabled cache for generated images */)`).

```php
[
  // ...
  'limiter' => new FreeImageLimiter(),
  // ...
]
```

## Striktní omezovač obrázku / StrictImageLimiter

Striktní omezovač obrázku hledá alespoň jednu platnou kombinaci omezovačů vlastností pro výstupní obrázek.

Tento omezovač je doporučeno používat v produkční verzi, která využívá funkce pro ukládání vygenerovaných obrázků (cache - `$segami->smartReturnImage($req_img, true /* enabled cache for generated images */)`).

```php
[
  // ...
  'limiter' => new StrictImageLimiter([
    // Allowed: 
    // segami.png@.jpg
    // segami.png@.webp
    [
      'core' => new CorePropsLimiter('png', ['jpg', 'webp']),
    ],
    // Allowed:
    // segami.png@q75.jpg
    // segami.png@q75.webp
    // segami.png@q50.jpg
    // segami.png@q50.webp
    [
      'core' => new CorePropsLimiter('png', ['jpg', 'webp']),
      'quality' => new QualityPropsLimiter([75, 50])
    ],
    // Allowed:
    // segami.png@r400x300_r.q50.webp
    // segami.png@r400x300_n.q50.webp
    // segami.jpg@r400x300_r.q50.webp
    // segami.jpg@r400x300_n.q50.webp
    [
      'core' => new CorePropsLimiter(['png', 'jpg'], 'webp'),
      'quality' => new QualityPropsLimiter(50),
      'resize' => new ResizePropsLimiter(400, 300, [ResizeProps::TYPE_COVER, ResizeProps::TYPE_CONTAIN]),
    ],
    // Allowed:
    // segami.png@r350x250_r.q50.webp
    // segami.png@r450x250_r.q50.webp
    // segami.png@r350x350_r.q50.webp
    // segami.png@r450x350_r.q50.webp
    [
      'core' => new CorePropsLimiter('png', 'webp'),
      'quality' => new QualityPropsLimiter(50),
      'resize' => new ResizePropsLimiter([350, 450], [250, 350], ResizeProps::TYPE_COVER),
    ],
    // Not allowed: Other
  ]),
  // ...
]
```

## Laxní omezovač obrázku / LaxImageLimiter

Laxní omezovač obrázků kontroluje zda požadovaný obrázek může obsahovat dné vlastnosti, ale nehlídá jejich různé kombinování.

```php
[
  // ...
  // Allowed:
  // segami.png@.jpg
  // segami.png@.webp
  // segami.png@q50.jpg
  // segami.png@q50.webp
  // segami.png@q75.jpg
  // segami.png@q75.webp
  // segami.png@r400x300_r.jpg
  // segami.png@r400x300_r.webp
  // segami.png@r400x300_r.q50.jpg
  // segami.png@r400x300_r.q50.webp
  // segami.png@r400x300_r.q75.jpg
  // segami.png@r400x300_r.q75.webp
  'limiter' => new LaxImageLimiter([
    'core' => [
      // core property must be converting from png to jpg
      new CorePropsLimiter('png', 'jpg'),
      // or
      // core property must be converting from png to webp
      new CorePropsLimiter('png', 'webp'),
    ],
    'quality' => [
      // quality property is optional
      new NullablePropsLimiter(),
      // or
      // quality property must be q75
      new QualityPropsLimiter(75),
      // or
      // quality property must be q50
      new QualityPropsLimiter(50),
    ],
    'resize' => [
      // resize property is optional
      new NullablePropsLimiter(),
      // or
      // resize property must be r400x300_r
      new ResizePropsLimiter(400, 300, ResizeProps::TYPE_COVER),
    ],
  ]),
  // ...
]
```

```php
[
  // ...
  // Allowed:
  // segami.png@.jpg
  // segami.png@.webp
  // segami.png@q50.jpg
  // segami.png@q50.webp
  // segami.png@q75.jpg
  // segami.png@q75.webp
  // segami.png@r400x300_r.jpg
  // segami.png@r400x300_r.webp
  // segami.png@r400x300_r.q50.jpg
  // segami.png@r400x300_r.q50.webp
  // segami.png@r400x300_r.q75.jpg
  // segami.png@r400x300_r.q75.webp
  'limiter' => new LaxImageLimiter([
    'core' => [
      // core property must be converting from png to jpg or webp
      new CorePropsLimiter('png', ['jpg', 'webp']),
    ],
    'quality' => [
      // quality property is optional
      new NullablePropsLimiter(),
      // or
      // quality property must be q75 or q50
      new QualityPropsLimiter([75, 50]),
    ],
    'resize' => [
      // resize property is optional
      new NullablePropsLimiter(),
      // or
      // resize property must be r400x300_r
      new ResizePropsLimiter(400, 300, ResizeProps::TYPE_COVER),
    ],
  ]),
  // ...
]
```
