<?php

namespace app\actions\auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\services\auth\AuthService;

class RegisterAction
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['nom']) || !isset($data['prenom'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => [
                    'message' => 'Email, mot de passe, nom et prénom requis',
                    'code' => 400
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }
        
        try {
            $this->authService->register($data);
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès'
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode() ?: 400
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
} 