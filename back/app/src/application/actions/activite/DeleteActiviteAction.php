<?php

namespace slv\application\actions\activite;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\Activite\ServiceActiviteException;
use slv\core\services\activite\ServiceActiviteInterface;

class DeleteActiviteAction extends AbstractAction
{
    private ServiceActiviteInterface $serviceActivite;

    public function __construct(ServiceActiviteInterface $serviceActivite)
    {
        $this->serviceActivite = $serviceActivite;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
{
    try {
        $this->serviceActivite->DeleteActivite($args['id']);

        $responseData = [
            'id' => $args['id'],
            'message' => 'Activite supprimé avec succès'
        ];

        $rs->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
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
