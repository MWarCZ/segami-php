<!--
//* Project: segami-php
//* File: doc/ImageName.md
-->
# Formát pro vytváření názvů obrázků

## Originální obrázek

Pro získání originálního obrázku stačí použít původní název obrázku bez přípony začínající na `@`.

Formát pro získání originálního obrázku: `[image_name]`
> Formát: `[image_name]`

- **Příklady:**
  - `sample.png`
  - `segami.jpg`

URL adresa pro získání vygenerovaného obrázku vždy obsahuje znak `@`, který odděluje originální název souboru od modifikátorů.

## Základ = Převod formátu a identifikace modifikátorů

Plugin `CorePlugin` je základní plugin, který 1. umožňuje převod do různých formátů a 2. extrahuje další modifikátory pro další pluginy.

> Formát: `[image_name]@[modifiers].[format]`;
> modifiers = `[]`||`[modifier]`||`[modifier].[modifiers]`

- **Příklady:**
  - `sample.png@[modifiers].webp` = Převod na formát WebP
  - `sample.png@[modifiers].jpg` = Převod na formát JPEG
  - `sample.png@[modifiers].PNG` = Převod na formát PNG

## Modifikátor - Výřez obrázku

Plugin `CropPlugin` umožňuje vytvářet výřezy z originálního obrázku.

> Formát:
> modifier = `[crop][?from]`;
> crop = `c[size]`||`c[width]x[height]`;
> from = `f[x_y]`||`f[x]x[y]`;
> x = `[int]`||`center`||`c`||`left`||`l`||`right`||`r`;
> y = `center`||`c`||`top`||`t`||`bottom`||`b`

- **Příklady:**
  - `sample.png@c200.webp` = Rozměr 200x200 ze středu obrázku
  - `sample.png@c200f20.webp` = Rozměr 200x200 z bodu 20x20
  - `sample.png@c200f20x30.webp` = Rozměr 200x200 z bodu 20x30
  - `sample.png@c200x300.webp` = Rozměr 200x300 ze středu obrázku
  - `sample.png@c200x300f20.webp` = Rozměr 200x300 z bodu 20x20
  - `sample.png@c200x300f20x30.webp` = Rozměr 200x300 z bodu 20x30
  - `sample.png@c200x300fRightxBottom.webp` = Rozměr 200x300 z bodu pravý dolní roh
  - `sample.png@c200x300fRxB.webp` = Rozměr 200x300 z bodu pravý dolní roh
  - `sample.png@c200x300frxb.webp` = Rozměr 200x300 z bodu pravý dolní roh
  - `sample.png@c200x300fCenterxTop.webp` = Rozměr 200x300 z bodu střed na horní hraně
  - `sample.png@c200x300fcxt.webp` = Rozměr 200x300 z bodu střed na horní hraně

## Modifikátor - Změna velikosti obrázku

Plugin `ResizePlugin` umožňuje upravit rozměr originálního obrázku a způsob vytvoření obrázku o požadovaném rozměru.

> Formát:
> modifier = `[resize][?type]`;
> resize = `r[size]`||`r[width]x[height]`;
> type = `_[fill|l|contain|n|cover|r|fit|t]`

- **Příklady:**
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
  - `sample.png@r200x300_t.webp` = Rozměr 200x300 a obrázek se přizpůsobí rozměru, ale zachová originální poměr stran (fit - Jde o contain, ale výsledný obrázek bude mít přesně požadovaný rozměr)
    - `sample.png@r200x300_t.webp`
    - `sample.png@r200x300_fit.webp`

## Modifikátor - Změna kvality / komprese

Plugin `QualityPlugin` umožňuje upravovat kvalitu výsledného obrázků (Ovlivňuje kompresi obrázku u formátů, které podporují kompresi.).

> Formát: `q[compression]`

- **Příklady:**
  - `sample.png@q100.webp` = Bez komprese (nejnižší komprese)
  - `sample.png@q1.webp` = Největší komprese
