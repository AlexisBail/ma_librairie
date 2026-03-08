<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')] // C'est cette route que le Dashboard appelle
    public function home(): Response
    {
        // Si l'utilisateur tape juste l'URL de base, on l'envoie sur la page des produits
        return $this->redirectToRoute('app_homepage');
    }

    #[Route('/home', name: 'app_homepage')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('home/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}