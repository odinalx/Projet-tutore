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
use slv\application\actions\sections\GetSectionsByUserAction;
use slv\application\actions\sections\AddUserToSectionAction;
use slv\application\actions\encadrants\GetEncadrantsByUserAction;
use slv\application\actions\encadrants\RemoveEncadrantFromSectionAction;
use slv\application\actions\formulaire\CreateFormulaireAction;
use slv\application\actions\formulaire\GetFormulaireAction;
use slv\application\actions\formulaire\DeleteFormulaireAction;
use slv\application\actions\formulaire\UpdateFormulaireAction;
use slv\application\actions\formulaire\CreateChampAction;
use slv\application\actions\formulaire\GetChampAction;
use slv\application\actions\formulaire\DeleteChampAction;
use slv\application\actions\formulaire\AddChampToFormulaireAction;

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
        $group->delete('/{id}', DeleteSectionAction::class)->setName('deleteSection');
        $group->patch('/{id}', UpdateSectionAction::class)->setName('updateSection');
        $group->post('', CreateSectionAction::class)->setName('createSection');
    })->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);    
    
    // Récupérer les sections d'un user
    $app->get('/users/{id}/sections', GetSectionsByUserAction::class)->setName('getSectionsByUser');

    // Associé un user à une section
    $app->post('/sections/{id}/users/{userId}', AddUserToSectionAction::class)->setName('addUserToSection');

    // Routes encadrants
    $app->get('/users/{id}/encadrants', GetEncadrantsByUserAction::class)->setName('getEncadrantsBySection')->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);
    $app->delete('/sections/{section_id}/encadrants/{encadrant_id}', RemoveEncadrantFromSectionAction::class)->setName('removeEncadrant')->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);

    // Routes formulaire
    $app->group('/formulaires', function ($group) {
        $group->post('', CreateFormulaireAction::class)->setName('createFormulaire');
        $group->get('/{id}', GetFormulaireAction::class)->setName('getFormulaire');
        $group->delete('/{id}', DeleteFormulaireAction::class)->setName('deleteFormulaire');
        $group->patch('/{id}', UpdateFormulaireAction::class)->setName('updateFormulaire');
        $group->post('/{formulaire_id}/champs/{champ_id}', AddChampToFormulaireAction::class)->setName('addChampToFormulaire');
    })->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);

    // Routes champs
    $app->group('/champs', function ($group) {
        $group->post('', CreateChampAction::class)->setName('createChamp');
        $group->get('/{id}', GetChampAction::class)->setName('getChamp');
        $group->delete('/{id}', DeleteChampAction::class)->setName('deleteChamp');
    })->add(AuthMiddleware::class)->add(AuthrzMiddleware::class);

    $app->options('/{routes:.+}', function (Request $request, Response $response) {
        return $response;
    });

    return $app;
};



