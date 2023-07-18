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

### CRUD Ingrédients Databases and Doctrine ORM (object relational mapping) ###

11. Configuration de la BD => .env.dev.local => DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8&charset=utf8mb4
12. phpMyAdmin => création de la table symrecipe via terminal => php bin/console doctrine:database:create
13. Création de l'intité => php bin/console make:entity => Ingredient.php et IngredientRepository.php
14. Migration => php bin/console make:migration => symfony interprete l'objet qu'on a créé en SQL (ficher migration)
15. php bin/console doctrine:migration:migrate => pousser toutes les migrations qu'on a dans le ficher migration dans la BD
16. Géstion de la validation des données => Ingredient.php => use Symfony\Component\Validator\Constraints as Assert; => ajouter des contraintes - des Assert => #[Assert\...()]
17. Metre en place des fixtures => géneration de jeu de fausses données cohérentes => doc DoctrineFixturesBundle => installation -  composer require --dev orm-fixtures et composer require fakerphp/faker --dev => dossier DataFixtures - AppFixtures.php (dedans on crée => $ingredient = new Ingredient(); ...) => php bin/console doctrine:fixtures:load (l'envoi les données à la BD)

### Mise en place de la "Listes des ingrédients" **** CRUD des Ingrédients

19. Fetching an object back out of the database. Methode findAll().  
20. via terminal => php bin/console make:controller IngredientController => création IngredientController.php et le dossier ingredient avec index.html.twig dans  templates
21. Dans IngredientController.php => on va utiliser repository => public function index(IngredientRepository $repository): Response {method findAll()} => return 'pages/ingredient/index.html.twig'
22. # dd() ("dump and die" - "сбрось и умри") 
Компонент VarDumper также предоставляет функцию ##dd() ("dump and die" - "сбрось и умри"). Эта функция отображает переменные используя dump() и сразу прекращает исполнение скрипта (используя функцию PHP exit).
23. # https://twig.symfony.com/
Documentation TWIG
24. On ajoute bootswhatch/United - tables dans index.html.twig
25. ERROR: Object of class DateTimeImmutable could not be converted to string
Pour afficher date => twig filter date  => on ajoute le filtre => |date('dd/mm/YYYY')
26. # pagitation: 
https://github.com/KnpLabs/KnpPaginatorBundle => composer require knplabs/knp-paginator-bundle => on copie YAML code et on l'ajoute dans: config/packages/knp_paginator.yaml
27. Modification IngredientController.php => on ajoute => PaginatorInterface $paginator, Request $request. 
28. On change view dans index.html.twig <div class="navigation">{{ knp_pagination_render(ingredients) }}</div> => puis on change template dans knp_paginator.yaml => pagination: '@KnpPaginator/Pagination/bootstrap_v5_pagination.html.twig'     # sliding pagination controls template
29. On remet les fixtures à zero => php bin/console d:f:l

### Partie CREATE / Création d'un engrédient à travers les formulaires dans Symfony
# Création un IngredientType.php -> on ajoute des contraintes -> modification des labels -> la class IngredientType(le formulaire) va se baser sur l'Entity(Ingredient.php) -> construction ce formulaire au sein du IngredientController.php (controller new)
30. php bin/console make:form => src/Form/IngredientType.php => maintenant il faut ajouter les champs => ajouter form dans IngredientController.php =>  return $this->render('pages/ingredient/new.html.twig');
31. On crée la page new.html.twig => Création d'un ingrédient
32. TextType::class => documentation => https://symfony.com/doc/current/reference/forms/types/text.html
33. IngredientType.php => on ajoute des class de 'attr'(bootswhatch) => ->add('name', TextType::class, ['attr' => ['class' => 'form-control'],]) 
34. Processing Forms => dans IngredientController.php on ajoute => public function new(EntityManagerInterface $manager) 
et dans Response { $manager->persist($ingredient);
                    $manager->flush() };

35. Dans new.html.twig on ajoute de doc de symfony => Form Rendering Functions => {{ form_start(form) }} content ici on utilise bootswatch code -> <div class="form-group"></div> {{ form_end(form) }}
36. Flash Messages
37. 
        