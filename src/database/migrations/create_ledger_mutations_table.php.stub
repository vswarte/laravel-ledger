<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgerMutationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_mutations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ledger_account_id')
                ->references('id')
                ->on('ledger_accounts');

            $table->unsignedInteger('ledger_transaction_id')
                ->references('id')
                ->on('ledger_transactions');

            $table->char('debcred', 1);
            $table->unsignedInteger('amount');
            $table->string('currency');

            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ledger_mutations');
    }
}
