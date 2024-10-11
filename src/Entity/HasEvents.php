<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

trait HasEvents
{
    /** @var array<object> */
    private array $events = [];

    public function recordThat(object $event): void
    {
        $this->events[] = $event;
    }

    /** @return array<object> */
    public function popEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }
}
