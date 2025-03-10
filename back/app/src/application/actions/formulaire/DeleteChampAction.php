<?php

namespace slv\application\actions\formulaire;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\formulaire\ServiceFormulaireException;
use slv\core\services\formulaire\ServiceFormulaireInterface;

class DeleteChampAction extends AbstractAction
{
    private ServiceFormulaireInterface $serviceFormulaire;

    public function __construct(ServiceFormulaireInterface $serviceFormulaire)
    {
        $this->serviceFormulaire = $serviceFormulaire;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
{
    try {
        $this->serviceFormulaire->deleteChamp($args['id']);

        $responseData = [
            'id' => $args['id'],
            'message' => 'Champ supprimé avec succès'
        ];

        $rs->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (ServiceFormulaireException $e) {
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
