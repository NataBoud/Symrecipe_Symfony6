# PROJET Symrecipe_Symfony6

### C'est un projet pour gérer des recettes ###
### But - apprendre les base d'une technologie
- Création des ingrédients qui vont eux-mêmes vont se retrouver dans les recettes que je vais créer 
- Mis en place ces recettes en privées 
- Partager ces recettes à la communauté (aux autres utilisateurs - une système de gestion de сompte, de sécurité,... )

1. Installation PHP 8.1 or higher & Composer &  installation Symfony CLI.
2. lancer cette commande: symfony check:requirements
3. symfony new symrecipe --version="6.3.*" --webapp
    Les dossiers: 
    - bin - des fichier de commandes(vider le cash de symfony, mis à jour de la BD, lancemet de test unitaire avec phpunit)
    - config - la configuration des paquets, des services et des routes
    - migrations - pour transposer les objets crées à l'aide de l'ORM (object relational maping) doctrine via la BD => transformation du code php (extraits des fichier, notamment des Entitys) vers une des commands SQL qui rempliront la BD et créeront le schéma de BD
    - public - le point d'accès à l'application (chaque requêtes passe par ici)
    - src - le coeur du projet
    - templates - toute la vue du projet (HTML)
    - vendor - relier au composer
4. php bin/console - pour voir toutes les commandes
5. lancement du server - commande => symfony => symfony server:start

### Mise en place header, page d'accueil ###
6. Création d'une page => templates => home.html.twig => HomeController.php => extends AbstractController => #[Route('/','home.index',)] => fonction return $this->render('home.html.twig',); 
7. Template Inheritance and Layouts => home.html.twig => {% extends 'base.html.twig' %} => compléter les blocks
8. Les styles => on utilise bootswatch => on ajoute les links à base.html.twig
9. Reusing Template Contents => include (header par examp)=> {% include 'partials/_header.html.twig'%}
10. jumbotron bootstrap 5 => home.html.twig => on remplie {% block body %}

### CRUD Ingrédients Databases and Doctrine ORM ###
11. Configuration de la BD => .env.dev.local => DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8&charset=utf8mb4
12. phpMyAdmin => création de la table symrecipe via terminal => php bin/console doctrine:database:create
13. Création de l'intité => php bin/console make:entity => Ingredient.php et IngredientRepository.php
14. Migration => php bin/console make:migration => symfony interprete l'objet qu'on a créé en SQL (ficher migration)
15. php bin/console doctrine:migration:migrate => pousser toutes les migrations qu'on a dans le ficher migration dans la BD
16. Géstion de la validation des données => Ingredient.php => use Symfony\Component\Validator\Constraints as Assert; => ajouter des contraintes - des Assert => #[Assert\...()]
17. Metre en place des fixtures => géneration de jeu de fausses données cohérentes => doc DoctrineFixturesBundle => installation -  composer require --dev orm-fixtures et composer require fakerphp/faker --dev => dossier DataFixtures - AppFixtures.php (dedans on crée => $ingredient = new Ingredient(); ...) => php bin/console doctrine:fixtures:load (l'envoi les données à la BD)

18. Mise en place de la "Listes des ingrédients" **** CRUD des Ingrédients 