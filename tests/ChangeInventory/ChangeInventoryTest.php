<?php

declare(strict_types=1);

namespace Chan;

use App\Domain\Inventory\Command\ChangeInventory;

use App\Event\InventoryChanged;

use App\Domain\Inventory\Inventory;

use App\Infra\UuidConverter;

use Ecotone\Lite\EcotoneLite;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;

final class ChangeInventoryTest extends TestCase
{
    public function test_change_inventory()
    {
        $productId = Uuid::uuid4();
        $inventory = 10;

        $this->assertEquals(
            [
                new InventoryChanged(productId: $productId, inventory: $inventory), 
            ],
            EcotoneLite::bootstrapFlowTesting([Inventory::class])
                ->sendCommand(new ChangeInventory(
                    productId: $productId,
                    inventory: $inventory
                ))
                ->getRecordedEvents()
        );
    }
}
