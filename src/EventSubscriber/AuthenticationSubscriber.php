<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class AuthenticationSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }
    public function onSecurityAuthentication(RequestEvent $event)
    {
        if(!$event->isMasterRequest()) {
            return;
        }

        $user = $this->security->getUser();

        if(!$user) {
            return;
        }

        $filter = $this->em->getFilters()->enable('account_filter');
        $filter->setParameter('account_id', $user->getAccount()->getId());
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onSecurityAuthentication',
        ];
    }
}
