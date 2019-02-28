<?php

namespace Ledger;

trait Accountable
{
    public function accounts()
    {
        return $this->morphMany(LedgerAccount::class, 'accountable');
    }

    public function account(string $name)
    {
        return $this->accounts()->where('name', $name)->first();
    }

    public function createAccount(string $name, string $description)
    {
        $account = new LedgerAccount([
            'name'        => $name,
            'description' => $description,
        ]);

        return $this->accounts()->save($account);
    }
}
