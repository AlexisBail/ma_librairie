<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            EmailField::new('email', 'Email du Membre')
                ->setHelp('Adresse email utilisée pour la connexion'),

            // On affiche le nombre de livres liés à cet utilisateur
            IntegerField::new('produits.count', 'Livres ajoutés')
                ->onlyOnIndex()
                ->setHelp('Nombre total de livres créés par ce membre'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // 1. Création de l'action personnalisée pour voir les livres
        $viewBooks = Action::new('viewBooks', 'Ses Livres', 'fas fa-book-open')
            ->linkToUrl(function (User $user) {
                $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
                
                return $adminUrlGenerator
                    ->setController(ProduitCrudController::class)
                    ->setAction(Action::INDEX)
                    ->set('user_id', $user->getId()) 
                    ->generateUrl();
            })
            ->addCssClass('btn btn-info');

        return $actions
            ->add(Crud::PAGE_INDEX, $viewBooks)
            
            // 2. Modification de l'action MODIFIER
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('Modifier');
            })

            // 3. SÉCURITÉ : Modification de l'action SUPPRIMER
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')
                    ->setLabel('Supprimer')
                    // ON N'AFFICHE LE BOUTON QUE SI LE COMPTE EST VIDE (0 LIVRES)
                    ->displayIf(static function (User $user) {
                        return count($user->getProduits()) === 0;
                    });
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Membre')
            ->setEntityLabelInPlural('Membres de la Librairie')
            ->setPageTitle('index', 'Gestion des Utilisateurs');
    }
}