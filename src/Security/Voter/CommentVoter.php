<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
	public const DELETE = 'COMMENT_DELETE';
	public const CREATE = 'COMMENT_CREATE';

	protected function supports(string $attribute, mixed $subject): bool {
		if($subject === null && in_array($attribute, [ self::CREATE])) {
			return true;
		}

		return in_array($attribute, [self::DELETE, self::CREATE])
			&& $subject instanceof Comment;
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
		$user = $token->getUser();
		if (!$user instanceof UserInterface) {
			return false;
		}

		switch ($attribute) {
			case self::DELETE:
				if ($subject->getUser() === $user || in_array('ROLE_ADMIN', $user->getRoles())) {
					return true;
				}
				break;
			case self::CREATE:
				if (in_array('ROLE_USER', $user->getRoles())) {
					return true;
				}
				break;
		}

		return false;
	}
}
