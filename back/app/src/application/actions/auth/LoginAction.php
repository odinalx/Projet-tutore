<?php
namespace slv\application\actions\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use slv\core\services\auth\ServiceAuthInterface;
use slv\core\dto\auth\CredentialsDTO;
use slv\core\services\auth\AuthenticationException;
use slv\application\actions\AbstractAction;

class LoginAction extends AbstractAction {
    private ServiceAuthInterface $authService;

    public function __construct(ServiceAuthInterface $authService) {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        try {
            $data = $rq->getParsedBody();

            if (!$data || empty($data['email']) || empty($data['password'])) {
                return $this->respondWithError($rs, 'Il manque un crédential', 400);
            }

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->respondWithError($rs, 'Format de l\'email invalide', 400);
            }

            $credentials = new CredentialsDTO($data['email'], $data['password']);
            $credentials->validate();

            $userDto = $this->authService->login($credentials);

            $responseData = [
                'success' => true,
                'data' => [
                    'accessToken' => $userDto->accessToken,
                    'refreshToken' => $userDto->refreshToken,
                ],
            ];        
            
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (AuthenticationException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 401);
        } catch (\Exception $e) {
            return $this->respondWithError($rs, $e->getMessage(), 500);
        }
    }

    private function respondWithError(ResponseInterface $response, string $message, int $status): ResponseInterface
    {
        $responseData = [
            'status' => $status,
            'error' => $message
        ];

        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

}