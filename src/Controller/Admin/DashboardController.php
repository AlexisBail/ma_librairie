<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\User;
use App\Repository\ProduitRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator; // Vérifie bien cet import !
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    // On ajoute AdminUrlGenerator ici dans le constructeur
    public function __construct(
        private ProduitRepository $produitRepo,
        private UserRepository $userRepo,
        private AdminUrlGenerator $adminUrlGenerator
    ) {}

    public function index(): Response
    {
        // 1. On récupère les paramètres de l'URL
        $crudControllerFqcn = $this->adminUrlGenerator->get('crudControllerFqcn');

        // 2. Si on demande un CRUD (Livres ou Membres), on redirige vers l'URL générée
        if ($crudControllerFqcn) {
            $url = $this->adminUrlGenerator
                ->setController($crudControllerFqcn)
                ->setAction('index')
                ->generateUrl();

            return $this->redirect($url);
        }

        // 3. SINON (si on est juste sur /admin), on affiche tes stats
        $totalPrix = $this->produitRepo->createQueryBuilder('p')
            ->select('SUM(p.prix)')
            ->getQuery()
            ->getSingleScalarResult();

        $stats = [
            'countProduits' => $this->produitRepo->count([]),
            'countUsers' => $this->userRepo->count([]),
            'totalPrix' => $totalPrix ?? 0, 
        ];

        return $this->render('admin/my_dashboard.html.twig', [
            'stats' => $stats,
        ]);
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

        // Utilisation de linkToRoute pour éviter l'erreur "Undefined method"
        yield MenuItem::linkToRoute('Livres', 'fas fa-book', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => \App\Controller\Admin\ProduitCrudController::class,
        ]);

        yield MenuItem::linkToRoute('Membres', 'fas fa-users', 'admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => \App\Controller\Admin\UserCrudController::class,
        ]);

        yield MenuItem::section('Retour');
    
        yield MenuItem::linkToRoute('Quitter l\'admin', 'fas fa-door-open', 'app_home');
    }
}