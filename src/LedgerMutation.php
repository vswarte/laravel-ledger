<?php

namespace Ledger;

use Money\Money;
use Money\Currency;
use Illuminate\Database\Eloquent\Model;

class LedgerMutation extends Model
{
    const DEBIT  = 'D';
    const CREDIT = 'C';

    protected $fillable = [
        'debcred', 'amount', 'account_id', 'currency',
    ];

    public function account()
    {
        return $this->belongsTo(LedgerAccount::class, 'ledger_account_id');
    }

    public function getAmountAttribute($value)
    {
        return Money($value, new Currency($this->currency));
    }

    public function setAmountAttribute($value)
    {
        // if we get passed in an instance of a Money object, grab the
        // cent value as well as the currency. 
        if ($value instanceof Money) {
            $currency = $value->getCurrency()->getCode();
            $value = $value->getAmount();

            $this->attributes['currency'] = $currency;
        }

        $this->attributes['amount']   = $value;
    }
}
