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

    // Route pour créer une session
    #[Route('/session/new', 'new_session')]
    #[Route('/session/{id}/edit', name: 'edit_session')]
    public function newSession(Request $request, 
    EntityManagerInterface $entityManager,
    Session $session = null
): Response
    {
        //Si session est null, alors on créé un objet de type Session
        $isNew = !$session;
        if ($isNew) {
            $session = new Session();
        }

        $form = $this->createForm(SessionType::class, $session);

        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $session = $form->getData();
            $entityManager->persist($session);
            $entityManager->flush();

            return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
        }

        return $this->render('session/new_edit.html.twig', [
            'formAddSession' => $form,
            'session' => $session,
            'isNew' => $isNew
        ]);
    }

    // Route pour supprimer un session
    #[Route('/session/{id}/delete', name: 'delete_session')]
    public function delete(
        Session $session, 
        EntityManagerInterface $entityManager
    )
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute(('app_session'));
    }

    // Route pour afficher la liste un ou plusieurs sessions, liste des stagiaires non inscrit à cette session ainsi que les programmes
    #[Route('/session', name: 'app_session')]
    #[Route('/session/{id}', name: 'infos_session')]
    #[Route('/session/{id}/edit/{id_programme}', name: 'edit_jour_programme_session')]
    public function index(
        SessionRepository $sessionRepository,
        #[MapEntity(id: 'id')] Session $session = null,
        #[MapEntity(id: 'id_programme')] Programme $programme = null,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // S'il n'y a pas de paramètre {id} dans l'url
        if ($session == null) {

            // Requete : on récupère la liste de toutes les sessions (en cours, terminée, à venir)
            $sessionsEnCours = $sessionRepository->sessionsEnCours();
            $sessionsTerminee = $sessionRepository->sessionsTerminee();
            $sessionsFuture = $sessionRepository->sessionsFuture();
            return $this->render('session/index.html.twig', [
                'enCours' => $sessionsEnCours,
                'terminees' => $sessionsTerminee,
                'future' => $sessionsFuture,
                'session' => $session
            ]);
            
            // Vue d'une session spécifique grâce à son ID
        } else {
            // Changement du nombre de jour d'un programme
            if ($programme) {

                if ($request->isMethod('POST')) {
                    $nbJour = $request->request->get('nbJour');
                    $programme->setNbJour($nbJour);
                    $entityManager->persist($programme);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
            }

            // Récupère la liste des programmes non inscrit à la session
            $programmes = $sessionRepository->findNonProgrammee($session);

            // Formulaire add programmes //
            $formProg = $this->createForm(ProgrammeType::class, null, [
                'modules_non_programmes' => $programmes
            ]);
            $formProg->handleRequest($request);

            // Si le formulaire pour ajouter un programme existant à la session est validé
            if ($formProg->isSubmitted() && $formProg->isValid()) {
                $formData = $formProg->getData();

                $session->addProgramme($formData);
                $entityManager->persist($session);
                $entityManager->flush();
    
                return $this->redirectToRoute('infos_session', ['id' => $session->getId()] );
            }

            // Récupère les infos de la session avec l'ID
            $session = $sessionRepository->findOneBy(['id' => $session]);
            // Récupère la liste des stagiaires non inscrit à la session 
            $stagiaires = $sessionRepository->findNonInscrits($session);
            // $stagiaires = $sessionRepository->stagiaireNonInscrits($session);

            return $this->render('session/index.html.twig', [
                'session' => $session,
                'stagiaires' => $stagiaires,
                'programmes' => $programmes,
                'formAddProgramme' => $formProg
            ]);
        }
    }
}