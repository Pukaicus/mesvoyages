<?php

namespace App\Controller;
use App\Repository\VoyageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(VoyageRepository $repository): Response
    {
        $derniersVoyages = $repository->findLastTwo();

        return $this->render('accueil/index.html.twig', [
            'voyages' => $derniersVoyages,
        ]);
    }
}
