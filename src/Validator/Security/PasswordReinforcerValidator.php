<?php

namespace App\Validator\Security;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordReinforcerValidator extends ConstraintValidator
{

	public function validate(mixed $value, Constraint $constraint) {

		if (!$constraint instanceof PasswordReinforcer) {
			throw new UnexpectedTypeException($constraint, OldPasswordMatch::class);
		}

		if (!preg_match('/[A-Z]+/', $value)){
			$this->context->buildViolation($constraint->messageNoMaj)
				->addViolation();
		}

		if (!preg_match('/[a-z]+/', $value)){
			$this->context->buildViolation($constraint->messageNoMin)
				->addViolation();
		}

		if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>§]+/', $value)){
			$this->context->buildViolation($constraint->messageNoSpecialCharacter)
				->addViolation();
		}

		if (!preg_match('/[0-9]+/', $value)){
			$this->context->buildViolation($constraint->messageNoNumber)
				->addViolation();
		}

		if (strlen($value) < 8){
			$this->context->buildViolation($constraint->messageToLess)
				->addViolation();
		}

		if (strlen($value) > 100){
			$this->context->buildViolation($constraint->messageToLong)
				->addViolation();
		}

		if ($this->context->getViolations()->count() > 0){
			return false;
		}

		return true;
	}

}