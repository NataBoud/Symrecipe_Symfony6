# PROJET Symrecipe_Symfony6

### C'est un projet pour gérer des recettes ###
### But - apprendre les base d'une technologie
- Création des ingrédients qui vont eux-mêmes vont se retrouver dans les recettes que je vais créer 
- Mis en place ces recettes en privées 
- Partager ces recettes à la communauté (aux autres utilisateurs - une système de gestion de сompte, de sécurité,... )

#### Les points à améliorer:
 - Translation of flash messages with parameters
 - Les messages d'alertes - la confirmation de supprission
 - Modification/création de la recette - les styles de les ingrédients
 - Gestion d'accès aux pages 

#### pagitation: 
https://github.com/KnpLabs/KnpPaginatorBundle => composer require knplabs/knp-paginator-bundle => on copie YAML code et on l'ajoute dans: config/packages/knp_paginator.yaml

https://symfony.com/doc/current/reference/forms/types/repeated.html#second-options => pour doubler le champs avec le mot de passe

#### Gestion d'accès aux pages (routes)

https://symfony.com/bundles/SensioFrameworkExtraBundle/current/annotations/security.html#isgranted

https://symfony.com/doc/current/security/expressions.html

https://symfony.com/doc/current/security/voters.html

