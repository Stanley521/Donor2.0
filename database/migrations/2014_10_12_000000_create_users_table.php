<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->unsignedBigInteger('file_id')->nullable();
//            $table->foreign('file_id')->references('id')->on('files');

            $table->string('description')->default('There is no description');
            $table->string('notes')->default('There is no notes');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('blood_type')->nullable();
            $table->string('rhesus')->nullable();
            $table->string('user_type')->default('user');
            $table->timestamp('last_donor')->nullable();

            $table->boolean('findable')->default(true);

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
