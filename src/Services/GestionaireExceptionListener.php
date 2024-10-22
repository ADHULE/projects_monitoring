<?php
namespace App\GestionaireEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Twig\Environment;

class GestionaireExceptionListener
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $message = 'La page que vous cherchez n\'existe pas! Merci pour la comprehension';

        // Personnaliser le message pour les exceptions HTTP
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        // Rendre une réponse Twig
        $response = new Response(
            $this->twig->render('error/custom_error.html.twig', [
                'message' => $message,
                'status_code' => $statusCode,
            ])
        );

        $response->setStatusCode($statusCode);
        $event->setResponse($response);
    }
}
