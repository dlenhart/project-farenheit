<?php
namespace APP\Validation;

// DDL - validator using Respect.
// Codecourse was very helpfull in creating this.
// Credit goes to the author of that course.
// https://codecourse.com/watch/slim-3-authentication

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    protected $errors;
    public function validate($request, array $rules)
    {
        foreach ($rules as $field => $rule) {
            try {
                $rule->setName($field)->assert($request->getParam($field));
            } catch (NestedValidationException $e) {
                $this->errors[$field] = $e->getMessages();
            }
        }

        //set session errors
        $_SESSION['ERRORS'] = $this->errors;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }

    public function messages()
    {
        return $this->errors;
    }
}
