
# AnneeScolaire

Permet de manipuler les années scolaires en Belgique (Fédération Wallonie-Bruxelles). 
Il permet de calculer la date de début et de fin d'une année scolaire à partir d'une date ou d'une année scolaire.

Cette librairie calcule l'année scolaire selon le décret relatif à l'adaptation des rythmes scolaires annuels dans l'**enseignement fondamental et secondaire ordinaire, spécialisé, secondaire artistique à horaire réduit et de promotion sociale** et aux mesures d'accompagnement pour l'accueil temps libre du 31/03/2022. [Lien Gallilex](https://www.gallilex.cfwb.be/document/pdf/50303_000.pdf)

Elle calcule également les années scolaires antérieures à l'application du décret du 31/03/2022.

## Limitations

Ne fonctionne actuellement que pour les établissements fonctionnant sur 37 semaines d'ouverture.

## Installation

Pour installer ce package, vous pouvez utiliser Composer :

```bash
  composer require helip/anneescolaire
```

Méthodes
--------

### \_\_construct(mixed $start)

Le constructeur de la classe. Accepte une date de début de l'année scolaire sous forme YYYY-YYYY ou d'un objet DateTime.
```php
$anneeScolaire = new AnneeScolaire('2021-2022');`
```
```php
$anneeScolaire = new AnneeScolaire(new \DateTime('2022-12-29'));`
```
### getDateDebut(): DateTime

Retourne la date de début de l'année scolaire.

### getDateFin(): DateTime

Retourne la date de fin de l'année scolaire.

### getAnneeScolaire(): string

Retourne l'année scolaire sous forme YYYY-YYYY.

### getAnneeScolaireSuivante(): string

Retourne l'année scolaire suivante sous forme YYYY-YYYY.

### getAnneeScolairePrecedente(): string
Retourne l'année scolaire précédente sous forme YYYY-YYYY.

## License

[GNU General Public License v3.0](https://choosealicense.com/licenses/gpl-3.0/)