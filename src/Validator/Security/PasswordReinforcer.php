<?php

namespace App\Validator\Security;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;

class PasswordReinforcer extends Constraint
{
	public string $messageNoMaj = "Le mot de passe doit contenir au moins une majuscule!";
	public string $messageNoMin = "Le mot de passe doit contenir au moins une minuscule!";
	public string $messageToLess = "Le mot de passe est trop court et doit contenir au moins 8 caractères!";
	public string $messageToLong = "Le mot de passe est trop long et doit contenir au maximum 100 caractères!";
	public string $messageNoSpecialCharacter = "Le mot de passe doit contenir au moins un caractère spécial!";
	public string $messageNoNumber = "Le mot de passe doit contenir au moins un chiffre!";


	public function __construct($options = null ,string $message = null, array $groups = null, $payload = null) {
		parent::__construct([], $groups, $payload);

		$this->messageNoMaj = $message ?? $this->messageNoMaj;
		$this->messageNoMin = $message ?? $this->messageNoMin;
		$this->messageToLess = $message ?? $this->messageToLess;
		$this->messageToLong = $message ?? $this->messageToLong;
		$this->messageNoSpecialCharacter = $message ?? $this->messageNoSpecialCharacter;
		$this->messageNoNumber = $message ?? $this->messageNoNumber;
		parent::__construct($options);

	}


}