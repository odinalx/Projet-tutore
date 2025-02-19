<?php

namespace slv\application\actions\sections;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use slv\application\actions\AbstractAction;
use slv\core\services\sections\ServiceSectionException;
use slv\core\services\sections\ServiceSectionInterface;

class AddUserToSectionAction extends AbstractAction
{
    private ServiceSectionInterface $serviceSection;

    public function __construct(ServiceSectionInterface $serviceSection)
    {
        $this->serviceSection = $serviceSection;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        try {
            $sectionId = $args['id'];
            $userId = $args['userId'];

            $data = $rq->getParsedBody();

            if (!isset($data['role'])) {
                return $this->respondWithError($rs, "Le rôle de l'utilisateur n'est pas défini", 400);
            }

            // Vérification que l'utilisateur et la section existent
            $this->serviceSection->addUserToSection($userId, $sectionId, $data['role']);

            $responseData = [
                'success' => true,
                'message' => 'Utilisateur ajouté à la section avec succès'
            ];

            $rs->getBody()->write(json_encode($responseData));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
        } catch (ServiceSectionException $e) {
            return $this->respondWithError($rs, $e->getMessage(), 400);
        } catch (\Exception $e) {
            return $this->respondWithError($rs, "Erreur interne : " . $e->getMessage(), 500);
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
