<?php

declare(strict_types=1);

namespace App\Exception;

class EmailTakenException extends \Exception
{
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}
