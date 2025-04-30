<?php

declare(strict_types=1);

namespace AddItem;

use App\Domain\Cart\Command\AddItem;

use App\Event\CartCreated;
use App\Event\ItemAdded;

use App\Domain\Cart\Cart;

use App\Infra\UuidConverter;

use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class AddItemTest extends TestCase
{
    public function test_adding_new_item()
    {
        $cartId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $description = 'Description';
        $imageUrl = 'https://example.com/image.jpg';
        $price = 1000.00;

        $this->assertEquals(
            [
                new CartCreated(cartId: $cartId), 
                new ItemAdded(
                    cartId: $cartId, 
                    itemId: $itemId,
                    productId: $productId,
                    image: $imageUrl,
                    price: $price,
                    description: $description,
                )
            ],
            EcotoneLite::bootstrapFlowTesting([Cart::class])
                ->sendCommand(new AddItem(
                    cartId: $cartId,
                    itemId: $itemId,
                    productId: $productId,
                    image: $imageUrl,
                    description: $description,
                    price: $price,
                ))
                ->getRecordedEvents()
        );
    }

    public function test_max_3_items_per_cart()
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
                new ItemAdded($cartId, $itemId, $productId, $imageUrl, $price, $description),
                new ItemAdded($cartId, $itemId, $productId, $imageUrl, $price, $description),
            ])
            ->sendCommand(new AddItem(
                cartId: $cartId,
                itemId: $itemId,
                productId: $productId,
                image: $imageUrl,
                description: $description,
                price: $price,
            ));
    }
}
