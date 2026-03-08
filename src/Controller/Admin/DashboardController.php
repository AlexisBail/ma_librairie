<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Redirection directe vers la liste des produits
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ProduitCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Ma Librairie - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil Admin', 'fa fa-home');
    
        yield MenuItem::section('Boutique');

        yield MenuItem::linkToRoute('Livres', 'fas fa-book', 'admin', [
        'crudAction' => 'index',
        'crudControllerFqcn' => \App\Controller\Admin\ProduitCrudController::class,
        ]);

        yield MenuItem::linkToRoute('Membres', 'fas fa-users', 'admin', [
        'crudAction' => 'index',
        'crudControllerFqcn' => \App\Controller\Admin\UserCrudController::class,
        ]);

        yield MenuItem::section('Retour');
    
        // MODIFICATION ICI : On pointe vers 'app_home' (ou la route de ta page d'accueil)
        // On retire le lien interne à l'admin pour forcer la sortie vers le vrai site public
        yield MenuItem::linkToRoute('Quitter l\'admin', 'fas fa-door-open', 'app_home');
    }
}