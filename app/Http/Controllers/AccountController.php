<?php

namespace App\Http\Controllers;

use App\Actions\Account\CreateAccountAction;
use App\Actions\Account\DepositAction;
use App\Actions\Account\GetBalanceAction;
use App\Actions\Account\ListAccountsAction;
use App\Actions\Account\ApplyMonthlyInterestAction;
use App\Http\Requests\Account\CreateAccountRequest;
use App\Http\Requests\Account\DepositRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function __construct(
        private CreateAccountAction $createAccountAction,
        private DepositAction $depositAction,
        private GetBalanceAction $getBalanceAction,
        private ListAccountsAction $listAccountsAction,
        private ApplyMonthlyInterestAction $applyMonthlyInterestAction
    ) {}

    public function create(CreateAccountRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        
        $account = $this->createAccountAction->execute($data);

        return response()->json([
            'message' => 'Account created successfully',
            'account' => $account
        ], 201);
    }

    public function deposit(DepositRequest $request, int $account): JsonResponse
    {
        $data = $request->validated();
        $data['account_id'] = $account;
        $account = $this->depositAction->execute($data);

        return response()->json([
            'message' => 'Deposit successful',
            'new_balance' => $account->balance
        ]);
    }

    public function getBalance(int $accountId): JsonResponse
    {
        $balance = $this->getBalanceAction->execute($accountId);

        return response()->json([
            'balance' => $balance
        ]);
    }

    public function list(): JsonResponse
    {
        $accounts = $this->listAccountsAction->execute(Auth::id());

        return response()->json([
            'accounts' => $accounts
        ]);
    }

    public function applyMonthlyInterest(): JsonResponse
    {
        $this->applyMonthlyInterestAction->execute();

        return response()->json([
            'message' => 'Monthly interest applied successfully'
        ]);
    }
}
