<?php

namespace app\actions\auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use app\services\auth\AuthService;

class LoginAction
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => [
                    'message' => 'Email et mot de passe requis',
                    'code' => 400
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }
        
        try {
            $result = $this->authService->login($data['email'], $data['password']);
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => [
                    'accessToken' => $result['accessToken'],
                    'refreshToken' => $result['refreshToken']
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode() ?: 401
                ]
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
} 