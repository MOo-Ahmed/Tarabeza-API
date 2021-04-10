<?php

namespace Core\Validation;

abstract class Rule
{
    abstract public function check($_array, $_key): bool;
}