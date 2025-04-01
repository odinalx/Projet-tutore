<?php

namespace slv\application\actions\organisme;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\organisme\ServiceOrganismeException;
use slv\core\services\organisme\ServiceOrganismeInterface;

class GetOrganismesAction extends AbstractAction
{

    private ServiceOrganismeInterface $serviceOrganisme;

    public function __construct(ServiceOrganismeInterface $serviceOrganisme)
    {
        $this->serviceOrganisme = $serviceOrganisme;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $organismes = $this->serviceOrganisme->getOrganismes();
            $responseData = [];
            foreach ($organismes as $organisme) {
                $responseData[] = [
                    'id' => $organisme->id,
                    'nom' => $organisme->nom,
                    'description' => $organisme->description,
                    'adresse' => $organisme->adresse,
                    'created_at' => $organisme->created_at,
                    'updated_at' => $organisme->updated_at
                ];
            }
            $responseData = [
                'data' => $responseData
            ];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (ServiceOrganismeException $e) {
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
