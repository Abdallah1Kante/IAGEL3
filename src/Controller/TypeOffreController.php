<?php

namespace App\Controller;

use App\Entity\TypeOffre;
use App\Form\TypeOffreType;
use App\Repository\TypeOffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/offre')]
class TypeOffreController extends AbstractController
{
    #[Route('/', name: 'app_type_offre_index', methods: ['GET'])]
    public function index(TypeOffreRepository $typeOffreRepository): Response
    {
        return $this->render('type_offre/index.html.twig', [
            'type_offres' => $typeOffreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeOffre = new TypeOffre();
        $form = $this->createForm(TypeOffreType::class, $typeOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeOffre);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_offre/new.html.twig', [
            'type_offre' => $typeOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_offre_show', methods: ['GET'])]
    public function show(TypeOffre $typeOffre): Response
    {
        return $this->render('type_offre/show.html.twig', [
            'type_offre' => $typeOffre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeOffre $typeOffre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeOffreType::class, $typeOffre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('type_offre/edit.html.twig', [
            'type_offre' => $typeOffre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_offre_delete', methods: ['POST'])]
    public function delete(Request $request, TypeOffre $typeOffre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeOffre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeOffre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}
