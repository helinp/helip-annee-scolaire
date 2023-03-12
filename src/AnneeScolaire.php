<?php

namespace Helip\AnneeScolaire;

use DateTime;

/**
 * Cette classe représente une année scolaire
 * dans le système primaraire, secondaire et ESAHR
 * de la Communauté française en Belgique.
 *
 * @package AnneeScolaire
 * @version 0.9.0
 * @license GPL-3.0
 * @author Pierre Hélin <pierre.helin@gmail.com>
 */
class AnneeScolaire
{
    /**
     * Nombre de semaines d'ouvertures dans une année scolaire
     */
    public const NOMBRE_SEMAINES_OUVERTURE = 37;

    /**
     * @var DateTime
     */
    private $dateDebut;

    /**
     * @var DateTime
     */
    private $dateFin;

    /**
     * @var string
     */
    private $anneeScolaire;

    /**
     * AnneeScolaire constructor.
     *
     * Accepte une date de début de l'année scolaire sous forme YYYY-YYYY
     * ou dun objet DateTime
     *
     * @param DateTime|String $start
     * @param array $config
     * @throws \InvalidArgumentException
     */
    public function __construct(string|DateTime $start)
    {
        // Si la date de début est une chaîne de caractères XXXX-XXXX, on vérifie qu'elle est valide
        if (is_string($start) && $this->isAnneeScolaireValid($start)) {
            $this->dateDebut = $this->calculateAnneeScolaireDateDebut(substr($start, 0, 4));

            // Si la date de début est un objet DateTime, on vérifie qu'elle est valide
        } elseif ($start instanceof DateTime) {
            $this->isEntreAoutEtDecembre($start) ? $start : $start->modify('-1 year');
            $this->dateDebut = $this->calculateAnneeScolaireDateDebut($start->format('Y'));
        } else {
            throw new \InvalidArgumentException(
                'La date de début de l\'année scolaire doit être une instance 
                de DateTimeInterface ou une chaîne de caractères valide'
            );
        }

        // Calcul de la date de fin de l'année scolaire
        $this->dateFin = $this->calculateAnneeScolaireDateFin($this->dateDebut->format('Y'));
        $this->anneeScolaire = $this->dateDebut->format('Y') . '-' . $this->dateFin->format('Y');
    }

    /**
     * Retourne la date de début de l'année scolaire
     * @return DateTime
     */
    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }

    /**
     * Retourne la date de fin de l'année scolaire
     * @return DateTime
     */
    public function getDateFin(): DateTime
    {
        return $this->dateFin;
    }

    /**
     * Retourne l'année scolaire sous forme YYYY-YYYY
     * @return string
     */
    public function getAnneeScolaire(): string
    {
        return $this->anneeScolaire;
    }

    /**
     * Retourne la date de début de l'année scolaire à partir d'une date donnée
     *
     * @param DateTime $date
     * @return DateTime
     */
    public static function returnDebutAnneeScolaireFromDate(DateTime $date): DateTime
    {
        $date = clone $date;
        self::isEntreAoutEtDecembre($date) ? $date : $date->modify('-1 year');
        return self::calculateAnneeScolaireDateDebut($date->format('Y'));
    }

    public static function returnFinAnneeScolaireFromDate(DateTime $date): DateTime
    {
        $date = clone $date;
        self::isEntreAoutEtDecembre($date) ? $date : $date->modify('-1 year');
        return self::calculateAnneeScolaireDateFin($date->format('Y'));
    }

    /**
     * Retourne l'année scolaire suivante
     * @return string
     */
    public function getAnneeScolaireSuivante(): string
    {
        return $this->dateFin->format('Y') . '-' . ($this->dateFin->format('Y') + 1);
    }

    /**
     * Retourne l'année scolaire précédente
     * @return string
     */
    public function getAnneeScolairePrecedente(): string
    {
        return ($this->dateDebut->format('Y') - 1) . '-' . $this->dateDebut->format('Y');
    }

    /**
     * Calcule la date de fin de l'année scolaire à partir de l'année donnée
     *
     * @param int $year
     * @return DateTime
     */
    private static function calculateAnneeScolaireDateFin(int $year): DateTime
    {
        // Avant le 31 aout 2022, l'année scolaire se termine le dernier jour ouvrable du mois juin
        if ($year < 2022) {
            // Dernier jour du mois de juin
            $dateFin = new DateTime("@" . strtotime("last day of june " . $year + 1));

            // Si le dernier jour du mois de juin est un samedi,
            // la date de fin de l'année scolaire est le vendredi précédent
            if ($dateFin->format('N') === '6') {
                $dateFin->modify('-1 day');
            }

            // Si le dernier jour du mois de juin est un dimanche,
            // la date de fin de l'année scolaire est le vendredi précédent
            if ($dateFin->format('N') === '7') {
                $dateFin->modify('-2 days');
            }

            return $dateFin;
        }

        // Après 2022, je dernier jour d'une année scolaire correspond au premier vendredi du mois juillet.
        return new DateTime("@" . strtotime("first friday of july " . ($year + 1)));
    }

    /**
     * Calcule la date de début de l'année scolaire à partir de l'année donnée
     * Prend en compte la modifications des rythmes scolaires à partir de 2022
     *
     * @param int $annee
     * @return DateTime
     */
    private static function calculateAnneeScolaireDateDebut(int $annee): DateTime
    {
        // Avant le 31 aout 2022, l'année scolaire se termine le premier jour ouvrable du mois de septembre
        if ($annee < 2022) {
            // Premier jour du mois de septembre
            $dateDebut = new DateTime("@" . strtotime("first day of september $annee"));

            // Si le premier jour du mois de septembre est un samedi,
            // la date de début de l'année scolaire est le lundi suivant
            if ($dateDebut->format('N') === '6') {
                $dateDebut->modify('+2 days');
            }

            // Si le premier jour du mois de septembre est un dimanche,
            // la date de début de l'année scolaire est le lundi suivant
            if ($dateDebut->format('N') === '7') {
                $dateDebut->modify('+1 day');
            }

            return $dateDebut;
        }

        // A partir de 2022, l'année scolaire commence le dernier lundi du mois d'août
        $dateDebut = new DateTime("@" . strtotime("last monday of august $annee"));

        // si cela est nécessaire pour que l'année scolaire comprenne le nombre de
        // 37 semaines d'ouverture hors vacances scolaires...
        if (
            self::countNombreDeSemainesOuverture(
                $dateDebut,
                self::calculateAnneeScolaireDateFin($annee)
            ) < self::NOMBRE_SEMAINES_OUVERTURE
        ) {
            // ...l'année scolaire commence l'avant-dernier lundi du mois d'août
            $dateDebut->modify("last monday of august $annee - 1 week");
        }

        return $dateDebut;
    }

    /**
     * Vérifie si une année scolaire est valide (YYYY-YYYY)
     *
     * @param string $anneeScolaire
     * @return boolean
     */
    public static function isAnneeScolaireValid(string $anneeScolaire): bool
    {
        // vérification du motif de l'expression régulière pour une année scolaire valide (YYYY-YYYY)
        $pattern = '/^\d{4}-\d{4}$/';

        if (!preg_match($pattern, $anneeScolaire)) {
            return false;
        }

        // vérifie que les années sont comprises entre 1900 et 2099
        $parts = explode('-', $anneeScolaire);

        if (
            count($parts) !== 2 || (int) $parts[0] < 1900 || (int) $parts[0] > 2099
            || (int) $parts[1] < 1900 || (int) $parts[1] > 2099
        ) {
            return false;
        }

        // récupération des deux années
        $parts = explode('-', $anneeScolaire);
        $startYear = (int) $parts[0];
        $endYear = (int) $parts[1];

        // vérification que les deux années sont consécutives
        if ($startYear + 1 !== $endYear) {
            return false;
        }

        return true;
    }

    /**
     * Vérifie si une date est comprise entre août et décembre
     *
     * @param DateTime $date
     * @return boolean
     */
    public static function isEntreAoutEtDecembre(DateTime $date): bool
    {
        $month = (int) $date->format('m');
        return $month >= 8 && $month <= 12;
    }

    /**
     * Retourne le nombre de semaines d'ouverture hors vacances scolaires
     * Le resultat est arrondi avec PHP_ROUND_HALF_UP
     *
     * Limitation: calcul uniquement valides pour les années scolaire complètes
     *
     * @param DateTime $dateDebut
     * @param DateTime $dateFin
     * @return int
     */
    private static function countNombreDeSemainesOuverture(DateTime $dateDebut, DateTime $dateFin): int
    {
        $dateDebut = clone $dateDebut;
        $dateFin = clone $dateFin;

        // si la date de fin est un vendredi ou un samedi, on la décale au dimanche suivant
        // pour prendre en compte la semaine entière
        if ($dateFin->format('N') === '5' || $dateFin->format('N') === '6') {
            $dateFin->modify('next sunday');
        }

        $interval = $dateDebut->diff($dateFin);
        $nombreDeSemaines = (int) round($interval->days / 7, 0, PHP_ROUND_HALF_UP);

        // Nombre de semaines de vacances scolaires
        // si après le 31 juillet 2022
        if ($dateDebut > new DateTime('2022-07-31')) {
            // 2 Semaines de vacances à Toussaint, Noël, Carnaval et Pâques
            $semainesVacances = 2 + 2 + 2 + 2;
        } else {
            // 2 semaines de vacances à Noël et Pâques
            // 1 semaine de vacances à Toussaint et Carnaval
            $semainesVacances = 2 + 1 + 2 + 1;
        }

        // Nombre de semaines d'ouverture hors vacances scolaires
        return $nombreDeSemaines - $semainesVacances;
    }
}
