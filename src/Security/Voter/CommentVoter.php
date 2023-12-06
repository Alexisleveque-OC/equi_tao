<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
	public const DELETE = 'COMMENT_DELETE';

	protected function supports(string $attribute, mixed $subject): bool {
		return in_array($attribute, [self::DELETE])
			&& $subject instanceof Comment;
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		$user = $token->getUser();
		if (!$user instanceof UserInterface) {
			return false;
		}

		switch ($attribute) {
			case self::DELETE:
				dump($subject, $user, $attribute);
				if ($subject->getUser() === $user || in_array('ROLE_ADMIN', $user->getRoles())) {
					return true;
				}
				break;
		}

		return false;
	}
}
