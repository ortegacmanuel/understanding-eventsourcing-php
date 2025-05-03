<?php
namespace App\RemoveItem;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class RemoveItemPayload
{
    private UuidInterface $itemId;

    #[SerializedName('itemId')]
    public function setItemId(string $itemId): void
    {
        $this->itemId = Uuid::fromString($itemId);
    }

    public function getItemId(): UuidInterface
    {
        return $this->itemId;
    }
}