<?php

namespace App\Event;

use Ramsey\Uuid\UuidInterface;

final readonly class CartCreated
{
    public function __construct(
        public UuidInterface $cartId
    )
    {
    }
}