<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\SessionRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    // Route pour ajouter ou éditer une formation
    #[Route('/formation/new', name: 'new_formation')]
    #[Route('/formation/{id}/edit', name: 'edit_formation')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function new_edit(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //Si formation est null, alors on créé un objet de type Formation
        $isNew = !$formation;
        if ($isNew) {
            $formation = new Formation();
        }

        $form = $this->createForm(FormationType::class, $formation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $formation = $form->getData();
            // Prepare PDO
            $entityManager->persist($formation);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_formation');
        }

        return $this->render('formation/new.html.twig', [
            'formAddFormation' => $form,
            'formation' => $formation->getId(),
            'isNew' => $isNew
        ]);

    }

    // Route pour supprimer une formation
    #[Route('/formation/{id}/delete', name: 'delete_formation')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function delete(Formation $formation = null, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($formation);
        $entityManager->flush();

        return $this->redirectToRoute(('app_formation'));
    }

    #[Route('/formation', name: 'app_formation')]
    #[Route('/formation/{id}', name: 'infos_formation')]
    public function index(Formation $formation = null, FormationRepository $formationRepository, SessionRepository $sessionRepository): Response
    {
        if ($formation == null) {
            $formations = $formationRepository->findBy([], ['nom' => 'ASC']);
            return $this->render('formation/index.html.twig', [
                'formations' => $formations,
                'formation' => $formation
            ]);
        } else {
            $sessionsFormation = $sessionRepository->findBy(['formation' => $formation]);
            return $this->render('formation/index.html.twig', [
                'formation' => $formation,
                'sessionsFormation' => $sessionsFormation
            ]);
        }
    }
}