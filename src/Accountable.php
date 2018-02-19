<?php

namespace Ledger;

trait Accountable
{
    public function accounts()
    {
        return $this->morphMany(LedgerAccount::class, 'accountable');
    }

    public function account($name)
    {
        return $this->accounts()->where('name', $name)->first();
    }
}
