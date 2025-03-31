<?php
namespace app\middlewares\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use app\providers\JWTManager;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{    
    private JWTManager $jwtManager;

    // Injection du gestionnaire de JWT lors de l'instanciation
    public function __construct(JWTManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response {
        
        // Vérifie si l'en-tête 'Authorization' est présent
        if (!$request->hasHeader('Authorization')) {
            return $this->respondWithError("Header Authorization manquant", 400);
        }
        
        $authHeader = $request->getHeaderLine('Authorization');

        // Vérifie que l'en-tête contient bien un token Bearer
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->respondWithError('Token manquant ou mal formé', 400);
        }

        // Extraction du token
        $token = $matches[1];

        try {
            // Décodage et vérification du token via JWTManager
            $decoded = $this->jwtManager->decodeToken($token);
            
            // Ajout des données du token décodé dans les attributs de la requête
            $request = $request->withAttribute('decoded_token', $decoded);

            return $handler->handle($request);

        } catch (\Exception $e) {
            return $this->respondWithError('Token invalide ou expiré', 401);
        }
    }

    // Méthode utilitaire pour renvoyer une réponse JSON en cas d'erreur
    private function respondWithError(string $message, int $status): Response
    {
        $response = new Response();
        $responseData = [
            'status' => $status,
            'error' => $message
        ];

        $response->getBody()->write(json_encode($responseData, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
