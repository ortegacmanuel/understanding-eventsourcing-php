<?php

namespace App\Command;

class WithdrawMoney
{
    public function __construct(
        private string $accountId,
        private float $amount
    ) {}

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
