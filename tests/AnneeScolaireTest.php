<?php

namespace Helip\AnneeScolaire\Tests;

use DateTime;
use Helip\AnneeScolaire\AnneeScolaire;
use PHPUnit\Framework\TestCase;

class AnneeScolaireTest extends TestCase
{
    public function testConstructString()
    {
        $anneeScolaire = new AnneeScolaire('2022-2023');
        $this->assertInstanceOf(AnneeScolaire::class, $anneeScolaire);

        $this->assertEquals('2022-2023', $anneeScolaire->getAnneeScolaire());
        $this->assertEquals('2022-08-29', $anneeScolaire->getDateDebut()->format('Y-m-d'));
        $this->assertEquals('2023-07-07', $anneeScolaire->getDateFin()->format('Y-m-d'));
    }

    public function testConstructDate()
    {
        $anneeScolaire = new AnneeScolaire(new DateTime('2022-08-29'));
        $this->assertInstanceOf(AnneeScolaire::class, $anneeScolaire);

        $this->assertEquals('2022-2023', $anneeScolaire->getAnneeScolaire());
        $this->assertEquals('2022-08-29', $anneeScolaire->getDateDebut()->format('Y-m-d'));
        $this->assertEquals('2023-07-07', $anneeScolaire->getDateFin()->format('Y-m-d'));

        $anneeScolaire = new AnneeScolaire(new DateTime('2023-03-07'));
        $this->assertInstanceOf(AnneeScolaire::class, $anneeScolaire);

        $this->assertEquals('2022-2023', $anneeScolaire->getAnneeScolaire());
        $this->assertEquals('2022-08-29', $anneeScolaire->getDateDebut()->format('Y-m-d'));
        $this->assertEquals('2023-07-07', $anneeScolaire->getDateFin()->format('Y-m-d'));

        $anneeScolaire = new AnneeScolaire(new DateTime('1990-10-07'));
        $this->assertEquals('1990-09-03', $anneeScolaire->getDateDebut()->format('Y-m-d'));
        $this->assertEquals('1991-06-28', $anneeScolaire->getDateFin()->format('Y-m-d'));
    }

    public function testgetAnneeScolaire()
    {
        $anneeScolaire = new AnneeScolaire('2022-2023');
        $this->assertEquals('2022-2023', $anneeScolaire->getAnneeScolaire());

        $anneeScolaire = new AnneeScolaire(new DateTime('2022-08-29'));
        $this->assertEquals('2022-2023', $anneeScolaire->getAnneeScolaire());
    }

    public function testIsAnneeScolaireValid()
    {
        $this->assertTrue(AnneeScolaire::isAnneeScolaireValid('2022-2023'), 'L\'année scolaire 2022-2023 est valide');
        $this->assertTrue(AnneeScolaire::isAnneeScolaireValid('2023-2024'), 'L\'année scolaire 2022-2024 est valide');
        $this->assertFalse(AnneeScolaire::isAnneeScolaireValid('2022-2024'), 'L\'année scolaire 2022-2024 n\'est pas valide');
        $this->assertFalse(AnneeScolaire::isAnneeScolaireValid('2022-202'), 'L\'année scolaire 2022-202 n\'est pas valide');
        $this->assertFalse(AnneeScolaire::isAnneeScolaireValid('2022-20222'), 'L\'année scolaire 2022-20222 n\'est pas valide');
        $this->assertFalse(AnneeScolaire::isAnneeScolaireValid('2022-2022'), 'L\'année scolaire 2022-2022 n\'est pas valide');
        $this->assertFalse(AnneeScolaire::isAnneeScolaireValid('2022 - 2023'), 'L\'année scolaire 2022 - 2023 n\'est pas valide');
    }

    public function testisEntreAoutEtDecembre()
    {
        $this->assertTrue(AnneeScolaire::isEntreAoutEtDecembre(new \DateTime('2022-08-01')), 'Le 1er août 2022 est entre août et décembre');
        $this->assertTrue(AnneeScolaire::isEntreAoutEtDecembre(new \DateTime('2022-12-31')), 'Le 31 décembre 2022 est entre août et décembre');
        $this->assertFalse(AnneeScolaire::isEntreAoutEtDecembre(new \DateTime('2022-07-31')), 'Le 31 juillet 2022 n\'est pas entre août et décembre');
        $this->assertFalse(AnneeScolaire::isEntreAoutEtDecembre(new \DateTime('2022-01-01')), 'Le 1er janvier 2022 n\'est pas entre août et décembre');
    }

    public function testReturnDebutAnneeScolaireFromDate()
    {
        $this->assertEquals(new \DateTime('2022-08-29'), AnneeScolaire::returnDebutAnneeScolaireFromDate(new \DateTime('2022-10-29')), 'Le 29 octobre 2022 fait partie de l\'année scolaire qui débute le 2022-08-29');
        $this->assertEquals(new \DateTime('2022-08-29'), AnneeScolaire::returnDebutAnneeScolaireFromDate(new \DateTime('2023-05-29')), 'Le 29 mai 2023 fait partie de l\'année scolaire qui débute le 2022-08-29');
    }
}
