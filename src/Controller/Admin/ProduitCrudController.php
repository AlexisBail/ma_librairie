<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\QueryBuilder;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    /**
     * Cette méthode permet de filtrer la liste des livres
     * si un "user_id" est présent dans l'URL.
     */
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        
        // On récupère l'ID de l'utilisateur depuis l'URL (envoyé par UserCrudController)
        $userId = $this->getContext()->getRequest()->query->get('user_id');

        if ($userId) {
            // "entity.user" correspond à la propriété $user dans ton entité Produit
            $qb->andWhere('entity.user = :userId')
               ->setParameter('userId', $userId);
        }

        return $qb;
    }

    public function createEntity(string $entityFqcn): object
    {
        $produit = new $entityFqcn();
        // On lie automatiquement le produit à l'admin connecté lors de la création manuelle
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