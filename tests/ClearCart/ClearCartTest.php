<?php

declare(strict_types=1);

namespace ClearCart;

use App\Domain\Cart\Command\ClearCart;

use App\Event\CartCreated;
use App\Event\ItemAdded;
use App\Event\CartCleared;

use App\Domain\Cart\Cart;

use App\Infra\UuidConverter;

use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class ClearCartTest extends TestCase
{
    public function test_clearing_cart()
    {
        $cartId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $description = 'Description';
        $imageUrl = 'https://example.com/image.jpg';
        $price = 1000.00;

        $this->assertEquals(
            [
                new CartCleared(
                    cartId: $cartId, 
                )
            ],
            EcotoneLite::bootstrapFlowTestingWithEventStore([Cart::class, UuidConverter::class], [new UuidConverter()])
                ->withEventsFor($cartId, Cart::class, [
                    new CartCreated($cartId),
                    new ItemAdded($cartId, $itemId, $productId, $imageUrl, $price, $description)
                ])
                ->sendCommand(new ClearCart(
                    cartId: $cartId,
                ))
                ->getRecordedEvents()
        );
    }
}
