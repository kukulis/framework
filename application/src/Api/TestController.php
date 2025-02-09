<?php

namespace App\Api;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function list() : Response {
        return new Response('{"TODO list"}');
    }
    public function error() : Response {
        throw new RuntimeException('Klaida');
    }
}