<?php

namespace App\Controller;

use App\Command\CreateBankAccount;
use App\Command\DepositMoney;
use App\Command\WithdrawMoney;
use Ecotone\Modelling\CommandBus;
use Ecotone\Modelling\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class BankAccountController extends AbstractController
{
    public function __construct(
        private CommandBus $commandBus,
        private QueryBus $queryBus
    ) {}

    #[Route('/account/create', methods: ['POST'])]
    public function createAccount(): JsonResponse
    {
        $accountId = uniqid('account_');
        
        $this->commandBus->send(new CreateBankAccount(
            $accountId,
            'John Doe',
            0.0
        ));

        return new JsonResponse(['accountId' => $accountId]);
    }

    #[Route('/account/{accountId}/deposit/{amount}', methods: ['POST'])]
    public function deposit(string $accountId, float $amount): JsonResponse
    {
        $this->commandBus->send(new DepositMoney($accountId, $amount));
        return new JsonResponse(['status' => 'success']);
    }

    #[Route('/account/{accountId}/withdraw/{amount}', methods: ['POST'])]
    public function withdraw(string $accountId, float $amount): JsonResponse
    {
        $this->commandBus->send(new WithdrawMoney($accountId, $amount));
        return new JsonResponse(['status' => 'success']);
    }
}
