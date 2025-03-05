<?php

namespace slv\application\actions\paiement;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\paiement\ServicePaiementException;
use slv\core\services\paiement\ServicePaiementInterface;

class DeletePaiementAction extends AbstractAction
{
    private ServicePaiementInterface $servicePaiement;

    public function __construct(ServicePaiementInterface $servicePaiement)
    {
        $this->servicePaiement = $servicePaiement;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
{
    try {
        $this->servicePaiement->deletePaiement($args['id']);

        $responseData = [
            'id' => $args['id'],
            'message' => 'Paiement supprimé avec succès'
        ];

        $rs->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
