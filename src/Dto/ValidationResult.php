<?php

declare(strict_types=1);

namespace App\Dto;

class ValidationResult
{
    public bool $isValid;
    public array $errors;

    public function __construct(bool $isValid, array $errors = [])
    {
        $this->isValid = $isValid;
        $this->errors = $errors;
    }


}
