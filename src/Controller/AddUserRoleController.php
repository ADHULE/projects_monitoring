<?php
namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Developper;
use App\Entity\User;
use App\Form\AddCustomerRole;
use App\Form\AddDevelopperRole;
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
         // Determine the user type and throw an exception if it's not valid
         if (!$user instanceof Developper && !$user instanceof Customer) {
            error_log('User ID: ' . $user->getId() . ' is not a Developper or Customer');
            throw $this->createNotFoundException('L\'utilisateur n\'est pas un client ou un développeur');
        }

         // Create the appropriate form based on the user type
         $formType = $user instanceof Developper ? AddDevelopperRole::class : AddCustomerRole::class;
         $form = $this->createForm($formType, $user);
         $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les rôles du formulaire
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        // Determine the Twig template based on the user type
        $template = $user instanceof Developper ? 'add_user_role/addDevelopperRole.html.twig' : 'add_user_role/addCustomerRole.html.twig';

        return $this->render($template, [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }


    
}
