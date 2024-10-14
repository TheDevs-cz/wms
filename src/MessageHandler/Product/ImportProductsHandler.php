<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Product;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Product\ImportProducts;
use TheDevs\WMS\Message\Product\ProcessProductImport;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class ImportProductsHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(ImportProducts $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $document = new Crawler($message->feed->getContent());

        $document->filter('SHOPITEM')->each(function (Crawler $node) use ($user) {
            $title = $this->getTextOrNull($node, 'PRODUCT');
            assert($title !== null);
            $itemId = $this->getTextOrNull($node, 'ITEM_ID');
            assert($itemId !== null);
            $ean = $this->getTextOrNull($node, 'EAN');
            assert($ean !== null);

            $category = $this->getTextOrNull($node, 'CATEGORYTEXT');
            $manufacturer = $this->getTextOrNull($node, 'MANUFACTURER');
            $imgUrl = $this->getTextOrNull($node, 'IMGURL');

            $this->bus->dispatch(
                new ProcessProductImport(
                    userId: $user->id,
                    title: $title,
                    sku: $itemId,
                    ean: $ean,
                    category: $category,
                    manufacturer: $manufacturer,
                    image: $imgUrl,
                ),
            );
        });
    }

    private function getTextOrNull(Crawler $node, string $selector): null|string
    {
        $filtered = $node->filter($selector);

        return $filtered->count() > 0 ? $filtered->text() : null;
    }
}
