<?php

namespace App\Controller;

use App\Service\ExceptionLogger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ErrorController extends AbstractController
{
    public function __construct(
        private Environment $twig,
        private ExceptionLogger $exceptionLogger
    ) {}

    public function error(Request $request, int $code): Response
    {
        $errorCodes = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
        ];

        $title = $errorCodes[$code] ?? 'Error';
        $message = $this->getMessageForCode($code);

        if ($code >= 400 && $code < 500) {
            $this->exceptionLogger->logHttpError($code, $message, $request);
        }

        $content = $this->twig->render('bundles/TwigBundle/Exception/error.html.twig', [
            'code' => $code,
            'title' => $title,
            'message' => $message,
        ]);

        return new Response($content, $code);
    }

    public function genericError(Request $request): Response
    {
        $exception = $request->attributes->get('exception');
        $code = $exception ? $exception->getStatusCode() : 500;
        return $this->error($request, $code);
    }

    private function getMessageForCode(int $code): string
    {
        $messages = [
            400 => 'La solicitud no puede ser procesada.',
            401 => 'Debe autenticarse para acceder a este recurso.',
            403 => 'No tiene permiso para acceder a este recurso.',
            404 => 'La página o recurso solicitado no existe.',
            500 => 'Ha ocurrido un error interno en el servidor.',
        ];

        return $messages[$code] ?? 'Ha ocurrido un error.';
    }
}
