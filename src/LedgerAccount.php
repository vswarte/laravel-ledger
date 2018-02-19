<?php

namespace Ledger;

use Money\Money;
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

    public function credit(Money $amount, string $description = '')
    {
        $mutation = new LedgerMutation([
            'debcred'  => LedgerMutation::CREDIT,
            'amount'   => $amount,
            'currency' => 'EUR',
        ]);

        $mutation->account()->associate($this);

        $transaction = LedgerTransaction::create([
            'description' => $description,
        ])->fresh();

        $transaction->mutations()->save($mutation);

        return $result;
    }
}
