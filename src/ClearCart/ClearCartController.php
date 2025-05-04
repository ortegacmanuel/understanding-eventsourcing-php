<?php

namespace App\ClearCart;

use App\Domain\Cart\Command\ClearCart;

use Ramsey\Uuid\Uuid;

use Ecotone\Modelling\CommandBus;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ClearCartController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    #[Route('/cart/{cartId}/clear-cart', methods: ['DELETE'], format: 'json')]
    public function clearCart(String $cartId): JsonResponse {
        $this->commandBus->send(new ClearCart(
            cartId: Uuid::fromString($cartId)
        ));

        return new JsonResponse(['cartId' => $cartId]);
    }
}