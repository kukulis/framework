<?php

namespace Kukulis\PermissionBased\Listeners;

use Kukulis\PermissionBased\Repository\PermissionRepository;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CheckPermissionRightListener
{

    public function __construct(
        PermissionRepository $permissionRepository
    )
    {
    }

    public function __invoke(RequestEvent $event): void
    {

    }
}