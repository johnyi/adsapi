<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageReferrals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_referrals', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sender_id', 20);
            $table->string('recipient_id', 20);
            $table->bigInteger('timestamp');
            $table->string('ref', 50);
            $table->string('ad_id', 20)->nullable();
            $table->string('source', 50);
            $table->string('type', 20);
            $table->string('referer_uri', 200)->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_referrals');
    }
}
