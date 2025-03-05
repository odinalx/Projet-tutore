<?php
namespace slv\application\actions\activite;

use slv\application\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\core\services\activite\ServiceActiviteException;
use slv\core\services\activite\ServiceActiviteInterface;

class UpdateactiviteAction extends AbstractAction
{
    private ServiceActiviteInterface $serviceactivite;

    public function __construct(ServiceActiviteInterface $serviceactivite)
    {
        $this->serviceactivite = $serviceactivite;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $data = json_decode($rq->getBody()->getContents(), true);

        $id = $args['id'];
        $nom = $data['nom'] ?? null;
        $description = $data['description'] ?? null;
        $sections_id = $data['sections_id'] ?? null;
        $lieu_id = $data['lieu_id'] ?? null;
        

        try {
            $activiteDTO = $this->serviceactivite->UpdateActivite($id, $nom, $description, $sections_id, $lieu_id);
            
            $responseData = [
                'message' => 'activite mis à jour avec succès.',
                'data' => [
                    'id' => $activiteDTO->id,
                    'nom' => $activiteDTO->nom,
                    'description' => $activiteDTO->description,
                    'sections_id' => $activiteDTO->sections_id,
                    'lieu_id' => $activiteDTO->lieu_id,
                    'created_at' => $activiteDTO->created_at,
                    'updated_at' => $activiteDTO->updated_at
                ]
            ];
            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (ServiceactiviteException $e) {
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
