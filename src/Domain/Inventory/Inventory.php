<?php

namespace App\Domain\Inventory;

use App\Event\InventoryChanged;

use App\Domain\Inventory\Command\ChangeInventory;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

#[EventSourcingAggregate]
class Inventory
{
    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $productId;

    #[CommandHandler]
    public static function setInventory(ChangeInventory $command): array
    {
        return [
            new InventoryChanged($command->productId, $command->inventory),
        ];
    }

    #[CommandHandler]
    public function changeInventory(ChangeInventory $command): array
    {
        return [
            new InventoryChanged($command->productId, $command->inventory),
        ];
    }

    #[EventSourcingHandler]
    public function onInventoryChanged(InventoryChanged $event): void
    {
        $this->productId = $event->productId;
    }
}