<?php

namespace Kukulis\PermissionBased\Listeners;

use Kukulis\PermissionBased\Repository\PermissionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class CheckPermissionRightListener
{

    public function __construct(
        private PermissionRepository $permissionRepository,
        private Security $security,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        $user = $this->security->getUser();

        if ( $user === null ) {
            // let unauthenticated user is handled by a default way.
            return;
        }

        $pathInfo = $event->getRequest()->getPathInfo();
        $this->logger->error('CheckPermissionRightListener pathInfo='.$pathInfo);

    }
}