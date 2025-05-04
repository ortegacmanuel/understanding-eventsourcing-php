<?php

declare(strict_types=1);

namespace CartItems;

use App\Domain\Cart\Cart;

use App\Event\CartCreated;
use App\Event\ItemAdded;
use App\Event\ItemRemoved;
use App\Event\CartCleared;

use App\CartItems\GetCartItemsQuery;
use App\CartItems\CartItemsReadModel;

use App\Infra\UuidConverter;

use Ecotone\Lite\EcotoneLite;
use Ecotone\Messaging\Config\ServiceConfiguration;
use Ecotone\Lite\Test\FlowTestSupport;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CartItemTest extends TestCase
{

    public function test_list_cart_items()
    {
        $cartId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $image = 'https://example.com/image.jpg';
        $price = 1000;
        $description = 'Description';

        $this->assertEquals(
            [
                'cartId' => $cartId->toString(),
                'totalPrice' => $price,
                'items' => [[
                    'itemId' => $itemId->toString(),
                    'cartId' => $cartId->toString(),
                    'productId' => $productId->toString(),
                    'image' => $image,
                    'price' => $price,
                    'description' => $description
                ]]
            ],
            EcotoneLite::bootstrapFlowTestingWithEventStore(
                [Cart::class, UuidConverter::class, CartItemsReadModel::class],
                [new UuidConverter(), new CartItemsReadModel()],
                configuration: ServiceConfiguration::createWithDefaults()
            )
            ->withEventsFor(
                $cartId,
                Cart::class,
                [
                    new CartCreated($cartId),
                    new ItemAdded(
                        cartId: $cartId,
                        itemId: $itemId,
                        productId: $productId,
                        image: $image,
                        price: $price,
                        description: $description
                    )
                ]
            )
            ->sendQuery(new GetCartItemsQuery($cartId))->jsonSerialize()
        );
    }

    public function test_removing_an_item_from_cart()
    {
        $cartId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $image = 'https://example.com/image.jpg';
        $price = 1000;
        $description = 'Description';

        $this->assertEquals(
            [
                'cartId' => $cartId->toString(),
                'totalPrice' => 0,
                'items' => []
            ],
            EcotoneLite::bootstrapFlowTestingWithEventStore(
                [Cart::class, UuidConverter::class, CartItemsReadModel::class],
                [new UuidConverter(), new CartItemsReadModel()],
                configuration: ServiceConfiguration::createWithDefaults()
            )
            ->withEventsFor(
                $cartId,
                Cart::class,
                [
                    new CartCreated($cartId),
                    new ItemAdded(
                        cartId: $cartId,
                        itemId: $itemId,
                        productId: $productId,
                        image: $image,
                        price: $price,
                        description: $description
                    ),
                    new ItemRemoved(
                        cartId: $cartId,
                        itemId: $itemId
                    )
                ]
            )
            ->sendQuery(new GetCartItemsQuery($cartId))->jsonSerialize()
        );
    }
    
    public function test_clearing_cart()
    {
        $cartId = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $itemId = Uuid::uuid4();
        $image = 'https://example.com/image.jpg';
        $price = 1000;
        $description = 'Description';

        $this->assertEquals(
            [
                'cartId' => $cartId->toString(),
                'totalPrice' => 0,
                'items' => []
            ],
            EcotoneLite::bootstrapFlowTestingWithEventStore(
                [Cart::class, UuidConverter::class, CartItemsReadModel::class],
                [new UuidConverter(), new CartItemsReadModel()],
                configuration: ServiceConfiguration::createWithDefaults()
            )
            ->withEventsFor(
                $cartId,
                Cart::class,
                [
                    new CartCreated($cartId),
                    new ItemAdded(
                        cartId: $cartId,
                        itemId: $itemId,
                        productId: $productId,
                        image: $image,
                        price: $price,
                        description: $description
                    ),
                    new CartCleared(
                        cartId: $cartId,
                    )
                ]
            )
            ->sendQuery(new GetCartItemsQuery($cartId))->jsonSerialize()
        );
    }     
}