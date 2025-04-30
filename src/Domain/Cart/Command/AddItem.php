<?php

namespace App\Domain\Cart\Command;

use Ramsey\Uuid\UuidInterface;

class AddItem
{
    public function __construct(
        public UuidInterface $cartId,
        public string $description,
        public string $image,
        public float $price,
        public UuidInterface $itemId,
        public UuidInterface $productId
    ) {}
}