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
        DB::transaction(function () use ($mutations, $description) {
            $transaction = LedgerTransaction::create([
                'description' => $description,
            ])->fresh();

            $transaction->mutations()->saveMany($mutations);
        }, 5);
    }

    public static function credit(
        LedgerAccount $account,
        Money $amount,
        string $description = ''
    ) {
        $mutation = new LedgerMutation([
            'ledger_account_id' => $account->id,
            'debcred'           => LedgerMutation::CREDIT,
            'amount'            => $amount,
            'currency'          => $amount->getCurrency()->getCode(),
        ]);

        return self::createTransaction([$mutation], $description);
    }

    public static function debit(
        LedgerAccount $account,
        Money $amount,
        string $description = ''
    ) {
        $mutation = new LedgerMutation([
            'ledger_account_id' => $account->id,
            'debcred'           => LedgerMutation::DEBIT,
            'amount'            => $amount,
            'currency'          => $amount->getCurrency()->getCode(),
        ]);

        return self::createTransaction([$mutation], $description);
    }

    public static function transfer(
        LedgerAccount $from,
        LedgerAccount $to,
        Money $amount ,
        string $description = ''
    ) {
        $debit = new LedgerMutation([
            'ledger_account_id' => $from->id,
            'debcred'           => LedgerMutation::DEBIT,
            'amount'            => $amount,
            'currency'          => $amount->getCurrency()->getCode(),
        ]);

        $credit = new LedgerMutation([
            'ledger_account_id' => $to->id,
            'debcred'           => LedgerMutation::CREDIT,
            'amount'            => $amount,
            'currency'          => $amount->getCurrency()->getCode(),
        ]);

        return self::createTransaction([$debit, $credit], $description);
    }
}
