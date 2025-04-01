<?php

namespace slv\application\actions\formulaire;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\formulaire\ServiceFormulaireInterface;
use slv\core\services\formulaire\ServiceFormulaireException;

class CreateFormulaireAction extends AbstractAction
{
    private ServiceFormulaireInterface $serviceFormulaire;

    public function __construct(ServiceFormulaireInterface $serviceFormulaire)
    {
        $this->serviceFormulaire = $serviceFormulaire;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

            if (!isset($data['nom']) || !isset($data['description']) || !isset($data['section_id'])) {
                return $this->respondWithError($rs, 'Il manque des attributs', 400);
            }            

            $this->serviceFormulaire->createFormulaire($data['nom'], $data['description'], $data['section_id']);

            $responseData = [
                'success' => true,
                'message' => 'Formulaire enregistré avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
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
