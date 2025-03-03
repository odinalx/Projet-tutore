<?php

namespace slv\application\actions\paiement;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\paiement\ServicePaiementInterface;
use slv\core\services\paiement\ServicePaiementException;

class CreatePaiementAction extends AbstractAction
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
            $decodedtoken = $rq->getAttribute('decoded_token');

            if (!isset($data['montant_total']) || !isset($data['reste_a_payer']) || !isset($data['user_id']) || !isset($data['section_id'])) {
                return $this->respondWithError($rs, 'Il manque des attributs', 400);
            }

            if (!$decodedtoken || !isset($decodedtoken['id'])) {
                return $this->respondWithError($rs, 'Impossible de récupérer l\'identifiant utilisateur', 401);
            }

            $userId = $decodedtoken['id'];

            $this->servicePaiement->createPaiement($data['montant_total'], $data['reste_a_payer'], $userId, $data['section_id']);

            $responseData = [
                'success' => true,
                'message' => 'Paiement enregistré avec succès'
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
