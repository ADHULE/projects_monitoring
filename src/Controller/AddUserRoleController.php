<?php

namespace App\Controller;

use App\Entity\Developper;
use App\Entity\User;
use App\Form\AddUserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddUserRoleController extends AbstractController
{
    #[Route('/admin/{id}/edit/role', name: 'app_developpeur_edit_role', methods: ['GET', 'POST'])]
    public function editRole(Request $request, Developper $developper, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddUserRoleType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les rôles du formulaire
            $roles = $form->get('roles')->getData();
            $developper->setRoles($roles);
            $entityManager->persist($developper);
            $entityManager->flush();
            return $this->redirectToRoute('app_developper_index', ['id' => $developper->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('add_user_role/index.html.twig', [
            'developper' => $developper,
            'form' => $form,
        ]);
    }
}
