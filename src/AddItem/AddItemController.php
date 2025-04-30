<?php

namespace App\AddItem;

use App\Domain\Cart\Command\AddItem;

use Ramsey\Uuid\Uuid;

use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class AddItemController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus
    ) {}

    #[Route('/cart/{cartId}/add-item', methods: ['POST'], format: 'json')]
    public function addItem(
        #[MapRequestPayload] AddItemPayload $payload,
        String $cartId
    ): JsonResponse {
        $this->commandBus->send(new AddItem(
            cartId: Uuid::fromString($cartId),
            itemId: $payload->getItemId(),
            productId: $payload->getProductId(),
            description: $payload->description,
            price: $payload->price,
            image: $payload->image
        ));

        return new JsonResponse(['cartId' => $cartId]);
    }
}