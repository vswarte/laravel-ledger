<?php

namespace Ledger;

use Money\Money;
use Illuminate\Support\Facades\DB;

class TransactionFactory
{
    public static function createTransaction(
        array $mutations,
        string $description
    ) {
        DB::transaction(function () {
            foreach ($mutations as $mutation) {
                $mutation->account_id = $mutation->account->id;
            }

            $transaction = LedgerTransaction::create([
                'description' => $description,
            ])->fresh();

            $transaction->mutations()->save($mutations);
        }, 5);
    }

    public static function credit(
        LedgerAccount $account,
        Money $amount,
        string $description = ''
    ) {
        $mutation = new LedgerMutation([
            'account'  => $account,
            'debcred'  => LedgerMutation::CREDIT,
            'amount'   => $amount,
            'currency' => $amount->getCurrency()->getCode(),
        ]);

        return $this->createTransaction([$mutation], $description);
    }

    public static function debit(
        LedgerAccount $account,
        Money $amount,
        string $description = ''
    ) {
        $mutation = new LedgerMutation([
            'account'  => $account,
            'debcred'  => LedgerMutation::DEBIT,
            'amount'   => $amount,
            'currency' => $amount->getCurrency()->getCode(),
        ]);

        return $this->createTransaction([$mutation], $description);
    }

}
