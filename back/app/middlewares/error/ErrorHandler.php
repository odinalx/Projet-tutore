<?php

namespace app\middlewares\error;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpException;
use Throwable;

class ErrorHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $e) {
            return $this->handleError($e, $request);
        } catch (Throwable $e) {
            return $this->handleError($e, $request);
        }
    }

    private function handleError(Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $response = new \Slim\Psr7\Response();
        $response = $response->withHeader('Content-Type', 'application/json');
        
        $message = $e->getMessage();
        
        if ($e instanceof \Slim\Exception\HttpUnauthorizedException) {
            $message = 'Vous devez être connecté pour accéder à cette ressource';
        }
        
        if ($e instanceof \Slim\Exception\HttpForbiddenException) {
            $message = 'Vous n\'avez pas les droits nécessaires pour accéder à cette ressource';
        }
        
        if ($e instanceof \Slim\Exception\HttpNotFoundException) {
            $message = 'La ressource demandée n\'existe pas';
        }
        
        if ($e instanceof \Respect\Validation\Exceptions\ValidationException) {
            $message = $e->getMessage();
        }
        
        $responseBody = json_encode([
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $e->getCode() ?: 0
            ]
        ]);
        
        $response->getBody()->write($responseBody);
        
        return $response->withStatus(200);
    }
} 