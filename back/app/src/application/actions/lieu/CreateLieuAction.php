<?php

namespace slv\application\actions\lieu;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\lieu\ServiceLieuInterface;
use slv\core\services\lieu\ServiceLieuException;

class CreateLieuAction extends AbstractAction
{
    private ServiceLieuInterface $serviceLieu;

    public function __construct(ServiceLieuInterface $serviceLieu)
    {
        $this->serviceLieu = $serviceLieu;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

            $requiredFields = ['nom', 'adresse', 'ville', 'code_postal'];
            foreach ($requiredFields as $field) {
                if (empty($data[$field])) {
                    return $this->respondWithError($rs, "Il manque l'attribut: $field", 400);
                }
            }

            $this->serviceLieu->CreateLieu(
                $data['nom'],
                $data['adresse'],
                $data['ville'],
                $data['code_postal']
            );

            $responseData = [
                'success' => true,
                'message' => 'Lieu enregistré avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
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
