<?php

namespace App\RemoveItem;

use App\Domain\Cart\Command\RemoveItem;

use Ramsey\Uuid\Uuid;

use Ecotone\Modelling\CommandBus;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class RemoveItemController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    #[Route('/cart/{cartId}/remove-item', methods: ['DELETE'], format: 'json')]
    public function removeItem(
        #[MapRequestPayload] RemoveItemPayload $payload,
        String $cartId
    ): JsonResponse {
        $this->commandBus->send(new RemoveItem(
            cartId: Uuid::fromString($cartId),
            itemId: $payload->getItemId()
        ));

        return new JsonResponse(['cartId' => $cartId]);
    }
}