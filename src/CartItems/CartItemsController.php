<?php

namespace App\CartItems;

use Ecotone\Modelling\QueryBus;

use Ramsey\Uuid\Uuid;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CartItemsController extends AbstractController
{
    public function __construct(
        private QueryBus $queryBus
    ) {}

    #[Route('/cart/{cartId}/items', methods: ['GET'], format: 'json')]
    public function getItems(
        String $cartId,
        QueryBus $queryBus
    ): JsonResponse {
        $cartItems = $queryBus->send(new GetCartItemsQuery(Uuid::fromString($cartId)));

        return new JsonResponse($cartItems);
    }
}