<?php

namespace App\CartItems;

use Ramsey\Uuid\UuidInterface;


class GetCartItemsQuery
{
    private UuidInterface $cartId;

    public function __construct(UuidInterface $cartId)
    {
        $this->cartId = $cartId;
    }

    public function getCartId() : UuidInterface
    {
        return $this->cartId;
    }
}