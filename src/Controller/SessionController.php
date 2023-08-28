<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SessionController extends AbstractController
{
    // Route pour ajouter un stagiaire à une session
    #[Route('/session/{id}/addStagiaire/{id_stagiaire}', name: 'addStagiaire_session')]
    public function addStagiaire(
        #[MapEntity(id: 'id')] Session $session,
        #[MapEntity(id: 'id_stagiaire')] Stagiaire $stagiaire,
        EntityManagerInterface $entityManager
    ): Response {

        $session->addStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
    }

    // Route pour supprimer un programme d'une session
    #[Route('/session/{id}/deleteProgramme/{idProgramme}', name: 'deleteProgramme_session')]
    public function deleteProgramme(
        #[MapEntity(id: 'id')] Session $session,
        #[MapEntity(id: 'idProgramme')] Programme $programme,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($programme);
        $entityManager->flush();

        return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
    }

    // Route pour supprimer un stagiaire d'une session
    #[Route('/session/{id}/deleteStagiaire/{idStagiaire}', name: 'deleteStagiaire_session')]
    public function deleteStagiaire(
        #[MapEntity(id: 'id')] Session $session,
        #[MapEntity(id: 'idStagiaire')] Stagiaire $stagiaire,
        EntityManagerInterface $entityManager
    ): Response {
        $session->removeStagiaire($stagiaire);
        $entityManager->persist($session);
        $entityManager->flush();

        return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
    }

    // Route pour afficher la liste un ou plusieurs sessions, liste des stagiaires non inscrit à cette session ainsi que les programmes
    #[Route('/session', name: 'app_session')]
    #[Route('/session/{id}', name: 'infos_session')]
    public function index(
        SessionRepository $sessionRepository,
        Session $session = null,
        Programme $programme,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($session == null) {
            $sessionsEnCours = $sessionRepository->sessionsEnCours();
            $sessionsTerminee = $sessionRepository->sessionsTerminee();
            $sessionsFuture = $sessionRepository->sessionsFuture();
            return $this->render('session/index.html.twig', [
                'enCours' => $sessionsEnCours,
                'terminees' => $sessionsTerminee,
                'future' => $sessionsFuture,
                'session' => $session
            ]);
        } else {
            $isNew = !$programme;
            if ($isNew) {
                $programme = new Programme();
            }   
            // Formulaire add programmes
            $formProg = $this->createForm(ProgrammeType::class, $programme);
            $formProg->handleRequest($request);

            if ($formProg->isSubmitted() && $formProg->isValid()) {

                $session->addProgramme($programme);
                $entityManager->persist($session);
                $entityManager->flush();
    
                return $this->redirectToRoute('infos_session', ['id' => $session->getId()] );
            }

            $session = $sessionRepository->findOneBy(['id' => $session]);
            $stagiaires = $sessionRepository->findNonInscrits($session);
            $programmes = $sessionRepository->findNonProgrammee($session);
            // dd($programmes);

            return $this->render('session/index.html.twig', [
                'session' => $session,
                'stagiaires' => $stagiaires,
                'programmes' => $programmes,
                'formAddProgramme' => $formProg
            ]);
        }
    }
}
