<?php

namespace App\ChangeInventory;

use App\Domain\Inventory\Command\ChangeInventory;

use Ramsey\Uuid\Uuid;

use Ecotone\Modelling\CommandBus;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ChangeInventoryController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus
    ) {}

    #[Route('/change-inventory', methods: ['POST'], format: 'json')]
    public function changeInventory(
        #[MapRequestPayload] InventoryChangedEventPayload $payload
    ): JsonResponse {
        $this->commandBus->send(new ChangeInventory(
            productId: Uuid::fromString($payload->getProductId()),
            inventory: $payload->getInventory()
        ));

        return new JsonResponse(['productId' => $payload->getProductId()]);
    }
}