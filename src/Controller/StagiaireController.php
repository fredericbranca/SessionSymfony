<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use App\Repository\SessionRepository;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    // Route pour ajouter ou éditer un stagiarie
    #[Route('/stagiaire/new', name: 'new_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function new_edit(
        Stagiaire $stagiaire = null,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        //Si stagiaire est null, alors on créé un objet de type Stagiaire
        $isNew = !$stagiaire;
        if ($isNew) {
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);

        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $stagiaire = $form->getData();
            // Prepare PDO
            $entityManager->persist($stagiaire);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('infos_stagiaire', ['id' => $stagiaire->getId()]);
        }

        return $this->render('stagiaire/new.html.twig', [
            'formAddStagiaire' => $form,
            'stagiaire' => $stagiaire->getId(),
            'isNew' => $isNew
        ]);
    }

    #[Route('/stagiaire', name: 'app_stagiaire')]
    #[IsGranted('ROLE_USER', message: 'Accès non autorisé')]
    public function index(
        StagiaireRepository $stagiaireRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {

        $stagiaires = $paginator->paginate(
            $stagiaireRepository->findStagiaires(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaires
        ]);
    }

    // Route pour afficher les infos d'un stagiaire
    #[Route('stagiaire/{id}', name: 'infos_stagiaire')]
    #[IsGranted('ROLE_USER', message: 'Accès non autorisé')]
    public function infos(
        Stagiaire $stagiaire,
        SessionRepository $sessionRepository
    ): Response
    {
        $sessionsEnCours = $sessionRepository->sessionsEnCours(null, $stagiaire);
        $sessionsFuture = $sessionRepository->sessionsFuture(null, $stagiaire);
        $sessionsTerminee = $sessionRepository->sessionsTerminee(null, $stagiaire);
        return $this->render('stagiaire/infos.html.twig', [
            'stagiaire' => $stagiaire,
            'sessionsEnCours' => $sessionsEnCours,
            'sessionsFuture' => $sessionsFuture,
            'sessionsTerminee' => $sessionsTerminee
        ]);
    }

    // Route pour supprimer un stagiaire
    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function delete(
        Stagiaire $stagiaire = null, 
        EntityManagerInterface $entityManager
    )
    {
        $entityManager->remove($stagiaire);
        $entityManager->flush();

        return $this->redirectToRoute(('app_stagiaire'));
    }
}
