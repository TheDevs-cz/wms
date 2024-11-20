<?php

declare(strict_types=1);

namespace TheDevs\WMS\Services;

use Psr\Clock\ClockInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\User\LogUserLogin;

#[AsEventListener]
readonly final class UserLoginListener
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(InteractiveLoginEvent $event): void
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $this->messageBus->dispatch(
            new LogUserLogin(
                $user->id,
                $this->clock->now(),
            ),
        );
    }
}
