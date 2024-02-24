
# Projekt Segami

Malá knihovna pro zautomatizování generování obrázků na základě jejich jména.
Knihovna zajišťuje úpravu rozměrů obrázku, převod formátu obrázku, ukládání vygenerovaných obrázků, zaznamenávání posledního přístupu k vygenerovanému obrázku, odstraňování dlouhodobě nevyužitých obrázků a to vše na základě názvu obrázku.

## Instalace / Installation

Segami je dostupná na [Packagist](https://packagist.org/packages/mwarcz/segami) a instalace přes [Composer](https://getcomposer.org/) je doporučenou cestou k instalaci Segami.

Stačí použít příkaz:

```bash
composer require mwarcz/segami
```

Nebo manuálně přidat do souboru `composer.json` řádek:

```json
"mwarcz/segami": "^1.1.1"
```

## Požadavky / Requirements

- PHP 8
- imagick

## Použití / Usage

Tento repositář obsahuje konkrétní ukázky kódu:

- [Ukázka základního použití](examples/basic/)
- [Ukázka použití s laxním omezovačem](examples/lax/)
- [Testovací ukázka použitá při vývoji](examples/dev/)
- TODO

Nebo textově popsáno fungování a možnosti nastavení:

- [Vytváření názvu generovaného obrázků](doc/ImageName.md)
- [Připravení omezovače pro limitaci generovaných obrázků](doc/Limiter.md)
- TODO

=========================================================

## Old roadmap

- [x] Zpracování parametrů obrázku z URL adresy (formát, výška, šířka, komprese)
- [ ] Převádění obrázků do požadovaných formátů
  - [x] Převod mezi formáty využívající raster (gif, png, jpg, webp, avif)
  - [ ] Převod z vektorového obrázku na raster (svg => png, jpg, webp)
- [ ] Převádění obrázků do požadovaných rozměrů
  - [x] Změna rozměru obrázku využívajících raster (gif, png, jpg, webp, avif)
  - [ ] Změna rozměrů vektorových obrázků (svg)
- [ ] Vytváření výřezů z obrázku
  - [x] Vytvoření výřezu o zadaných rozměrech
  - [ ] Vytvořit výřez s vrcholem (ze souřadnic X a Y)
  - [ ] Vytvořit výřez SVG obrázku
- [x] Cache - ukládání vygenerovaných obrázků
  - [x] Možnost vybírat zda bude ukládán či nikoliv
  - [ ] Vytváření obrázků
    - [x] Vytváření obrázků za pomocí knihovny Imagick
    - [ ] Vytváření obrázků za pomocí knihovny GD
  - [x] Detekce žádosti o existující a neexistující upravený obrázek
  - [ ] Zaznamenávání poslední žádosti o zobrazení obrázku
    - [x] Záznam za pomocí souborového systému a mtime parametru u souboru obrázku
    - [ ] Záznam za pomocí databáze (př. MySQL)
  - [x] Odebírání vygenerovaných obrázků dle názvu originálního obrázku
  - [x] Odebírání vygenerovaných obrázků, které nebyly dlouhodobě použity
    - [x] Využívat záznam o posledním použití/zobrazení obrázku
  - [ ] ...
- [ ] Přepracování veškerá ošetření vstupů (rozměr, typy obrázků, kombinace, ...)
- [ ] ...

> - [ ] Ošetření různých vstupů
>   - [x] Existuje zdrojový obrázek
>   - [x] Nulové rozměry šířky a výšky = automatické dopočítání rozměru
>   - [x] Kontrola podporovaných formátů
>   - [ ] Podpora komprese u daného formátu
>   - [x] Nastavování a kontrola omezení pro tvorbu obrázků
>     - [x] Striktní omezení na generované obrázky => Seznam kombinací parametrů: `[výška, šířka, formát] = obrázek`
>     - [x] Laxní omezení na generování obrázků => Seznam povolených rozměrů a seznam formátů: `[[výška_1, šířka_1], [výška_2, šířka_2], ...] + [formát_1, formát_2, ...] = obrázek`
>     - [x] Laxnější omezení na generování obrázků => Seznam povolených výšek, šířek a formátů: `[výška_1, výška_2, ...] + [šířka_1, šířka_2, ...] + [formát_1, formát_2, ...] = obrázek`
>     - [x] Volné / Bez omezení.
>   - [ ] ...
