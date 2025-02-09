<?php

namespace App\DTO;

class Book
{
    /**
     * @var string
     */
    public ?string $name = null;

    /**
     * @var string
     */
    public ?string $year = null;

    public function __construct(?string $name, ?string $year)
    {
        $this->name = $name;
        $this->year = $year;
    }
}