<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DisplayUserController extends AbstractController
{
    #[Route('/display/user', name: 'app_display_user')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('display_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
