<?php

namespace Ledger;

use Illuminate\Support\ServiceProvider;

class LedgerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (! class_exists('CreateLedgerAccountsTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_ledger_accounts_table.php.stub' 
                    => database_path('migrations/'.date('Y_m_d_His', time()).'_create_ledger_accounts_table.php'),
            ], 'migrations');
        }

        if (! class_exists('CreateLedgerTransactionsTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_ledger_transactions_table.php.stub' 
                    => database_path('migrations/'.date('Y_m_d_His', time()).'_create_ledger_transactions_table.php'),
            ], 'migrations');
        }

        if (! class_exists('CreateLedgerMutationsTable')) {
            $this->publishes([
                __DIR__.'/database/migrations/create_ledger_mutations_table.php.stub' 
                    => database_path('migrations/'.date('Y_m_d_His', time()).'_create_ledger_mutations_table.php'),
            ], 'migrations');
        }

    }
}
