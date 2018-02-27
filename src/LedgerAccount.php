<?php

namespace Ledger;

use Money\Money;
use Money\Currency;
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
