<?php

namespace App\Domain\Cart;

use App\Event\CartCreated;
use App\Event\ItemAdded;
use App\Event\ItemRemoved;
use App\Domain\Cart\Command\AddItem;
use App\Domain\Cart\Command\RemoveItem;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

#[EventSourcingAggregate]
class Cart
{
    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $cartId;
    private array $items;

    #[CommandHandler]
    public static function createCart(AddItem $command): array
    {
        return [
            new CartCreated($command->cartId),
            new ItemAdded(
                cartId: $command->cartId,
                description: $command->description,
                image: $command->image,
                price: $command->price,
                itemId: $command->itemId,
                productId: $command->productId
            ),
        ];
    }

    #[CommandHandler]
    public function addItem(AddItem $command): array
    {
        if (count($this->items) >= 3) {
            throw new \InvalidArgumentException("can only add 3 items");
        }

        return [
            new ItemAdded(
                cartId: $command->cartId,
                description: $command->description,
                image: $command->image,
                price: $command->price,
                itemId: $command->itemId,
                productId: $command->productId
            ),
        ];
    }

    #[CommandHandler]
    public function removeItem(RemoveItem $command): array
    {
        if (!in_array($command->itemId, $this->items)) {
            throw new \InvalidArgumentException("Item {$command->itemId} not in the Cart");
        }
        
        return [
            new ItemRemoved($command->cartId, $command->itemId),
        ];
    }

    #[EventSourcingHandler]
    public function onCartCreated(CartCreated $event): void
    {
        $this->cartId = $event->cartId;
        $this->items = [];
    }

    #[EventSourcingHandler]
    public function onItemAdded(ItemAdded $event): void
    {
        $this->items[] = $event->itemId;
    }

    #[EventSourcingHandler]
    public function onItemRemoved(ItemRemoved $event): void
    {
        $this->items = array_diff($this->items, [$event->itemId]);
    }
}