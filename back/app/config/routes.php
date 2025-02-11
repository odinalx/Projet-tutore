<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use slv\application\actions\HomeAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');
                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};