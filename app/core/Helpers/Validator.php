<?php

namespace App\Core\Helpers;

class Validator
{
    public static function requiredFields(array $required, array $data)
    {
        $missing = [];

        foreach ($required as $field)
        {
            if (!isset($data[$field]) || empty(trim($data[$field])))
            {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }
}