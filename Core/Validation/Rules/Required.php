<?php

namespace Core\Validation\Rules;

class Required extends \Core\Validation\Rule
{
    /** @var string */
    public $message = "The :attribute is required";

    /**
     * Check the $_value is valid
     *
     * @param mixed $_value
     * @return bool
     */
    public function check($_array, $_key): bool
    {
        if(isset($_array[$_key]))
        {
            return True;
        }
        
        return False;
    }
}