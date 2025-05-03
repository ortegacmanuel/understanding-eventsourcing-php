<?php

namespace App\CartItems;

use App\Event\CartCreated;
use App\Event\ItemAdded;
use App\Event\ItemRemoved;

use App\CartItems\CartItem;

use Ramsey\Uuid\UuidInterface;
use JsonSerializable;

use Ecotone\Modelling\Attribute\QueryHandler;
use Ecotone\EventSourcing\EventStore;
use Ecotone\Messaging\Attribute\Parameter\Reference;
use Ecotone\Modelling\Event;
use Ecotone\Messaging\MessageHeaders;
use Prooph\EventStore\Metadata\MetadataMatcher;
use Prooph\EventStore\Metadata\Operator;

class CartItemsReadModel implements \JsonSerializable
{
    private ?UuidInterface $cartId = null;
    private float $totalPrice = 0.0;
    /** @var CartItem[] */
    private array $items = [];

    #[QueryHandler]
    public function getCartItems(GetCartItemsQuery $query, #[Reference] EventStore $eventStore): self
    {
        $events = $this->loadEvents($query->getCartId(), $eventStore);
        foreach ($events as $event) {
            match ($event->getEventName()) {
                "App\Event\ItemAdded" => $this->applyItemAdded($event->getPayload()),
                "App\Event\CartCreated" => $this->applyCartCreated($event->getPayload()),
                "App\Event\ItemRemoved" => $this->applyItemRemoved($event->getPayload()),
                default => null
            };
        }
        return $this;
    }

    public function applyCartCreated(CartCreated $event): void
    {
        $this->cartId = $event->cartId;
    }

    public function applyItemAdded(ItemAdded $event): void
    {
        $this->items[] = new CartItem(
            itemId: $event->itemId,
            cartId: $event->cartId,
            description: $event->description,
            image: $event->image,
            price: $event->price,
            productId: $event->productId
        );

        $this->totalPrice += $event->price;
    }

    public function applyItemRemoved(ItemRemoved $event): void
    {
        $removedItem = array_find($this->items, fn (CartItem $item) => $item->itemId == $event->itemId);
        if ($removedItem) {
            $this->totalPrice -= $removedItem->price;
        }
        $this->items = array_values(array_filter($this->items, fn (CartItem $item) => $item->itemId != $event->itemId));
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function getCartId(): ?UuidInterface
    {
        return $this->cartId;
    }

    private function loadEvents(UuidInterface $cartId, EventStore $eventStore): array
    {
        $streamName = "App\Domain\Cart\Cart";
        if (!$eventStore->hasStream($streamName)) {
            return [];
        }

        $metadataMatcher = new MetadataMatcher();
        $metadataMatcher = $metadataMatcher->withMetadataMatch(
            MessageHeaders::EVENT_AGGREGATE_ID,
            Operator::EQUALS(),
            $cartId->toString()
        );

        return $eventStore->load($streamName, 1, null, $metadataMatcher);
    }

    public function jsonSerialize(): array
    {
        return [
            'cartId' => $this->cartId?->toString(),
            'totalPrice' => $this->totalPrice,
            'items' => array_map(fn (CartItem $item) => $item->jsonSerialize(), $this->items)
        ];
    }
}