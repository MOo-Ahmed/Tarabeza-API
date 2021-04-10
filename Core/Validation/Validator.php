<?php

namespace Core\Validation;

class Validator
{
    /** @var ErrorBag */
    public $error;

    /** @var array */
    protected $validators = [];

    /**
     * Constructor
     *
     * @param array $messages
     * @return void
     */
    public function __construct()
    {
        // Initialize base validators array
        $baseValidator = [
            "required" => new \Core\Validation\Rules\Required,
            "min" => new \Core\Validation\Rules\Min
        ];

        foreach ($baseValidator as $key => $validator)
        {
            $this->setValidator($key, $validator);
        }
    }

    
    /**
     * Validate $inputs
     *
     * @param array $inputs
     * @param array $rules
     * @return bool
     */
    public function isValid(array $_inputs, array $_rules): bool
    {
        foreach($_rules as $key => $value)
        {
            $rules = explode('|', $value);

            foreach($rules as $rule)
            {
                $pos = strpos($rule, ':');
                if($pos == false)
                {
                    $va = new $this->validators[$rule];
                    if(!$va->check($_inputs, $key))
                    {
                        $key = str_replace('_', ' ',  $key);
                        $message = str_replace(":attribute", $key, $va->message);
                        $this->error = $message;
                        return False;
                    }
                }
                else
                {
                    $ruleArr = explode(':', $rule);
                    $ruleName = $ruleArr[0];
                    $param = $ruleArr[1];

                    $va = new $this->validators[$ruleName];

                    if(!$va->checkWithParam($_inputs, $key, $param))
                    {
                        $key = str_replace('_', ' ',  $key);
                        $message = str_replace(":attribute", $key, $va->message);
                        $message = str_replace(":param", $param, $message);
                        $this->error = $message;
                        return False;
                    }
                }
            }
        }

        return True;
    }

    /**
     * Register or override existing validator
     *
     * @param mixed $key
     * @param \Rakit\Validation\Rule $rule
     * @return void
     */
    public function setValidator(string $key, Rule $rule)
    {
        $this->validators[$key] = $rule;
    }

    public function getError()
    {
        return $this->error;
    }
}
