<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\ModuleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    // Route pour ajouter ou éditer une catégorie
    #[Route('/categorie/new', name: 'new_categorie')]
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //Si catégorie est null, alors on créé un objet de type Catégorie
        $isNew = !$categorie;
        if ($isNew) {
            $categorie = new Categorie();
        }   

        $form = $this->createForm(CategorieType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorie = $form -> getData();
            // Prepare PDO
            $entityManager->persist($categorie);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie');
        }

        return $this->render('categorie/new.html.twig', [
            'formAddCategorie' => $form,
            'categorie' => $categorie->getId(),
            'isNew' => $isNew
        ]);
        
    }

    // Route pour supprimer une catégorie
    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function delete(Categorie $categorie = null, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($categorie);
        $entityManager->flush();

        return $this->redirectToRoute(('app_categorie'));
    }

    #[Route('/categorie', name: 'app_categorie')]
    #[Route('/categorie/{id}', name: 'infos_categorie')]
    public function index(Request $request, PaginatorInterface $paginator, CategorieRepository $categorieRepository, Categorie $categorie = null, ModuleRepository $moduleRepository): Response
    {   
        if ($categorie == null) {
            $categories = $paginator->paginate(
                $categorieRepository->findBy([], ['nom' => 'ASC']),
                $request->query->getInt('page', 1),
                10
            );
            return $this->render('categorie/index.html.twig', [
                'categories' => $categories,
                'categorie' => $categorie
            ]);
        } else {
            $modulesCategorie = $moduleRepository->findBy(['categorie' => $categorie], ['nom' => 'ASC']);
            return $this->render('categorie/index.html.twig', [
                'categorie' => $categorie,
                'modulesCategorie' => $modulesCategorie
            ]);
        }
    }
}
