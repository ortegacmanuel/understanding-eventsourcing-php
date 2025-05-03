<?php

namespace App\CartItems;

use Ramsey\Uuid\UuidInterface;
use JsonSerializable;

readonly class CartItem implements \JsonSerializable
{
    public function __construct(
        public UuidInterface $itemId,
        public UuidInterface $cartId,
        public string $description,
        public string $image,
        public float $price,
        public UuidInterface $productId
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId->toString(),
            'cartId' => $this->cartId->toString(),
            'productId' => $this->productId->toString(),
            'image' => $this->image,
            'price' => $this->price,
            'description' => $this->description
        ];
    }
}
