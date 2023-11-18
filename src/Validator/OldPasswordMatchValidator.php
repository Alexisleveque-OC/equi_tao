<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Service\Attribute\Required;

class OldPasswordMatchValidator extends ConstraintValidator
{

	#[Required]
	public UserPasswordHasherInterface $passwordHasher;

	/**
	 * @inheritDoc
	 */
	public function validate(mixed $value, Constraint $constraint) {

		dump($constraint->user);
		if (!$constraint instanceof OldPasswordMatch) {
			throw new UnexpectedTypeException($constraint, OldPasswordMatch::class);
		}

		$user = $constraint->user;

		if (!$this->passwordHasher->isPasswordValid($user, $value)){
			$this->context->buildViolation($constraint->message)
				->addViolation();
			return false;
		}
		return true;
	}
}