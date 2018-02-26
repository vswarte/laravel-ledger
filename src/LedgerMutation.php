<?php

namespace Ledger;

use Money\Money;
use Illuminate\Database\Eloquent\Model;

class LedgerMutation extends Model
{
    const DEBIT  = 'D';
    const CREDIT = 'C';

    protected $fillable = [
        'debcred', 'amount', 'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(LedgerAccount::class);
    }

    public function getAmountAttribute($value)
    {
        return Money::EUR($value);
    }

    public function setAmountAttribute($value)
    {
        if ($value instanceof Money)
            $value = $value->getAmount();

        $this->attributes['amount'] = $value;
    }
}
