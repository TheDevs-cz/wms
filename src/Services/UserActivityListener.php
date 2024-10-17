<?php

declare(strict_types=1);

namespace TheDevs\WMS\Services;

use Psr\Clock\ClockInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\User\LogUserActivity;

#[AsEventListener]
readonly final class UserActivityListener
{
    public function __construct(
        private Security $security,
        private MessageBusInterface $messageBus,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        /** @var null|User $user */
        $user = $this->security->getUser();

        if ($user !== null) {
            $this->messageBus->dispatch(
                new LogUserActivity(
                    $user->id,
                    $this->clock->now(),
                ),
            );
        }
    }
}
