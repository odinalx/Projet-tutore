<?php

namespace slv\application\actions\sections;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\sections\ServiceSectionException;
use slv\core\services\sections\ServiceSectionInterface;

class CreateSectionAction extends AbstractAction
{
    private ServiceSectionInterface $serviceSection;

    public function __construct(ServiceSectionInterface $serviceSection)
    {
        $this->serviceSection = $serviceSection;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

            if (!isset($data['nom']) || !isset($data['description']) || !isset($data['categorie']) || !isset($data['capacite']) || !isset($data['tarif']) || !isset($data['organisme_id'])) {
                return $this->respondWithError($rs, 'Nom, description, catégorie, capacité, tarif et organisme_id requis', 400);
            }

            $this->serviceSection->createSection($data['nom'], $data['description'], $data['categorie'], $data['capacite'], $data['tarif'], $data['organisme_id']);

            $responseData = [
                'success' => true,
                'message' => 'Section enregistré avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (ServiceSectionException $e) {
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
