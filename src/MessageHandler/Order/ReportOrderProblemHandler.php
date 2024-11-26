<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\ReportOrderProblem;

#[AsMessageHandler]
readonly final class ReportOrderProblemHandler
{
    public function __invoke(ReportOrderProblem $message): void
    {

    }
}
