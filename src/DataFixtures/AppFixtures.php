<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\User;
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
        // Ingredient

        // on fait une boucle et l'ingredient on met a l'interieur.
        // Создаем список ингидиентов 
        $ingredients = [];
        for ($i=1; $i <= 50; $i++) { 
            $ingredient = new Ingredient();
            // $ingredient->setName('Ingredient ' . $i)
            $ingredient
                ->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100));
            
            $ingredients[] = $ingredient; 
            // перед тем как вставить (->persist) ингридиент добавляем его в список
            $manager->persist($ingredient);
        }
         
        // Recipes
        
        for ($j=0; $j < 25 ; $j++) { 
            $recipe = new Recipe();
            $recipe
                ->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);

            // entre 5 et 15 ingr par recette
            for ($k=0; $k < mt_rand(5, 15) ; $k++) { 
                // pour la $recipe en cours tu vas ajouter un ingr et comme il est dans un boucle il y en a ajoutra plusieurs(entre 5 et 15 ingr par recette)
                // on lui ajoute des ing qui sont ds la liste en haut ($ingredients[] = $ingredient; ) => la taille de la list -1 (count($ingredients)-1)
                $recipe->addIngredient($ingredients[ mt_rand(0, count($ingredients)-1) ]);
            }
            $manager->persist($recipe);
        }

        // Users

        for ($i=0; $i < 10; $i++) { 
           $user = new User();
           $user
            ->setFullName($this->faker->name())
            ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null )
            ->setEmail($this->faker->email())
            ->setRoles(['ROLE USER'])
            ->setPlainPassword('password');

            $manager->persist($user);
         }

        $manager->flush();
    }
}

// 10. composer require --dev orm-fixture
// Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you're developing your application.
// Persistence - постоянство - une fois l'objet est cree et remplie(ici new Ingredient()) => il faudra persister l'objet => dire a l'objet qu il est pret a aller a la base de donnee avec l'info qu'il contient et ensuit => flush - сброс via le manager (manager contient plusieurs methodes)
// 11. require fakerphp/faker --dev
// 12. php bin/console doctrine:fixtures:load => загружаем данные в phpmyadmin