<?php
namespace App\Controller;
use App\Entity\Developper;
use App\Form\DevelopperType;
use App\Repository\DevelopperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/developper')]
class DevelopperController extends AbstractController
{
    #[Route('/', name: 'app_developper_index', methods: ['GET'])]
    public function index(DevelopperRepository $developperRepository): Response
    {
        return $this->render('developper/index.html.twig', [
            'developpers' => $developperRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_developper_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Developper $developper, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DevelopperType::class, $developper);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_developper_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('developper/edit.html.twig', [
            'developper' => $developper,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_developper_delete', methods: ['POST'])]
    public function delete(Request $request, Developper $developper, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$developper->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($developper);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_developper_index', [], Response::HTTP_SEE_OTHER);
    }
}
