<?php

namespace Ledger;

use Money\Money;
use Money\Currency;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    protected $fillable = ['name', 'description'];

    public function accountable()
    {
        return $this->morphTo();
    }

    public function mutations()
    {
        return $this->hasMany(LedgerMutation::class);
    }

    protected function createTransaction(Money $amount, string $debcred, string $description)
    {
        DB::transaction(function () {
            $mutation = new LedgerMutation([
                'debcred'  => $debcred,
                'amount'   => $amount,
                'currency' => 'EUR',
            ]);

            $mutation->account()->associate($this);

            $transaction = LedgerTransaction::create([
                'description' => $description,
            ])->fresh();

            $transaction->mutations()->save($mutation);
        }, 5);
    }

    public function credit(Money $amount, string $description = '')
    {
        return $this->createTransaction(
            $amount,
            LedgerMutation::CREDIT,
            $description
        );
    }

    public function debit(Money $amount, string $description = '')
    {
        return $this->createTransaction(
            $amount,
            LedgerMutation::DEBIT,
            $description
        );
    }

    /**
     * Returns a balance for a certain currency.
     *
     * @todo: split by currency
     */
    public function balance($currency = 'EUR'): Money
    {
        $credit = new Money(
            $this->mutations()
                ->whereDebcred(LedgerMutation::CREDIT)
                ->sum('amount'),
            new Currency($currency)
        );
        $debit = new Money(
            $this->mutations()
                ->whereDebcred(LedgerMutation::DEBIT)
                ->sum('amount'),
            new Currency($currency)
        );

        return $credit->subtract($debit);
    }
}
