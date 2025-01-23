<?php

namespace Kukulis\PermissionBased\Listeners;

use Kukulis\PermissionBased\Repository\PermissionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CheckPermissionRightListener
{

    public function __construct(
        private PermissionRepository $permissionRepository,
        private Security             $security,
        private LoggerInterface      $logger
    )
    {
    }

    public function __invoke(RequestEvent $event): void
    {
        $user = $this->security->getUser();

        if ($user === null) {
            // let unauthenticated user is handled by a default way.
            return;
        }

        $routeName = $event->getRequest()->attributes->get('_route');

        $action = $routeName;
        if ( $action == null ) {
            return;
        }

        $permission = $this->permissionRepository->findOneByAction($action);
        if ( $permission === null ) {
            $this->logger->info(sprintf('There is no permission for action [%s]', $action));
            return;
        }

        $hasPermission = $this->permissionRepository->checkUserHasPermission($user, $permission);

        if (!$hasPermission) {
            throw new AccessDeniedException(sprintf('User [%s] cant call action [%s], because he has not permission [%s]',
                $user->getUsername(), $action, $permission->getName()));
        }

//        $pathInfo = $event->getRequest()->getPathInfo();
//        $this->logger->error('CheckPermissionRightListener pathInfo=' . $pathInfo . '  routeName=' . $routeName);

    }
}