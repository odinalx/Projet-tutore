<?php

namespace slv\application\actions\organisme;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\organisme\ServiceOrganismeException;
use slv\core\services\organisme\ServiceOrganismeInterface;

class GetOrganismeAction extends AbstractAction
{

    private ServiceOrganismeInterface $serviceOrganisme;

    public function __construct(ServiceOrganismeInterface $serviceOrganisme)
    {
        $this->serviceOrganisme = $serviceOrganisme;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $organismeDTO = $this->serviceOrganisme->getOrganisme($args['id']);
            $responseData = [
                'id' => $organismeDTO->id,
                'nom' => $organismeDTO->nom,
                'description' => $organismeDTO->description,
                'adresse' => $organismeDTO->adresse,
                'created_at' => $organismeDTO->created_at,
                'updated_at' => $organismeDTO->updated_at
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
