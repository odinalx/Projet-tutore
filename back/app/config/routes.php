<?php

use app\middlewares\auth\AuthMiddleware;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use slv\application\actions\HomeAction;
use app\middlewares\authrz\AuthrzMiddleware;
use slv\application\actions\auth\RegisterAction;
use slv\application\actions\auth\LoginAction;
use slv\application\actions\auth\RefreshAction;
use slv\application\actions\auth\ValidateTokenAction;
use slv\application\actions\organisme\CreateOrganismeAction;
use slv\application\actions\organisme\GetOrganismeAction;
use slv\application\actions\organisme\DeleteOrganismeAction;
use slv\application\actions\organisme\UpdateOrganismeAction;
use slv\application\actions\sections\CreateSectionAction;
use slv\application\actions\sections\GetSectionAction;
use slv\application\actions\sections\DeleteSectionAction;
use slv\application\actions\sections\UpdateSectionAction;

return function(App $app): App {

    // Public routes
    $app->get('/', HomeAction::class)->setName('home');

    // Routes pour l'authentification 
    $app->post('/auth/register', RegisterAction::class)->setName('register');
    $app->post('/auth/login', LoginAction::class)->setName('login');
    $app->post('/auth/refresh', RefreshAction::class)->setName('refresh');
    $app->post('/tokens/validate', ValidateTokenAction::class)->setName('validateToken');

    // Routes pour les organismes
    $app->group('/organismes', function ($group) {
        $group->get('/{id}', GetOrganismeAction::class)->setName('getOrganisme');
        $group->post('', CreateOrganismeAction::class)->setName('createOrganisme');
        $group->delete('/{id}', DeleteOrganismeAction::class)->setName('deleteOrganisme');
        $group->patch('/{id}', UpdateOrganismeAction::class)->setName('updateOrganisme');
    })->add(AuthMiddleware::class)->add(AuthrzMiddleware::class); 

    // Routes pour les sections
    $app->group('/sections', function ($group) {
        $group->get('/{id}', GetSectionAction::class)->setName('getSection');
        $group->post('', CreateSectionAction::class)->setName('createSection');
        $group->delete('/{id}', DeleteSectionAction::class)->setName('deleteSection');
        $group->patch('/{id}', UpdateSectionAction::class)->setName('updateSection');
    })->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);

    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};
