<?php

namespace App\Tests\Draft;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ParameterTest extends KernelTestCase
{

    public function testParameter() {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();
        $grybas = $container->getParameter('grybas');

        $this->assertNotNull($grybas);

        echo "$grybas\n";

    }
}