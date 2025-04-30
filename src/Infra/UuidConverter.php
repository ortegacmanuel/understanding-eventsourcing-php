<?php

declare(strict_types=1);

namespace App\Infra;

use Ecotone\Messaging\Attribute\Converter;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;

final readonly class UuidConverter
{
    #[Converter]
    public function fromString(mixed $uuid): UuidInterface
    {
        if (is_array($uuid)) {
            return Uuid::fromString($uuid['uuid'] ?? '');
        }
        
        return Uuid::fromString($uuid);
    }

    #[Converter]
    public function toString(UuidInterface $uuid): string
    {
        return $uuid->toString();
    }
}