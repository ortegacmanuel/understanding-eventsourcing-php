<?php

namespace App\Domain\Cart\Command;

use Ramsey\Uuid\UuidInterface;

class RemoveItem
{
    public function __construct(
        public UuidInterface $cartId,
        public UuidInterface $itemId
    ) {}
}