<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class ItemAdded
{
    public function __construct(
        public UuidInterface $cartId,
        public UuidInterface $itemId,
        public UuidInterface $productId,   
        public string $image,
        public float $price,
        public string $description
    )
    {
    }
}