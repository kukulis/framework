<?php

namespace App\DTO;

class Book
{
    public ?string $name = null;
    public ?int $year = null;

    public function __construct(?string $name, ?int $year)
    {
        $this->name = $name;
        $this->year = $year;
    }
}