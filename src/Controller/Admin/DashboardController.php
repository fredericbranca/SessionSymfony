<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Module;
use App\Entity\Session;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Programme;
use App\Entity\Stagiaire;
use App\Controller\SessionController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(SessionCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SessionSymfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Session', 'fas fa-calendar', Session::class);
        yield MenuItem::linkToCrud('Formation', 'fas fa-graduation-cap', Formation::class);
        yield MenuItem::linkToCrud('Module', 'fas fa-cubes', Module::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-tasks', Categorie::class);
        yield MenuItem::linkToCrud('Programme', 'fas fa-square', Programme::class);
        yield MenuItem::linkToCrud('Stagiaire', 'fas fa-address-book', Stagiaire::class);
        yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
    }
}
