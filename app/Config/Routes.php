<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// Définir le contrôleur par défaut
$routes->setDefaultController('RectettesController');

// Désactiver l'AutoRoute 
$routes->setAutoRoute(false);

// Définir la route '/'
$routes->get('/', 'RecettesController::index');

/*
slug placholder 

- [a-z0-9]+ : une ou plusieurs répétitions de ces caractères
- (?:  : un groupe sans capture
- - : un tiret
- [a-z0-9]+ : une ou plusieurs répétitions de ces caractères
- )* : aucune ou plusieurs rép

- Une séquence de caractères alphanuérmiques au début de la chaîne de caractères 
- Ensuite, ça va trouver un tiret, puis une séquence de caractères alphanumériques, aucune ou plusieurs fois

Exemple :  item12345 n-article-de-blog
*/
$routes->addPlaceholder('slug', '[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*'); 
// Utilisation de la fonction "match()" pour associer la route aux méthodes "post" et "get"
$routes->match(['get', 'post'], '/', 'RecettesController::index');

// Définir la route "/" 
$routes->get('/', 'RecettesController::index');
// Définir la route "rectette/id"
$routes->get('recette/(:num)', 'RecettesController::recetteParId/$1');
// Définir la route "recette/slug"
$routes->get('recette/(:slug)', 'RecettesController::recetteParSlug/$1');

// Afficher un formulaire vide pour créer une nouvelle recette
$routes->get('/creer', 'RecettesController::creer');
// Afficher un formulaire avec les informations d'une recette, permettant de la modifier
$routes->get('/modifier/(:num)', 'RecettesController::modifier/$1');
// Supprimer une recette 
$routes->get('/supprimer/(:num)', 'RecettesController::supprimer/$1');
// Sauvegarder une nouvelle recette 
$routes->post('/sauvegarder', 'RecettesController::sauvegarder');
// Sauvegarder une recette existante
$routes->post('/sauvegarder/(:num)', 'RecettesController::sauvegarder/$1');


