<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GlobalErrorController extends AbstractController
{
    #[Route('/global/error', name: 'app_global_error')]
    public function index(int $code): Response
    {
        $message = $code === 404 ? 'Page non trouvée' : 'Une erreur s\'est produite';
        return $this->render('global_error/index.html.twig', [
            'message' => $message,
            'status_code' => $code,
        ]);
    }
}
