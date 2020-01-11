<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageAudiences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_audiences', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('page_id', 20);
            $table->string('ps_id', 20);
            $table->string('name', 200)->nullable();
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('locale', 10)->nullable();
            $table->integer('timezone')->nullable();
            $table->string('gender', 6)->nullable();
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
        Schema::dropIfExists('page_audiences');
    }
}
