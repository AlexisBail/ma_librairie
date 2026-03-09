<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    // Cette méthode remplit automatiquement l'utilisateur connecté
    public function createEntity(string $entityFqcn): object
    {
        $produit = new $entityFqcn();
        $produit->setUser($this->getUser());
    
        return $produit;
    }   

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre', 'Titre du livre'),
            NumberField::new('prix', 'Prix (€)'),
        ];
    }
}