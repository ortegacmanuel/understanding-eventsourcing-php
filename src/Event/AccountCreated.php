<?php

namespace App\Event;

class AccountCreated
{
    public function __construct(
        private string $accountId,
        private string $ownerName,
        private float $initialBalance
    ) {}

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function getInitialBalance(): float
    {
        return $this->initialBalance;
    }
}
