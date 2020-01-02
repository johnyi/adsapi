<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 20);
            $table->string('sender_id', 20);
            $table->string('recipient_id', 20);
            $table->string('message_id', 200);
            $table->bigInteger('timestamp');
            $table->boolean('is_read')->default(false);
            $table->string('text', 1000)->nullable();
            $table->string('quick_reply', 50)->nullable();
            $table->string('reply_to', 200)->nullable();
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
        Schema::dropIfExists('messages');
    }
}
