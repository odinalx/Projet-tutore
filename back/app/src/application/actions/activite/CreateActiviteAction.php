<?php

namespace slv\application\actions\activite;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\activite\ServiceActiviteInterface;
use slv\core\services\activite\ServiceActiviteException;

class CreateActiviteAction extends AbstractAction
{
    private ServiceActiviteInterface $serviceActivite;

    public function __construct(ServiceActiviteInterface $serviceActivite)
    {
        $this->serviceActivite = $serviceActivite;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $data = $rq->getParsedBody();

        
            $requiredFields = ['nom', 'description', 'sections_id', 'lieu_id', 'date_debut', 'date_fin'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    return $this->respondWithError($rs, "Il manque l'attribut: $field", 400);
                }
            }

            

            $this->serviceActivite->createActivite(
                $data['nom'],
                $data['description'],
                $data['sections_id'],
                $data['lieu_id'],
                $data['date_debut'],
                $data['date_fin']
            );

            $responseData = [
                'success' => true,
                'message' => 'Activité enregistrée avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (ServiceActiviteException $e) {
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
