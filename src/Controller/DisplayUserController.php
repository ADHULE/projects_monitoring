<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\DisplayUserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/{id}/profile', name: 'app_user_profile', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('display_user/show.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DisplayUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //******************************** Ajout de l'image***************************** */
            $imageFile = $form->get('photo')->getData();

            if ($imageFile) {
                $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                // Move the file to the directory where images are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                // Update the 'image' property to store the image file name
                // instead of its contents
                $user->setPhoto($newFilename);
            }
            //************************************************************************************ */


            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('display_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    
}
