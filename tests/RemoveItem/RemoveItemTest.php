<?php

declare(strict_types=1);

namespace RemoveItem;

use App\Domain\Cart\Command\RemoveItem;

use App\Event\CartCreated;
use App\Event\ItemAdded;
use App\Event\ItemRemoved;

use App\Domain\Cart\Cart;

use App\Infra\UuidConverter;

use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class RemoveItemTest extends TestCase
{
    public function test_removing_item()
    {
        $cartId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $description = 'Description';
        $imageUrl = 'https://example.com/image.jpg';
        $price = 1000.00;

        $this->assertEquals(
            [
                new ItemRemoved(
                    cartId: $cartId, 
                    itemId: $itemId,
                )
            ],
            EcotoneLite::bootstrapFlowTestingWithEventStore([Cart::class, UuidConverter::class], [new UuidConverter()])
                ->withEventsFor($cartId, Cart::class, [
                    new CartCreated($cartId),
                    new ItemAdded($cartId, $itemId, $productId, $imageUrl, $price, $description)
                ])
                ->sendCommand(new RemoveItem(
                    cartId: $cartId,
                    itemId: $itemId,
                ))
                ->getRecordedEvents()
        );
    }

    public function test_removing_an_already_removed_item()
    {
        $cartId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $description = 'Description';
        $imageUrl = 'https://example.com/image.jpg';
        $price = 1000.00;

        $this->expectException(InvalidArgumentException::class);

        EcotoneLite::bootstrapFlowTestingWithEventStore([Cart::class, UuidConverter::class], [new UuidConverter()])
            ->withEventsFor($cartId, Cart::class, [
                new CartCreated($cartId),
                new ItemAdded($cartId, $itemId, $productId, $imageUrl, $price, $description),
                new ItemRemoved($cartId, $itemId)
            ])
            ->sendCommand(new RemoveItem(
                cartId: $cartId,
                itemId: $itemId
            ));
    }
}
