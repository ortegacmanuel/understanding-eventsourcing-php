<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class CartCleared
{
    public function __construct(
        public UuidInterface $cartId
    )
    {
    }
}