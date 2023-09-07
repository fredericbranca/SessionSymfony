<?php

namespace App\Controller;

use App\Entity\Module;
use App\Form\ModuleType;
use App\Repository\ModuleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModuleController extends AbstractController
{
    // Créer / Editer un module
    #[Route('/module/new', name: 'new_module')]
    #[Route('/module/{id}/edit', name: 'edit_module')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function new_edit(Module $module = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        //Si module est null, alors on créé un objet de type Module
        $isNew = !$module;
        if ($isNew) {
            $module = new Module();
        }   

        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $module = $form -> getData();
            // Prepare PDO
            $entityManager->persist($module);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('infos_module', ['id' => $module->getId()] );
        }

        return $this->render('module/new.html.twig', [
            'formAddModule' => $form,
            'module' => $module->getId(),
            'isNew' => $isNew
        ]);
    }

    // Route pour supprimer un module
    #[Route('/module/{id}/delete', name: 'delete_module')]
    #[IsGranted('ROLE_ADMIN', message: 'Accès non autorisé')]
    public function delete(Module $module = null, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($module);
        $entityManager->flush();

        return $this->redirectToRoute(('app_module'));
    }

    // Route pour avoir les modules
    #[Route('/module', name: 'app_module')]
    #[Route('/module/{id}', name: 'infos_module')]
    public function index(Request $request, PaginatorInterface $paginator, ModuleRepository $moduleRepository, Module $module = null): Response
    {
        $modules = $paginator->paginate(
            $moduleRepository->findBy([], ['nom' => 'ASC']),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('module/index.html.twig', [
            'modules' => $modules,
            'module' => $module
        ]);
    }
}
