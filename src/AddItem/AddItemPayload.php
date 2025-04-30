<?php
namespace App\AddItem;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class AddItemPayload
{
    private UuidInterface $itemId;
    private UuidInterface $productId;

    public function __construct(
        #[Assert\NotBlank]
        public string $description,
        #[Assert\NotBlank]
        public string $image,
        #[Assert\Positive]
        public float $price
    ) {}

    #[SerializedName('itemId')]
    public function setItemId(string $itemId): void
    {
        $this->itemId = Uuid::fromString($itemId);
    }

    #[SerializedName('productId')]
    public function setProductId(string $productId): void
    {
        $this->productId = Uuid::fromString($productId);
    }

    public function getItemId(): UuidInterface
    {
        return $this->itemId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }
}