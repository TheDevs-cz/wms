<?php declare(strict_types=1);

use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Config\FrameworkConfig;
use TheDevs\WMS\Message\Product\ProcessProductImport;

return static function (FrameworkConfig $framework): void {
    $messenger = $framework->messenger();

    $bus = $messenger->bus('command_bus');
    $bus->middleware()->id('doctrine_transaction');

    $messenger->failureTransport('failed');

    $messenger->transport('sync')
        ->dsn('sync://');

    $messenger->transport('failed')
        ->dsn('doctrine://default?queue_name=failed');

    $messenger->transport('async')
        ->options([
            'auto_setup' => false,
        ])
        ->dsn('%env(MESSENGER_TRANSPORT_DSN)%');

    $messenger->routing('TheDevs\WMS\Events\*')->senders(['async']);
    $messenger->routing(SendEmailMessage::class)->senders(['async']);
    $messenger->routing(ProcessProductImport::class)->senders(['async']);
};
