<?php

namespace slv\application\actions\encadrants;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\encadrants\ServiceEncadrantInterface;
use slv\core\services\encadrants\ServiceEncadrantException;

class GetEncadrantsByUserAction extends AbstractAction {

    private ServiceEncadrantInterface $serviceEncadrant;

    public function __construct(ServiceEncadrantInterface $serviceEncadrant)
    {
        $this->serviceEncadrant = $serviceEncadrant;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $encadrants = $this->serviceEncadrant->getEncadrantsByUserId($args['id']);
            $responseData = [];
            foreach ($encadrants as $encadrant) {
                $responseData[] = [                    
                    'id' => $encadrant->id,
                    'nom' => $encadrant->nom,
                    'prenom' => $encadrant->prenom,
                    'email' => $encadrant->email,
                    'role' => $encadrant->role,
                    'section_id' => $encadrant->section_id,
                ];
            }
            $responseData = [
                'encadrants' => $responseData
            ];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (ServiceEncadrantException $e) {
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