<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
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
        // Users
        $users = [];

        $admin = new User();
        $admin
            ->setFirstName('Administrateur')
            ->setLastName('de SymRecipe')
            ->setPseudo(null)
            ->setEmail('admin@symrecipe.fr')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('passwyesord');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null )
                ->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
          }

        // Ingredient
        // on fait une boucle et l'ingredient on met a l'interieur.
        // Создаем список ингидиентов 
        $ingredients = [];
        for ($i=1; $i <= 50; $i++) { 
            $ingredient = new Ingredient();
            // $ingredient->setName('Ingredient ' . $i)
            $ingredient
                ->setName($this->faker->word())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[ mt_rand(0, count($users)-1) ]);
            
            $ingredients[] = $ingredient; 
            // перед тем как вставить (->persist) ингридиент добавляем его в список
            $manager->persist($ingredient);
        }
         
        // Recipes
        $recipes = [];   
        for ($j=0; $j < 25 ; $j++) { 
            $recipe = new Recipe();
            $recipe
                ->setName($this->faker->word())
                ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440) : null)
                ->setNbPeople(mt_rand(0, 1) == 1 ? mt_rand(1, 50) : null)
                ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5) : null)
                ->setDescription($this->faker->text(500))
                ->setPrice(mt_rand(0, 1) == 1 ? mt_rand(1, 1000) : null)
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false)
                ->setIsPublic(mt_rand(0, 1) == 1 ? true : false)
                ->setUser($users[ mt_rand(0, count($users)-1) ]);

            // entre 5 et 15 ingr par recette
            for ($k=0; $k < mt_rand(5, 15) ; $k++) { 
                // pour la $recipe en cours tu vas ajouter un ingr et comme il est dans un boucle il y en a ajoutra plusieurs(entre 5 et 15 ingr par recette)
                // on lui ajoute des ings qui sont ds la liste en haut ($ingredients[] = $ingredient; ) => la taille de la list -1 (count($ingredients)-1)
                $recipe->addIngredient($ingredients[ mt_rand(0, count($ingredients)-1) ]);
            }
            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        // Marks
        foreach ($recipes as $recipe) {
            for ($i=0; $i < mt_rand(0, 4) ; $i++) { 
                $mark = new Mark();
                $mark
                    ->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users)-1)])
                    ->setRecipe($recipe); 
                    
                $manager->persist($mark);
            }          
        }

        // Contact
        for ($i=0; $i < 5; $i++) { 
            $contact = new Contact();
            $contact
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($i +1))
                ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        $manager->flush();
    }

        
}

// 10. composer require --dev orm-fixture
// Fixtures are used to load a "fake" set of data into a database that can then be used for testing or to help give you some interesting data while you're developing your application.
// Persistence - постоянство - une fois l'objet est cree et remplie(ici new Ingredient()) => il faudra persister l'objet => dire a l'objet qu il est pret a aller a la base de donnee avec l'info qu'il contient et ensuit => flush - сброс via le manager (manager contient plusieurs methodes)
// 11. require fakerphp/faker --dev
// 12. php bin/console doctrine:fixtures:load => загружаем данные в phpmyadmin