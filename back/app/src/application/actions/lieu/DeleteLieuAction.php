<?php

namespace slv\application\actions\lieu;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\lieu\ServiceLieuException;
use slv\core\services\lieu\ServiceLieuInterface;

class DeleteLieuAction extends AbstractAction
{
    private ServiceLieuInterface $serviceLieu;

    public function __construct(ServiceLieuInterface $serviceLieu)
    {
        $this->serviceLieu = $serviceLieu;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $this->serviceLieu->DeleteLieu($args['id']);

            $responseData = [
                'id' => $args['id'],
                'message' => 'Lieu supprimé avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (ServiceLieuException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 400);
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
