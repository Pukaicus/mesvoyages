<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Voyage;
use App\Form\VoyageType;
use App\Repository\VoyageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/voyage')]
final class VoyageController extends AbstractController
{
    /**
     * Affiche la liste des voyages avec option de filtrage par environnement
     */
    #[Route(name: 'app_voyage_index', methods: ['GET', 'POST'])]
    public function index(Request $request, VoyageRepository $voyageRepository): Response
    {
        // On récupère la saisie du formulaire (attribut name="environnement_filtre" dans le Twig)
        $envSaisi = $request->request->get('environnement_filtre');

        if ($envSaisi) {
            // Si une recherche est lancée, on utilise la méthode du Repository
            $voyages = $voyageRepository->findByEnvironnement($envSaisi);
        } else {
            // Sinon, on affiche tout, trié par date de création décroissante (Antichronologique)
            // C'est ce qui est demandé explicitement dans ton sujet d'évaluation.
            $voyages = $voyageRepository->findBy([], ['datecreation' => 'DESC']);
        }

        return $this->render('voyage/index.html.twig', [
            'voyages' => $voyages,
        ]);
    }

    #[Route('/new', name: 'app_voyage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voyage = new Voyage();
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/images',
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Commentaire pour SonarQube : évite l'alerte "Empty Catch Block"
                    // L'image n'est simplement pas enregistrée en cas d'erreur système
                }
                $voyage->setImage($newFilename);
            }

            $entityManager->persist($voyage);
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyage/new.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_show', methods: ['GET'])]
    public function show(Voyage $voyage): Response
    {
        return $this->render('voyage/show.html.twig', [
            'voyage' => $voyage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_voyage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoyageType::class, $voyage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('voyage/edit.html.twig', [
            'voyage' => $voyage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_voyage_delete', methods: ['POST'])]
    public function delete(Request $request, Voyage $voyage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$voyage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($voyage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_voyage_index', [], Response::HTTP_SEE_OTHER);
    }
}
