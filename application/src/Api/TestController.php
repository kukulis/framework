<?php

namespace App\Api;


use Symfony\Component\HttpFoundation\Response;

class TestController
{
    public function list() : Response {
        return new Response('{"TODO list"}');
    }
}