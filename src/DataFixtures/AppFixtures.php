<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{   
    /**@var Generator */

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
        
    public function load(ObjectManager $manager): void
    {
        // on fait une boucle et l'ingredient on met a l'interieur:
        for ($i=1; $i <= 49; $i++) { 
            $ingredient = new Ingredient();
            // $ingredient->setName('Ingredient ' . $i)
            $ingredient->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));
            $manager->persist($ingredient);
        }             
        $manager->flush();
    }
}

// 10. composer require --dev orm-fixture
// Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you're developing your application.
// Persistence - постоянство - une fois l'objet est cree et remplie(ici new Ingredient()) => il faudra persister l'objet => dire a l'objet qu il est pret a aller a la base de donnee avec l'info qu'il contient et ensuit => flush - сброс via le manager (manager contient plusieurs methodes)
// 11. require fakerphp/faker --dev
// 12. php bin/console doctrine:fixtures:load => загружаем данные в phpmyadmin