<?php

namespace App\Security\Voter;

use App\Entity\Produit;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\Vote; // AJOUT DE CET IMPORT
use Symfony\Component\Security\Core\User\UserInterface;

final class ProduitVoter extends Voter
{
    public const EDIT = 'PRODUIT_EDIT';
    public const DELETE = 'PRODUIT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Produit;
    }

    // MODIFICATION DE LA SIGNATURE CI-DESSOUS
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        // RÈGLE : Si l'utilisateur est ADMIN, on lui accorde tout de suite le droit
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        /** @var Produit $produit */
        $produit = $subject;

        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                // Le USER peut modifier ou supprimer SEULEMENT s'il est le propriétaire
                return $produit->getUser() === $user;
        }

        return false;
    }
}