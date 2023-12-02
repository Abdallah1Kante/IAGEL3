<?php

namespace App\Controller;

use App\Entity\Voiture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{id}', name: 'app_annonce')]
    public function index(Voiture $voiture = null): Response
    {
        return $this->render('annonce/index.html.twig', [
            'voiture' => $voiture,
        ]);
    }
}
