<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_one_id')->nullable();
            $table->foreign('user_one_id')->references('id')->on('users');

            $table->unsignedBigInteger('user_two_id')->nullable();
            $table->foreign('user_two_id')->references('id')->on('users');

            // c (connected), p (pending), d (disconnected)
            $table->string('status')->default('disconnected');

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
        Schema::dropIfExists('conversations');
    }
}
