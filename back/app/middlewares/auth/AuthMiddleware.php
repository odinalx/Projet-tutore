<?php
namespace app\middlewares\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Slim\Psr7\Response;

class AuthMiddleware implements MiddlewareInterface
{
    private ClientInterface $authClient;

    public function __construct(ClientInterface $authClient) {
        $this->authClient = $authClient;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): Response {
        $authHeader = $request->getHeaderLine('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return $this->respondWithError('Token manquant ou mal formé', 400);
        }

        $token = $matches[1];

        try {
            $this->authClient->request('POST', '/tokens/validate', [
                'headers' => ['Authorization' => "Bearer $token"]
            ]);

            return $handler->handle($request);

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorMessage = match ($statusCode) {
                400 => 'Requête invalide vers le service d\'authentification',
                401 => 'Token invalide ou expiré',
                default => 'Erreur lors de la validation du token'
            };

            return $this->respondWithError($errorMessage, $statusCode);
        }
    }

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
