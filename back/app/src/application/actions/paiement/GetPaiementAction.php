<?php

namespace slv\application\actions\paiement;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\paiement\ServicePaiementInterface;
use slv\core\services\paiement\ServicePaiementException;

class GetPaiementAction extends AbstractAction
{

    private ServicePaiementInterface $servicePaiement;

    public function __construct(ServicePaiementInterface $servicePaiement)
    {
        $this->servicePaiement = $servicePaiement;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $paiementDTO = $this->servicePaiement->getPaiement($args['id']);
            $responseData = [
                'id' => $paiementDTO->id,
                'status' => $paiementDTO->status,
                'montant_total' => $paiementDTO->montant_total,
                'reste_a_payer' => $paiementDTO->reste_a_payer,
                'user_id' => $paiementDTO->user_id,
                'section_id' => $paiementDTO->section_id,
                'updated_at' => $paiementDTO->updated_at,                
                'paiements_partiels' => array_map(fn($paiementPartiel) => [
                    'id' => $paiementPartiel->id,
                    'paiement_id' => $paiementPartiel->paiement_id,
                    'montant' => $paiementPartiel->montant,
                    'date_paiement' => $paiementPartiel->date_paiement,
                    'mode_paiement' => $paiementPartiel->mode_paiement,
                ], $paiementDTO->paiements_partiels),
            ];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
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
