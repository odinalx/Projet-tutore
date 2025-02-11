<?php
namespace slv\application\actions\auth;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use app\providers\JWTManager;
use slv\application\actions\AbstractAction;

class ValidateTokenAction extends AbstractAction
{
    private JWTManager $jwtManager;

    public function __construct(JWTManager $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $authHeader = $rq->getHeaderLine('Authorization');
            if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                return $this->respondWithError($rs, 'Token manquant ou mal formé', 401);
            }

            $token = $matches[1];
            $decoded = $this->jwtManager->decodeToken($token);

            $responseData =[
                'success' => true,
                'message' => 'Token valide',
                'data' => $decoded,
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            return $this->respondWithError($rs, $e->getMessage(), 401);
        }
    }

    private function respondWithError(ResponseInterface $response, string $message, int $status): ResponseInterface
    {
        $responseData = ['error' => $message];
        $response->getBody()->write(json_encode($responseData));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
