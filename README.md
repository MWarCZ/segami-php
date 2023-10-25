
# Projekt Segami

Menší obrázková knihovna pro zautomatizování generování obrázků a jejich ukládání.
Knihovna zajišťuje úpravu rozměrů obrázku, převod formátu obrázku, ukládání vygenerovaných obrázků, zaznamenávání posledního přístupu k vygenerovanému obrázku a odstraňování dlouhodobě nevyužitých obrázků.

## Použití

### Nastavení knihovny

> **TODO**

### Formát URL

URL adresa pro získání originálního obrázku: `[image_name]`
> Např.: `sample.png`

URL adresa pro získání vygenerovaného obrázku vždy obsahuje znak `@`, který odděluje originální název souboru od modifikátorů.

#### Základ = Převod formátu

> Formát: `[image_name]@[?modifiers].[format]`

- `sample.png@[modifiers].webp` = Převod na formát WebP
- `sample.png@[modifiers].jpg` = Převod na formát JPEG

#### Modifikátor - Výřez obrázku

> Formát: modifier = `[crop][?from]`; crop = `c[size]`||`c[width]x[height]`; from = `f[x_y]`||`f[x]x[y]`

- `sample.png@c200.webp` = Rozměr 200x200 ze středu obrázku
- `sample.png@c200f20.webp` = Rozměr 200x200 z bodu 20x20
- `sample.png@c200f20x30.webp` = Rozměr 200x200 z bodu 20x30
- `sample.png@c200x300.webp` = Rozměr 200x300 ze středu obrázku
- `sample.png@c200x300f20.webp` = Rozměr 200x300 z bodu 20x20
- `sample.png@c200x300f20x30.webp` = Rozměr 200x300 z bodu 20x30

#### Modifikátor - Změna velikosti obrázku

> Formát: modifier = `[resize][?type]`; resize = `r[size]`||`r[width]x[height]`; type = `_[fill|contain|cover]`

- `sample.png@r200.webp` = Rozměr 200x200 a chování obrázku: fill
  - `sample.png@r200.webp`
  - `sample.png@r200x200.webp`
  - ... viz. dále `_fill`, `_fil`, `_l`
- `sample.png@r200x300.webp` = Rozměr 200x300 a obrázek vyplní celý prostor s deformací (fill)
  - `sample.png@r200x300.webp`
  - `sample.png@r200x300_fill.webp`
  - `sample.png@r200x300_fil.webp`
  - `sample.png@r200x300_l.webp`
- `sample.png@r200x300_n.webp` = Maximální rozměr 200x300 a obrázek zachová originální poměr stran (contain)
  - `sample.png@r200x300_n.webp`
  - `sample.png@r200x300_contain.webp`
  - `sample.png@r200x300_con.webp`
- `sample.png@r200x300_r.webp` = Rozměr 200x300 a obrázek vyplní celý prostor bez deformace (cover)
  - `sample.png@r200x300_r.webp`
  - `sample.png@r200x300_cover.webp`
  - `sample.png@r200x300_cov.webp`

#### Modifikátor - Změna kvality / komprese

> Formát: `q[compression]`

- `sample.png@q100.webp` = Bez komprese (nejnižší komprese)
- `sample.png@q1.webp` = Největší komprese

## Roadmap

- [x] Zpracování parametrů obrázku z URL adresy (formát, výška, šířka, komprese)
- [x] Převádění obrázků do požadovaných formátů
- [x] Převádění obrázků do požadovaných rozměrů
- [ ] Ošetření různých vstupů
  - [x] Existuje zdrojový obrázek
  - [x] Nulové rozměry šířky a výšky = automatické dopočítání rozměru
  - [x] Kontrola podporovaných formátů
  - [ ] Podpora komprese u daného formátu
  - [x] Nastavování a kontrola omezení pro tvorbu obrázků
    - [x] Striktní omezení na generované obrázky => Seznam kombinací parametrů: `[výška, šířka, formát] = obrázek`
    - [x] Laxní omezení na generování obrázků => Seznam povolených rozměrů a seznam formátů: `[[výška_1, šířka_1], [výška_2, šířka_2], ...] + [formát_1, formát_2, ...] = obrázek`
    - [x] Laxnější omezení na generování obrázků => Seznam povolených výšek, šířek a formátů: `[výška_1, výška_2, ...] + [šířka_1, šířka_2, ...] + [formát_1, formát_2, ...] = obrázek`
    - [x] Volné / Bez omezení.
  - [ ] ...
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
- [ ] Přepracování a rozšíření parametrů obrázku v URL adrese
- [ ] ...
