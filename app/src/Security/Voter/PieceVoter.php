<?php

namespace App\Security\Voter;

use App\Entity\Piece;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class PieceVoter extends Voter
{
    public const CREATE = 'CREATE';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::CREATE, self::EDIT, self::DELETE])
            && $subject instanceof Piece;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
            case self::EDIT:
                return $this->canEdit($subject, $user);
            case self::DELETE:
                return $this->canDelete($subject, $user);
        }

        return false;
    }

    private function canCreate(User $user): bool
    {
        return true;
    }

    private function canEdit(Piece $piece, User $user): bool
    {
        $userFriends = $piece->getAuthor()->getFriends();

        return ($userFriends->contains($user)) || ($piece->getAuthor() === $user);
    }

    private function canDelete(Piece $piece, User $user): bool
    {
        return $piece->getAuthor() === $user;
    }
}
