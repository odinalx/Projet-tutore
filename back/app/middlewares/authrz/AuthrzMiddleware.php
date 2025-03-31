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

    // Injection du service de gestion des autorisations
    public function __construct(AuthrzServiceInterface $authrzService)
    {
        $this->authrzService = $authrzService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {   
        // Récupération de l'en-tête 'Authorization' contenant le token JWT
        $authHeader = $request->getHeaderLine('Authorization');

        // Vérification du format de l'en-tête Authorization
        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->respondWithError("Token manquant ou mal formé", 400);
        }

        $token = $matches[1];

        // Décodage du token JWT pour extraire les données associées (ici l'ID de l'utilisateur)
        $jwtManager = new JWTManager();
        $auth_data = $jwtManager->decodeToken($token);
        $method = $request->getMethod();  // Récupération de la méthode HTTP (GET, POST, etc.)
        $path = $request->getUri()->getPath();  // Récupération du chemin de la requête
        $userId = $auth_data['id'];  // ID de l'utilisateur depuis le token JWT

        // Vérification des droits d'accès en fonction de l'URL et de l'action
        if (strpos($path, '/organismes') === 0) {
            try {
                $this->authrzService->isGrantedOrganisme($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if ($method === 'POST' && $path === '/sections') { // Autorisation Creation Section
            try {
                $this->authrzService->isGrantedResponsable($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/sections/([a-f0-9\-]+)$#', $path, $matches)) { // Autres Autorisations pour les sections
            $sectionId = $matches[1];
            try {
                $this->authrzService->isGrantedSection($userId, $sectionId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/users/([a-f0-9\-]+)/encadrants$#', $path, $matches)) {
            try {
                $this->authrzService->isGrantedResponsable($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/sections/([a-f0-9\-]+)/encadrants/([a-f0-9\-]+)$#', $path, $matches)) {
            $sectionId = $matches[1];
            try {
                $this->authrzService->isGrantedSection($userId, $sectionId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/champs(/.*)?$#', $path)) {
            try {
                $this->authrzService->isGrantedResponsable($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/formulaires$#', $path) && $method === 'POST') {
            try {
                $this->authrzService->isGrantedResponsable($userId);
            } catch (AuthrzInvalidRoleException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/formulaires/([a-f0-9\-]+)$#', $path, $matches)) {
            $formulaireId = $matches[1];

            try {
                $this->authrzService->isGrantedFormulaireSection($userId, $formulaireId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/formulaires/([a-f0-9\-]+)/champs/([a-f0-9\-]+)$#', $path, $matches)) {
            $formulaireId = $matches[1];

            try {
                $this->authrzService->isGrantedFormulaireSection($userId, $formulaireId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        } else if (preg_match('#^/paiements/([a-f0-9\-]+)(/paiements-partiels)?$#', $path, $matches)) {
            $paiementId = $matches[1];
            try {
                // Vérifiez si l'utilisateur est bien celui associé au paiement
                $this->authrzService->isGrantedPaiement($userId, $paiementId);
            } catch (AuthrzInvalidRoleException | AuthrzNotOwnerException $e) {
                return $this->respondWithError($e->getMessage(), 403);
            }
        }

        return $handler->handle($request);
    }

    // Méthode utilitaire pour renvoyer une réponse d'erreur
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
