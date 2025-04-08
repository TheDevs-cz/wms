<?php

declare(strict_types=1);

namespace TheDevs\WMS\QueryResult;

/**
 * @phpstan-type StockDemandData array{
 *      product_id: string,
 *      sku: string,
 *      title: string,
 *      ean: string,
 *      stock_quantity: int,
 *      unpicked_ordered_quantity: int,
 *      stock_difference: int,
 *  }
 */
readonly final class StockDemand
{
    public function __construct(
        public string $productId,
        public string $sku,
        public string $title,
        public string $ean,
        public int $stockQuantity,
        public int $unpickedOrderedQuantity,
        public int $stockDifference,
    ) {}

    /**
     * @param StockDemandData $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            sku: $data['sku'],
            title: $data['title'],
            ean: $data['ean'],
            stockQuantity: $data['stock_quantity'],
            unpickedOrderedQuantity: $data['unpicked_ordered_quantity'],
            stockDifference: $data['stock_difference'],
        );
    }
}
