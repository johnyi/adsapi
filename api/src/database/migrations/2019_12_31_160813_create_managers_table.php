<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 20);
            $table->string('manager_id', 20);
            $table->string('email')->nullable();
            $table->boolean('enable')->default(true);
            $table->string('client_id', 200);
            $table->string('client_secret', 200);
            $table->string('developer_token', 200)->nullable();
            $table->string('access_token', 1000)->nullable();
            $table->string('refresh_token', 500)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
