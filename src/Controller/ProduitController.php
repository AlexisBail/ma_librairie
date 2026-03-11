<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted; // L'import indispensable

#[Route('/produit')]
final class ProduitController extends AbstractController
{
    // 1. Liste des produits (Accessible à TOUS)
    #[Route('/', name: 'app_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    // 2. Ajout d'un produit (RÉSERVÉ AUX CONNECTÉS)
    #[Route('/nouveau', name: 'produit_nouveau', methods: ['GET','POST'])]
    #[IsGranted('ROLE_USER')] // Bloque l'accès si pas de compte connecté
    public function nouveau(Request $request, EntityManagerInterface $em): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // L'utilisateur connecté devient le propriétaire
            $produit->setUser($this->getUser());
            
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('app_produit');
        }

        return $this->render('produit/nouveau.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // 3. Suppression d'un produit (RÉSERVÉ AUX CONNECTÉS + VOTER)
    #[Route('/supprimer/{id}', name: 'produit_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')] // Sécurité supplémentaire de base
    public function supprimer(Produit $produit, EntityManagerInterface $em, Request $request): Response
    {
        // On demande au ProduitVoter si l'utilisateur a le droit
        $this->denyAccessUnlessGranted('PRODUIT_DELETE', $produit);

        // On vérifie le jeton CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('app_produit');
    }
}