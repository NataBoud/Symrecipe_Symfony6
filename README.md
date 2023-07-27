# PROJET Symrecipe_Symfony6

### C'est un projet pour gérer des recettes ###
### But - apprendre les base d'une technologie
- Création des ingrédients qui vont eux-mêmes vont se retrouver dans les recettes que je vais créer 
- Mis en place ces recettes en privées 
- Partager ces recettes à la communauté (aux autres utilisateurs - une système de gestion de сompte, de sécurité,... )
##### Les points à améliorer:
 - Translation of flash messages with parameters
 - Les messages d'alertes - la confirmation de supprission
 - Les boutons Créer/Modifier la recette/l'ingrédient
 - Modification recette - les styles de les ingrédients 

### Mise en place header, page d'accueil

#### Databases and Doctrine ORM (object relational mapping)

### CRUD Ingrédients / Mise en place de la "Listes des ingrédients"

### https://twig.symfony.com/
Documentation TWIG
### pagitation: 
https://github.com/KnpLabs/KnpPaginatorBundle => composer require knplabs/knp-paginator-bundle => on copie YAML code et on l'ajoute dans: config/packages/knp_paginator.yaml

#### Partie CREATE / Création d'un engrédient à travers les formulaires dans Symfony
#### Création un IngredientType.php -> on ajoute des contraintes -> modification des labels -> la class IngredientType(le formulaire) va se baser sur l'Entity(Ingredient.php) -> construction ce formulaire au sein du IngredientController.php (controller new)

TextType::class => documentation => https://symfony.com/doc/current/reference/forms/types/text.html

### CRUD Recettes
#### Une recette sera définie par:
 - Un nom => obligatoire / ne pourra pas être vide / caractères: max = 50, min = 2 
 - Un temps (en minutes) => n'est pas obligatoire / s'il est rempli: il ne pourra pas être inférieur à 1 min et ne pas depasser 24h 
 - Un nombre de personnes => n'est pas obligatoire / s'il est rempli: inférieur à 50
 - Une difficulté => n'est pas obligatoire / si elle est rentrée: doit être entre 1 et 5
 - Une description (une liste d'étape à suivre) => obligatoire / ne pourra pas être vide
 - Un prix => n'est pas obligatoire / prix > 0, prix < 1000 / il pourra contenir  des décimales
 - La possibilité de définir le recette comme étant favorite ou non
 - Une date de création => générées automatiquement
 - Une date de mise à jour => générées automatiquement

#### Date de mise à jour => symfony lifecycle -> https://symfony.com/doc/current/doctrine/events.html

### La sécurité et le compte d'utilisateur
#### Un compte sera définie par:
- Un nom / prénom => obligatoire / caractères: max = 50, min = 2 
- Un pseudo => n'est pas obligatoire / s'il est renseigné: caractères: max = 50, min = 2 
- Une adresse email => unique et servira d'identifient lors de la connexion
- Un mot de passe => sera encodé en BD, pour des questions de sécurité
- Une date de création => générées automatiquement
- Une date de modification => générées automatiquement
#### Checklist
- Création de l'intité User
- Validation avec les Assert
- Mise en place des fixtures
- Encodage de mot de passe
- Création de formulaire de connexion / inscription
- Modification du profil utilisateur / mot de passe

### Création de formulaire de connexion

### Création de formulaire d'iscription
#### https://symfony.com/doc/current/reference/forms/types/repeated.html#second-options => pour doubler le champs avec le mot de passe

### Formulaire d'édition du profil 

### Formulaire de modification de mot de passe

### MAJ (mis à jour) Ingrédients
- Mise en place de la relation entre l'entité User et l'entité Ingredient
- Modification des fixtures pour lier les ingrédients à un utilisateur
- lier un ingrédient à un utilisateur connecté lors de la création
- Afficher uniquement les ingrédients reliés à l'utilisateur