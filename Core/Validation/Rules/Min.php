<?php

namespace Core\Validation\Rules;

class Min extends \Core\Validation\Rule
{
    /** @var string */
    public $message = "The :attribute minimum is :param";

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($_array, $_key): bool
    {}

    public function checkWithParam($_array, $_key, $_param): bool
    {
        if(isset($_array[$_key]))
        {
            if(is_string($_array[$_key]))
                return strlen($_array[$_key]) >= (int) $_param;
        }
        
        return True;
    }
}