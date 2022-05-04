<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('parent_uid')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('scene')->default('home')->nullable();
            $table->string('last_message_id')->nullable();
            $table->boolean('ban')->default(0);
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
        Schema::dropIfExists('telegram_users');
    }
}
