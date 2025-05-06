<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class InventoryChanged
{
    public function __construct(
        public UuidInterface $productId,
        public int $inventory
    )
    {
    }
}