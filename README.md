
# Segami

Segami je jednoduchá PHP knihovna pro modifikaci obrázků. Umožňuje snadno převádět formáty, vytvářet výřezy, upravovat rozměry a měnit kvalitu obrázků na základě přípony v jejich názvu.

## Klíčové vlastnosti / Key properties

- **Převod formátu obrázku**
  - Základní funkcí knihovny je převod obrázku do různých rastrových formátů (PNG, GIF, JPEG, WebP, atd.).
  - Například: `sample.png@.jpg`, `sample.png@.webp`
- **Vytvoření výřezu z obrázku**
  - Volitelná funkce umožňuje vytváření výřezů z obrázku v požadovaných rozměrech.
  - Například: `sample.png@c200x100.png`, `sample.png@c300.jpg`
- **Změna rozměru obrázku**
  - Volitelná funkce umožňující změnu velikosti obrázku v požadovaných rozměrech a stylem vyplnění (fill, contain, cover).
  - Například: `sample.png@r200x100_cover.png`, `sample.png@r300.jpg`
- **Změna kvality/komprese obrázku**
  - Volitelná funkce pro nastavení kvality/komprese obrázku, což ovlivňuje velikost výsledného souboru a kompresi u zvoleného formátu.
  - Například: `sample.png@q80.jpg`, `sample.png@q50.webp`
- **Ukládání vygenerovaných obrázků (cache)**
  - Knihovna umožňuje ukládání vygenerovaných obrázků do cache pro rychlé znovu vrácení vygenerovaného obrázku, neboť název obrázku přesně definuje jeho vlastnosti.
  - Názvy souborů jsou na pozadí normovány, aby nedocházelo ke zbytečným duplicitám.
    - př. 1: `sample.png@c200.png` = `sample.png@c200x200.png`
    - př. 2: `sample.png@r100x100_cover.png` = `sample.png@r100_r.png`
- **Automatické odstraňování dlouhodobě nevyužitých obrázků z cache**
  - Knihovna poskytuje funkce pro odstraňování obrázků dříve uložených do cache, které pomáhají čistit disk od dlouhodobě nevyužívaných obrázků.
- **Omezovače limitující názvy požadovaných obrázků**
  - Volitelně je možné omezovat názvy obrázků, které modifikují originální obrázek.
  - Je doporučeno limitovat názvy obrázku při zapnuté funkci cache, aby případný útočník měl ztíženou práci s útokem na vás.

## Požadavky / Requirements

- PHP 8.1+
- imagick

## Instalace / Installation

Knihovna Segami je dostupná na [Packagist](https://packagist.org/packages/mwarcz/segami) a instalace přes [Composer](https://getcomposer.org/) je doporučeným způsobem k instalaci.

Stačí použít příkaz:

```bash
composer require mwarcz/segami
```

Nebo manuálně přidat do souboru `composer.json` řádek:

```json
"mwarcz/segami": "^1.1.2"
```

## Použití / Usage

Krátká ukázka možného základního použití knihovny.

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

Repositář obsahuje sadu konkrétních ukázek různého použití:

- [Ukázka základního použití](examples/basic/)
- [Ukázka použití s laxním omezovačem](examples/lax/)
- [Testovací ukázka použitá při vývoji](examples/dev/)
- TODO

Podrobnější informace o fungování a použití Segami naleznete v *připravované* [dokumentaci](doc).

- [Vytváření názvu generovaného obrázků](doc/ImageName.md)
- [Připravení omezovače pro limitaci generovaných obrázků](doc/Limiter.md)
- TODO

## Licence / License

Segami je licencováno pod [MIT licencí](LICENSE).

------------------------------

## TODO roadmap

- [x] Získání originálního obrázku
- [x] Získání vygenerovaného obrázku z cache
- [ ] Modifikace originálního obrázku přes plugin
  - [ ] CorePlugin - Základní funkce, převod mezi formáty
    - [x] Z rastrového formátu na rastrový formát
    - [ ] Z vektorového formátu na rastrový formát
    - [ ] Z rastrový formátu na vektorový formát
  - [ ] CropPlugin - Vytvoření výřezu z originálního obrázku
    - [x] Výřez o zadané výšce a šířce
    - [ ] Výřez začínající na souřadnicích x, y
  - [x] ResizePlugin - Změna velikosti originálního obrázku
    - [x] Změna velikosti na zadanou výšku a šířku
    - [x] Jak se zachovat pokud není zachován poměr stran
      - [x] fill - Vyplní celou oblast deformovaným obrázkem
      - [x] contain - Vynutí zachování originálního poměru stran a nastavené rozměry jsou brány jako maximální možné
      - [x] cover - Vyplní celou oblast obrázkem bez deformace ale s oříznutím
  - [x] QualityPlugin - Nastavení kvality/komprese obrázku
  - [ ] Filtry aplikované na obrázek
    - [ ] BlurPlugin - Rozmazání obrázku
    - [ ] BrightnessPlugin - Jas obrázku
    - [ ] ContrastPlugin - Kontrast obrázku
    - [ ] GrayscalePlugin - Převod do odstínu šedé
    - [ ] InvertPlugin - Invertování obrázku
    - [ ] OpacityPlugin - Celková průhlednost obrázku
- [ ] Omezovače názvu obrázku
  - [x] Volný omezovač obrázku neboli Fake omezovač - Vše je povoleno
  - [x] Striktní omezovač obrázku - definice přesných kombinací hodnot a vlastnosti
  - [x] Laxní omezovač - definice povolených hodnot vlastností bez vynucení dodržení přesné kombinace.
  - [ ] Jednoduché omezovače pro jednotlivé pluginy
    - [x] Omezovač pro CorePlugin
    - [x] Omezovač pro CropPlugin
    - [x] Omezovač pro ResizePlugin
    - [x] Omezovač pro QualityPlugin
    - [ ] Omezovač pro BlurPlugin
    - [ ] Omezovač pro BrightnessPlugin
    - [ ] Omezovač pro ContrastPlugin
    - [ ] Omezovač pro GrayscalePlugin
    - [ ] Omezovač pro InvertPlugin
    - [ ] Omezovač pro OpacityPlugin
- [ ] Logger přístupu k obrázku
  - [x] Žádné ukládání přístupu
  - [x] Ukládání pomocí souborového systému jako datum poslední modifikace
  - [ ] Ukládání do MySQL/MariaDB databáze
  - [ ] Ukládání do SQLite databáze
  - [ ] Ukládání do MongoDB databáze
