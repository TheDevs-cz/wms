<?php

declare(strict_types=1);

namespace TheDevs\WMS\QueryResult;


readonly final class StockDemand
{
    public function __construct(
        public string $sku,
        public string $title,
        public string $ean,
        public int $stockQuantity,
        public int $unpickedOrderedQuantity,
        public int $stockDifference,
    ) {}

    /**
     * @param array{
     *     sku: string,
     *     title: string,
     *     ean: string,
     *     stock_quantity: int,
     *     unpicked_ordered_quantity: int,
     *     stock_difference: int,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            sku: $data['sku'],
            title: $data['title'],
            ean: $data['ean'],
            stockQuantity: $data['stock_quantity'],
            unpickedOrderedQuantity: $data['unpicked_ordered_quantity'],
            stockDifference: $data['stock_difference'],
        );
    }
}
