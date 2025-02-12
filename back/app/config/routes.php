<?php

use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use slv\application\actions\HomeAction;
use slv\application\actions\auth\RegisterAction;
use slv\application\actions\auth\LoginAction;
use slv\application\actions\auth\RefreshAction;
use slv\application\actions\auth\ValidateTokenAction;
use slv\application\actions\organisme\CreateOrganismeAction;
use slv\application\actions\organisme\GetOrganismeAction;
use slv\application\actions\organisme\DeleteOrganismeAction;
use slv\application\actions\organisme\UpdateOrganismeAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Routes pour l'authentification
    $app->post('/auth/register', RegisterAction::class)->setName('register');
    $app->post('/auth/login', LoginAction::class)->setName('login');
    $app->post('/auth/refresh', RefreshAction::class)->setName('refresh');
    $app->post('/tokens/validate', ValidateTokenAction::class)->setName('validateToken');

    // Routes pour les organismes
    $app->get('/organismes/{id}', GetOrganismeAction::class)->setName('getOrganisme');
    $app->post('/organismes', CreateOrganismeAction::class)->setName('createOrganisme');
    $app->delete('/organismes/{id}', DeleteOrganismeAction::class)->setName('deleteOrganisme');
    $app->patch('/organismes/{id}', UpdateOrganismeAction::class)->setName('updateOrganisme');
                                                            
    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};