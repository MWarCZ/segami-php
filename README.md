
# Projekt Segami

Menší obrázková knihovna pro zautomatizování generování obrázků a jejich ukládání.
Knihovna zajišťuje úpravu rozměrů obrázku, převod formátu obrázku, ukládání vygenerovaných obrázků, zaznamenávání posledního přístupu k vygenerovanému obrázku a odstraňování dlouhodobě nevyužitých obrázků.

## Použití

### Nastavení knihovny

> **TODO**

### Formát URL

URL adresa pro získání originálního obrázku: `[image_name]`
> Např.: `sample.png`

URL adresa pro získání vygenerovaného obrázku: `[image_name]@[action][size].[format]`, `[image_name]@[action][width]x[height].[format]`
> Např.: `sample.png@r500x200.webp`, `sample.png@c300x400.png`,  `sample.png@r300.webp`

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
