<?php
namespace App\ChangeInventory;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;

class InventoryChangedEventPayload
{
    private UuidInterface $productId;

    public function __construct(
        #[Assert\NotBlank]
        private int $inventory
    ) {}

    #[SerializedName('productId')]
    public function setProductId(string $productId): void
    {
        $this->productId = Uuid::fromString($productId);
    }


    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getInventory(): int
    {
        return $this->inventory;
    }
}