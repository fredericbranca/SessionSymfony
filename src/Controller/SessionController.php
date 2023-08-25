<?php

namespace App\Controller;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
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