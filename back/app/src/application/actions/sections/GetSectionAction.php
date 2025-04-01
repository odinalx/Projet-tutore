<?php

namespace slv\application\actions\sections;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\sections\ServiceSectionException;
use slv\core\services\sections\ServiceSectionInterface;

class GetSectionAction extends AbstractAction
{

    private ServiceSectionInterface $serviceSection;

    public function __construct(ServiceSectionInterface $serviceSection)
    {
        $this->serviceSection = $serviceSection;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $sectionDTO = $this->serviceSection->getSection($args['id']);
            $responseData = [
                'id' => $sectionDTO->id,
                'nom' => $sectionDTO->nom,
                'description' => $sectionDTO->description,
                'categorie' => $sectionDTO->categorie,
                'capacite' => $sectionDTO->capacite,
                'tarif' => $sectionDTO->tarif,
                'organisme_id' => $sectionDTO->organisme_id,
                'created_at' => $sectionDTO->created_at,
                'updated_at' => $sectionDTO->updated_at
            ];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
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
