<?php

namespace Discutea\UserBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{
    /**
     * @var array
     */
    private $configuration;

    /**
     * PasswordValidator constructor.
     * @param array $discuteaUserConfig
     */
    public function __construct(array $discuteaUserConfig)
    {
        $this->configuration = $discuteaUserConfig;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!isset($this->configuration['password_validation'])) {
            return;
        }


        foreach ($this->configuration['password_validation'] as $key => $val) {
            if ($val > 0) {
                $method = 'validate' . str_replace('_', '', ucwords(strtolower($key), '_'));

                if (method_exists($this, $method)) {
                    $this->$method($val);
                }
            }
        }


        /* @var $constraint \App\Validator\Password */

        if (null === $value || '' === $value) {
            return;
        }

        // TODO: implement the validation here
//        $this->context->buildViolation($constraint->message)
//            ->setParameter('{{ value }}', $value)
//            ->addViolation();
    }

    private function validateMinLength()
    {

    }
}
