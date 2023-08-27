<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SessionController extends AbstractController
{
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

    // Route pour afficher la liste un ou plusieurs sessions
    #[Route('/session', name: 'app_session')]
    #[Route('/session/{id}', name: 'infos_session')]
    public function index(SessionRepository $sessionRepository, Session $session = null): Response
    {
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
            $session = $sessionRepository->findOneBy(['id' => $session]);
            return $this->render('session/index.html.twig', [
                'session' => $session
            ]);
        }
    }
}
