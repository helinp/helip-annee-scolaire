<?php

/**
 * Démonstration de l'utilisation de la librairie
 * 
 * @license https://opensource.org/licenses/MIT
 */

use Helip\Anneescolaire\AnneeScolaire;

require_once __DIR__ . '/../vendor/autoload.php';

//
// Avec l'instanciation
//
$anneeScolaireA = new AnneeScolaire('2022-2023');
$anneeScolaireB = new AnneeScolaire(new \DateTime('2022-12-29'));
$anneeScolaireC = new AnneeScolaire(new \DateTime('2024-04-13'));

// Récupérer la date de début et de fin de l'année
print('L\'année scolaire ' . $anneeScolaireA->getAnneeScolaire()
    . ' commence le ' . $anneeScolaireA->getDateDebut()->format('d/m/Y')
    . ' et se termine le ' . $anneeScolaireA->getDateFin()->format('d/m/Y') . PHP_EOL
);

// Comparaison A == B
print('Les années scolaires ' . $anneeScolaireA->getAnneeScolaire() .
    ' et ' . $anneeScolaireB->getAnneeScolaire() .
    ($anneeScolaireA == $anneeScolaireB ? ' sont identiques' : ' sont différentes') . PHP_EOL
);

// Comparaison A == C
print('Les années scolaires ' . $anneeScolaireA->getAnneeScolaire() . ' et ' . $anneeScolaireC->getAnneeScolaire() .
    ($anneeScolaireA == $anneeScolaireC ? ' sont identiques' : ' sont différentes') . PHP_EOL
);

// Année précédente et suivante
print('L\'année scolaire ' . $anneeScolaireA->getAnneeScolaire() .
    ' est suivie par l\'année scolaire ' . $anneeScolaireA->getAnneeScolaireSuivante() .
    ' et précédée par l\'année scolaire ' . $anneeScolaireA->getAnneeScolairePrecedente() .
    PHP_EOL
);

//
// Avec les méthodes statiques
//
// Création d'un objet DateTime
$date = new DateTime('2022-12-05');

// Calcul de la date de début de l'année scolaire à partir de la date donnée
$dateDebut = AnneeScolaire::returnDebutAnneeScolaireFromDate($date);

// Calcul de la date de fin de l'année scolaire à partir de la date donnée
$dateFin = AnneeScolaire::returnFinAnneeScolaireFromDate($date);

// Affichage des dates de début et de fin de l'année scolaire
echo 'Début de l\'année scolaire : ' . $dateDebut->format('d/m/Y') . PHP_EOL;
echo 'Fin de l\'année scolaire : ' . $dateFin->format('d/m/Y') . PHP_EOL;
