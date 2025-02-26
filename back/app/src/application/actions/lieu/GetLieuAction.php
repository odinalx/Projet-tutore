<?php

namespace slv\application\actions\lieu;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\lieu\ServiceLieuException;
use slv\core\services\lieu\ServiceLieuInterface;

class GetLieuAction extends AbstractAction
{
    private ServiceLieuInterface $serviceLieu;

    public function __construct(ServiceLieuInterface $serviceLieu)
    {
        $this->serviceLieu = $serviceLieu;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $lieuDTO = $this->serviceLieu->getLieu($args['id']);
            $responseData = [
                'id' => $lieuDTO->id,
                'nom' => $lieuDTO->nom,
                'adresse' => $lieuDTO->adresse,
                'ville' => $lieuDTO->ville,
                'code_postal' => $lieuDTO->code_postal,
                'created_at' => $lieuDTO->created_at,
                'updated_at' => $lieuDTO->updated_at
            ];
            $rs->getBody()->write(json_encode($responseData));
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
