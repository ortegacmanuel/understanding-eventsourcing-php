<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class ItemRemoved
{
    public function __construct(
        public UuidInterface $cartId,
        public UuidInterface $itemId
    )
    {
    }
}