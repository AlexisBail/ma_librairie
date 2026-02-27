<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Produit; // Assure-toi que cette entité existe
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // 1. Création de 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->unique()->safeEmail());
            // Tu peux définir un mot de passe par défaut
            $user->setPassword('password123'); 
            $manager->persist($user);
        }

        // 2. Création de 15 produits
        for ($i = 0; $i < 15; $i++) {
            $produit = new Produit();
            $produit->setTitre($faker->word()); // Nom du produit
            $produit->setPrix($faker->randomFloat(2, 5, 100)); // Prix entre 5 et 100
            $manager->persist($produit);
        }

        $manager->flush();
    }
}