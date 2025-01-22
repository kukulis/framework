<?php

namespace Kukulis\PermissionBased;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class PermissionBasedBundle extends AbstractBundle
{
    public function getPath(): string
    {
        return __DIR__;
    }
}