<?php

namespace Ledger;

use Money\Money;
use Illuminate\Database\Eloquent\Model;

class LedgerTransaction extends Model
{
    protected $fillable = ['description'];
}
