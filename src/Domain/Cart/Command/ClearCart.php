<?php

namespace App\Domain\Cart\Command;

use Ramsey\Uuid\UuidInterface;

class ClearCart
{
    public function __construct(
        public UuidInterface $cartId
    ) {}
}