<?php

/*
 * This handles validation of a University, used in the UniversityController
 * It is pretty basic in nature, as our "admin" users are trustworthy.
 * Hence checking for only "@" for verifying emails
 *
 * */

use App\Models\University;

class UniversityValidator
{
    public function validateName($name)
    {
        return is_string($name) && strlen($name) <= 255;
    }

    public function validateEmailDomain($emailDomain)
    {
        return is_string($emailDomain) && strlen($emailDomain) <= 255 && strpos($emailDomain, '@') === 0;
    }

    public function validateAll(University $university)
    {
        $errors = [];

        if (!$this->validateName($university['name'])) {
            $errors['name'] = 'University name is invalid.';
        }

        if (!$this->validateEmailDomain($university['email_domain'])) {
            $errors['email_domain'] = 'Email domain is invalid. It should start with @.';
        }

        return $errors;
    }
}
