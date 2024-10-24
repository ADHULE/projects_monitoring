<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Developper;
use App\Entity\User;
use App\Form\DisplayCustomerProfilFormType;
use App\Form\DisplayDevelopperProfilFormType;
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
        $template = $user instanceof Developper ? 'display_user/showDevelopperProfil.html.twig' : 'display_user/showCustomerProfil.html.twig';

        return $this->render($template, [
            'user' => $user,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function editUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Determine the user type and throw an exception if it's not valid
        if (!$user instanceof Developper && !$user instanceof Customer) {
            error_log('User ID: ' . $user->getId() . ' is not a Developper or Customer');
            throw $this->createNotFoundException('L\'utilisateur n\'est pas un client ou un développeur');
        }

        // Create the appropriate form based on the user type
        $formType = $user instanceof Developper ? DisplayDevelopperProfilFormType::class : DisplayCustomerProfilFormType::class;
        $form = $this->createForm($formType, $user);
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

        // Determine the Twig template based on the user type
        $template = $user instanceof Developper ? 'display_user/editDevelopper.html.twig' : 'display_user/editCustomer.html.twig';

        return $this->render($template, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
