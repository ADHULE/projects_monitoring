<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddUserRoleController extends AbstractController
{
    #[Route('/admin/{id}/edit/role', name: 'app_developpeur_edit_role', methods: ['GET', 'POST'])]
    public function editRole(Request $request, User $developpeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddUserRoleType::class, $developpeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les rôles du formulaire
            $roles = $form->get('roles')->getData();
            $developpeur->setRoles($roles);

            $entityManager->persist($developpeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_developpeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('add_user_role/index.html.twig', [
            'developpeur' => $developpeur,
            'form' => $form->createView(),
        ]);
    }
}
