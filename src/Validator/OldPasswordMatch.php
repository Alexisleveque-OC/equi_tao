<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;

class OldPasswordMatch extends Constraint
{
	public string $message = "L'ancien mot de passe ne correspond pas!";
	public User $user;

	public function __construct(User $user = null, string $message = null, array $groups = null, $payload = null) {
		parent::__construct([], $groups, $payload);

		$this->user = $user ?? $this->user;
		$this->message = $message ?? $this->message;
	}

	public function getDefaultOption() {
		return 'user';
	}
}