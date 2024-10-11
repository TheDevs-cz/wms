<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

interface EntityWithEvents
{
    public function recordThat(object $event): void;

    /** @return array<object> */
    public function popEvents(): array;
}
