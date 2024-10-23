<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\AddUserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddUserRoleController extends AbstractController
{
    #[Route('/admin/{id}/edit/role', name: 'app_user_edit_role', methods: ['GET', 'POST'])]
    public function editRole(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddUserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les rôles du formulaire
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_home', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('add_user_role/index.html.twig', [
            'users' => $user,
            'form' => $form,
        ]);
    }
}
