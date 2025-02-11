<?php

namespace slv\application\actions\auth;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\auth\ServiceAuthInterface;
use slv\core\dto\auth\CredentialsDTO;
use slv\core\services\auth\AuthenticationException;

class RegisterAction extends AbstractAction
{

    private ServiceAuthInterface $authService;

    public function __construct(ServiceAuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

            if (!isset($data['email']) || !isset($data['password'])) {
                return $this->respondWithError($rs, 'Email ou mot de passe requis', 400);
            }

            if (!isset($data['nom']) || !isset($data['prenom'])) {
                return $this->respondWithError($rs, 'Nom ou prénom requis', 400);
            }

            $filteredEmail = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->respondWithError($rs, 'Email pas correct', 400);
            }


            $credentials = new CredentialsDTO($filteredEmail, $data['password']);
            $credentials->validate();

            $this->authService->register($credentials, $data['nom'], $data['prenom']);

            $responseData = [
                'success' => true,
                'message' => 'Utilisateur enregistré avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (AuthenticationException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->respondWithError($rs, $e->getMessage(), 500);
        }
    }

    private function respondWithError(ResponseInterface $response, string $message, int $status): ResponseInterface
    {
        $responseData = ['error' => $message];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
