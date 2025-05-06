<?php

namespace App\Domain\Inventory\Command;

use Ramsey\Uuid\UuidInterface;

class ChangeInventory
{
    public function __construct(
        public UuidInterface $productId,
        public int $inventory
    )
    {
    }
}