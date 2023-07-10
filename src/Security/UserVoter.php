<?php

namespace App\Security;


use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const EDIT = "edit";

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var User $post */
        $user = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($user, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }


    private function canEdit(User $user): bool
    {
        // this assumes that the Post object has a `getOwner()` method
        return $user === $user->getOwner();
    }
}