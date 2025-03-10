<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use app\middlewares\cors\Cors;
use app\middlewares\error\ErrorHandler;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php' );
$builder->addDefinitions(__DIR__ . '/dependencies.php');

$c=$builder->build();
$app = AppFactory::createFromContainer($c);

$app->add(new Cors());
$app->add(new ErrorHandler());

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);

$app = (require_once __DIR__ . '/routes.php')($app);
$routeParser = $app->getRouteCollector()->getRouteParser();

return $app;