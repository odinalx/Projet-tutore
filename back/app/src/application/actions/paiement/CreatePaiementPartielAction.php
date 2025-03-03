<?php

namespace slv\application\actions\paiement;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\paiement\ServicePaiementInterface;
use slv\core\services\paiement\ServicePaiementException;

class CreatePaiementPartielAction extends AbstractAction
{
    private ServicePaiementInterface $servicePaiement;

    public function __construct(ServicePaiementInterface $servicePaiement)
    {
        $this->servicePaiement = $servicePaiement;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

            if (!isset($data['montant']) || !isset($data['mode_paiement'])) {
                return $this->respondwithError($rs, 'Il manque des attributs', 400);
            }            

            $this->servicePaiement->createPaiementPartiel($args['id'], $data['montant'], $data['mode_paiement']);
            $responseData = [
                'success' => true,
                'message' => 'Paiement partiel enregistré avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (ServicePaiementException $e) {
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
