<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Order;

use Symfony\Component\Validator\Constraints\Uuid;

readonly final class CancelOrder
{
    public function __construct(
        #[Uuid]
        public string $orderId,
    ) {
    }
}
