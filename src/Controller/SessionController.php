<?php

namespace App\Controller;

use App\Entity\Session;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Form\SessionType;
use App\Form\ProgrammeType;
use App\Form\StagiaireType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class SessionController extends AbstractController
{
    // Route pour supprimer un programme d'une session
    #[Route('/session/{id}/deleteProgramme/{idProgramme}', name: 'deleteProgramme_session')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
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
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
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
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function newSession(Request $request, 
        EntityManagerInterface $entityManager,
        Session $session = null,
        SessionRepository $sessionRepository
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

            // Récupère le nombre de stagiaire inscrit dans une session
            $nb_stagiaire = $sessionRepository->countStagiairesInscrit($session);
            $nb_disponible = $nb_stagiaire['nb_disponible'];

            $session = $form->getData();
            $nb_place = $session->getNbPlace();
            
            // Empeche la modification sur le nombre de place est inférieurs au nombre de stagiaire déjà inscrits
            if ($nb_place < $nb_disponible) {
                $this->addFlash(
                    'danger',
                    'Le nombre de places ne peut pas être inférieur au nombre de stagiaires déjà inscrits !'
                );
            
                return $this->redirectToRoute('edit_session', ['id' => $session->getId()]);
            }

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
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function delete(
        Session $session, 
        EntityManagerInterface $entityManager
    )
    {
        $entityManager->remove($session);
        $entityManager->flush();

        return $this->redirectToRoute(('app_session'));
    }

    // Route pour afficher la liste un ou plusieurs sessions, liste des stagiaires non inscrit à cette session ainsi que le programme
    #[Route('/session', name: 'app_session')]
    #[Route('/session/{id}', name: 'infos_session')]
    #[Route('/session/{id}/edit/{id_programme}', name: 'edit_jour_programme_session')]
    #[Route('/session/{id}/addStagiaire/{id_stagiaire}', name: 'addStagiaire_session')]
    #[Route('/session/{id}/addStagiaire', name: 'newStagiaire_session')]
    public function index(
        SessionRepository $sessionRepository,
        #[MapEntity(id: 'id')] Session $session = null,
        #[MapEntity(id: 'id_programme')] Programme $programme = null,
        #[MapEntity(id: 'id_stagiaire')] Stagiaire $stagiaire = null,
        Request $request,
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator
    ): Response {
        // S'il n'y a pas de paramètre {id} dans l'url
        if ($session == null) {

            // Requete pagination : on récupère la liste de toutes les sessions (en cours, terminée, à venir) + le nombre de stagiaire inscrit
            $sessionsEnCours = $paginator->paginate(
                $sessionRepository->sessionsEnCoursAndCountStagiairesInscrit(),
                $request->query->getInt('cours', 1),
                2
            );
            $sessionsTerminee = $paginator->paginate(
                $sessionRepository->sessionsTermineeAndCountStagiairesInscrit(),
                $request->query->getInt('terminee', 1),
                2
            );
            $sessionsFuture = $paginator->paginate(
                $sessionRepository->sessionsFutureAndCountStagiairesInscrit(),
                $request->query->getInt('future', 1),
                2
            );

            return $this->render('session/index.html.twig', [
                'enCours' => $sessionsEnCours,
                'terminees' => $sessionsTerminee,
                'future' => $sessionsFuture,
                'session' => $session
            ]);
            
            // Vue d'une session spécifique grâce à son ID
        } else {
            // Créer un stagiaire depuis une session //
            $formNewStagiaire = $this->createForm(StagiaireType::class);
            $formNewStagiaire->handleRequest($request);

            // Si le formulaire est valide
            if ($formNewStagiaire->isSubmitted() && $formNewStagiaire->isValid()) {
                
                $stagiaire = new Stagiaire();
                $stagiaire = $formNewStagiaire->getData();
                // Prepare PDO
                $entityManager->persist($stagiaire);
                // execute PDO
                $entityManager->flush();

                return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
            }

            // Changement du nombre de jour d'un module
            if ($programme) {
                // Uniquement autorisé par l'admin
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                if ($request->isMethod('POST')) {
                    $nbJour = $request->request->get('nbJour');
                    $programme->setNbJour($nbJour);
                    $entityManager->persist($programme);
                    $entityManager->flush();
                }
    
                return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
            }

            // Récupère la liste des modules non inscrit à la session
            $programmes = $sessionRepository->findNonProgrammee($session);

            // Formulaire add module //
            $formProg = $this->createForm(ProgrammeType::class, null, [
                'modules_non_programmes' => $programmes
            ]);
            $formProg->handleRequest($request);

            // Si le formulaire pour ajouter un module existant à la session est validé
            if ($formProg->isSubmitted() && $formProg->isValid()) {
                // Uniquement autorisé par l'admin
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $formData = $formProg->getData();

                $session->addProgramme($formData);
                $entityManager->persist($session);
                $entityManager->flush();
    
                return $this->redirectToRoute('infos_session', ['id' => $session->getId()] );
            }

            // Récupère les infos de la session avec l'ID
            $session = $sessionRepository->findOneBy(['id' => $session]);
            // Récupère la liste des stagiaires non inscrit à la session 
            $stagiaires = $sessionRepository->stagiaireNonInscrits($session);

            // Récupère le nombre de stagiaire inscrit dans une session
            $nb_stagiaire = $sessionRepository->countStagiairesInscrit($session);
            $nb_disponible = $nb_stagiaire['nb_disponible'];

            if ($stagiaire) {
                // Uniquement autorisé par l'admin
                $this->denyAccessUnlessGranted('ROLE_ADMIN');

                $nb_place = $session->getNbPlace();

                // Si le nombre de place est = au nombre de place disponible alors on ne peut plus rajouter de stagiaire dans cette session
                if ($nb_place == $nb_disponible) {
                    return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
                }

                $session->addStagiaire($stagiaire);
                $entityManager->persist($session);
                $entityManager->flush();

                return $this->redirectToRoute('infos_session', ['id' => $session->getId()]);
            }
    

            return $this->render('session/index.html.twig', [
                'session' => $session,
                'nb_disponible' => $nb_disponible,
                'stagiaires' => $stagiaires,
                'programmes' => $programmes,
                'formAddProgramme' => $formProg,
                'formNewStagiaire' => $formNewStagiaire
            ]);
        }
    }
}