<?php

namespace app\middlewares\authrz;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use app\providers\JWTManager;
use Slim\Psr7\Response;
use slv\core\services\authorization\AuthrzServiceInterface;
use slv\core\services\authorization\AuthrzInvalidRoleException;
use slv\core\services\authorization\AuthrzNotOwnerException;

class AuthrzMiddleware
{
    private AuthrzServiceInterface $authrzService;

    public function __construct(AuthrzServiceInterface $authrzService)
    {
        $this->authrzService = $authrzService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->respondWithError("Token manquant ou mal formé", 400);
        }

        $token = $matches[1];

        // Décode le token JWT
        $jwtManager = new JWTManager();
        $auth_data = $jwtManager->decodeToken($token);
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();
        $userId = $auth_data['id'];

        if (strpos($path, '/organismes') === 0) {
            try {
                $this->authrzService->isGrantedOrganisme($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if ($method === 'POST' && $path === '/sections') { // Autorisation Creation Section
            try {
                $this->authrzService->isGrantedCreateSection($userId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/sections/([a-f0-9\-]+)$#', $path, $matches)) { // Autres Autorisations pour les sections
            $sectionId = $matches[1];
            try {
                $this->authrzService->isGrantedSection($userId, $sectionId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e ) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        }

        return $handler->handle($request);
    }

    private function respondWithError(string $message, int $status): ResponseInterface
    {
        $responseData = [
            'status' => $status,
            'error' => $message
        ];
        $response = new Response();
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
