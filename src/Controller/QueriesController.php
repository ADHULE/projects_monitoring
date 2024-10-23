<?php

namespace App\Controller;

use App\Entity\Queries;
use App\Form\QueriesType;
use App\Repository\QueriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/queries')]
class QueriesController extends AbstractController
{
    #[Route('/', name: 'app_queries_index', methods: ['GET'])]
    public function index(QueriesRepository $queriesRepository): Response
    {
        return $this->render('queries/index.html.twig', [
            'queries' => $queriesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_queries_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $query = new Queries();
        $form = $this->createForm(QueriesType::class, $query);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($query);
            $entityManager->flush();

            return $this->redirectToRoute('app_queries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('queries/new.html.twig', [
            'query' => $query,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_queries_show', methods: ['GET'])]
    public function show(Queries $query): Response
    {
        return $this->render('queries/show.html.twig', [
            'query' => $query,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_queries_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Queries $query, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QueriesType::class, $query);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_queries_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('queries/edit.html.twig', [
            'query' => $query,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_queries_delete', methods: ['POST'])]
    public function delete(Request $request, Queries $query, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$query->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($query);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_queries_index', [], Response::HTTP_SEE_OTHER);
    }
}
