<?php

namespace App\Controller;

use App\Entity\Contrat;
use App\Form\ContratType;
use App\Repository\ContratRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/contrat')]
class ContratController extends AbstractController
{
    #[Route('/', name: 'app_contrat_index', methods: ['GET'])]
    public function index(ContratRepository $contratRepository): Response
    {
        return $this->render('contrat/index.html.twig', [
            'contrats' => $contratRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contrat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contrat = new Contrat();
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contrat ->setDateContrat(new DateTime());
            if($contrat -> getTypeOffre()->getNom() == "Vente"){
                if(!$contrat -> getVoiture()->isDisponibilite()){
                    $this->addFlash("error", "Cette voiture n'est pas disponible pour une vente.");
                }else{

                    $entityManager->persist($contrat);
                    $voiture = $contrat->getVoiture();
                    $voiture->setDisponibilite(false);
                    $entityManager->persist($voiture);
                    $entityManager->flush();
                    $this->addFlash("success", "Contrat ajouté avec succès!");
                    $this->addFlash("success", "La disponibilité de la voiture $voiture a été modifié avec succès!");
                    return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
                }
            }else{
                if(!$contrat -> getVoiture()->isDisponibilite()){
                    $this->addFlash("error", "Cette voiture n'est pas disponible pour une location.");
                }else{
                    if($contrat -> getDateDebut() >= $contrat->getDateFin()){
                        $this->addFlash("error", "La date de début ne peux pas etre postérieur ou egale à  la date de fin.");
                    }else{
                        $entityManager->persist($contrat);
                        
                        $voiture = $contrat->getVoiture();
                        $voiture->setDisponibilite(false);
                        $entityManager->persist($voiture);
                        $entityManager->flush();
                        $this->addFlash("success", "Contrat ajouté avec succès!");
                        $this->addFlash("success", "La disponibilité de la voiture $voiture a été modifié avec succès!");
                        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
                    }
                    
                }
            }
            

            
        }

        return $this->renderForm('contrat/new.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_show', methods: ['GET'])]
    public function show(Contrat $contrat): Response
    {
        return $this->render('contrat/show.html.twig', [
            'contrat' => $contrat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contrat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContratType::class, $contrat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contrat/edit.html.twig', [
            'contrat' => $contrat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contrat_delete', methods: ['POST'])]
    public function delete(Request $request, Contrat $contrat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contrat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($contrat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contrat_index', [], Response::HTTP_SEE_OTHER);
    }
}
