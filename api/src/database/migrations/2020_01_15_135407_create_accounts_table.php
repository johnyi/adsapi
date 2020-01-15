<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_id', 20);
            $table->string('name', 100)->nullable();
            $table->char('currency', 3);
            $table->string('timezone', 20);
            $table->decimal('balance')->default(0);
            $table->decimal('spend_cap')->default(0);
            $table->string('manager_id', 20)->nullable();
            $table->boolean('enable')->default(true);
            $table->string('user_id', 20);
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
        Schema::dropIfExists('accounts');
    }
}
