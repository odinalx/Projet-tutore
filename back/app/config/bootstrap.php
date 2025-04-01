<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use app\middlewares\cors\Cors;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php' ); // Fichier de configuration générale de l'application
$builder->addDefinitions(__DIR__ . '/dependencies.php'); // Fichier de définition des services et des dépendances

$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->add(new Cors());

// Ajout des middlewares standards de Slim
$app->addBodyParsingMiddleware(); // Permet d'interpréter les données envoyées via POST (ex: JSON, formulaire, etc.)
$app->addRoutingMiddleware(); // Active le système de routage de Slim
$app->addErrorMiddleware(true, false, false); // Gère les erreurs (affiche les erreurs en mode développement)

// Inclusion des routes définies dans le fichier `routes.php`
$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

return $app;