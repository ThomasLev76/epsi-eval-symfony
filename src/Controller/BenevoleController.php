<?php

namespace App\Controller;

use App\Entity\Benevole;
use App\Form\BenevoleType;
use App\Repository\BenevoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/benevole')]
final class BenevoleController extends AbstractController
{
    #[Route(name: 'app_benevole_index', methods: ['GET'])]
    public function index(BenevoleRepository $benevoleRepository): Response
    {
        return $this->render('benevole/index.html.twig', [
            'benevoles' => $benevoleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_benevole_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $benevole = new Benevole();
        $form = $this->createForm(BenevoleType::class, $benevole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($benevole);
            $entityManager->flush();

            return $this->redirectToRoute('app_benevole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('benevole/new.html.twig', [
            'benevole' => $benevole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_benevole_show', methods: ['GET'])]
    public function show(Benevole $benevole): Response
    {
        return $this->render('benevole/show.html.twig', [
            'benevole' => $benevole,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_benevole_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Benevole $benevole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BenevoleType::class, $benevole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_benevole_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('benevole/edit.html.twig', [
            'benevole' => $benevole,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_benevole_delete', methods: ['POST'])]
    public function delete(Request $request, Benevole $benevole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$benevole->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($benevole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_benevole_index', [], Response::HTTP_SEE_OTHER);
    }
}
