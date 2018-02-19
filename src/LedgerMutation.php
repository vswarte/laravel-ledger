<?php

namespace Ledger;

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
}
