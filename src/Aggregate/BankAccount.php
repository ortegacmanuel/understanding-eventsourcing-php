<?php

namespace App\Aggregate;

use App\Event\AccountCreated;
use App\Event\MoneyDeposited;
use App\Event\MoneyWithdrawn;

use App\Command\CreateBankAccount;
use App\Command\DepositMoney;
use App\Command\WithdrawMoney;

use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\Attribute\EventSourcingAggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\EventSourcingHandler;
use Ecotone\Modelling\WithAggregateVersioning;

#[EventSourcingAggregate]
class BankAccount
{
    use WithAggregateVersioning;

    #[AggregateIdentifier]
    private string $accountId;
    private string $ownerName;
    private float $balance;

    #[CommandHandler]
    public static function create(CreateBankAccount $command): array
    {
        return [
            new AccountCreated(
                $command->getAccountId(),
                $command->getOwnerName(),
                $command->getInitialBalance()
            )
        ];
    }

    #[CommandHandler]
    public function deposit(DepositMoney $command): array
    {
        return [
            new MoneyDeposited(
                $this->accountId,
                $command->getAmount()
            )
        ];
    }

    #[CommandHandler]
    public function withdraw(WithdrawMoney $command): array
    {
        if ($this->balance < $command->getAmount()) {
            throw new \InvalidArgumentException('Insufficient funds');
        }

        return [
            new MoneyWithdrawn(
                $this->accountId,
                $command->getAmount()
            )
        ];
    }

    #[EventSourcingHandler]
    public function onAccountCreated(AccountCreated $event): void
    {
        $this->accountId = $event->getAccountId();
        $this->ownerName = $event->getOwnerName();
        $this->balance = $event->getInitialBalance();
    }

    #[EventSourcingHandler]
    public function onMoneyDeposited(MoneyDeposited $event): void
    {
        $this->balance += $event->getAmount();
    }

    #[EventSourcingHandler]
    public function onMoneyWithdrawn(MoneyWithdrawn $event): void
    {
        $this->balance -= $event->getAmount();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
