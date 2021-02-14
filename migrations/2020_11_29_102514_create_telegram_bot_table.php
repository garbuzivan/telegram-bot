<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramBotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gi_tb_user', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tg_id');
            $table->integer('is_bot')->default(0);
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->timestamp('last_time')->nullable(); // последняя активность
            $table->string('sex')->nullable(); // пол
            $table->integer('message_count')->default(0); //  количество сообщений
            $table->bigInteger('money')->default(0); //  баланс
            $table->integer('rank')->nullable(); //  ранк
            $table->integer('penis')->nullable(); // пенис
            $table->integer('boobs')->nullable(); // сиськи
            $table->integer('vagina')->nullable(); // глубина влагалища
            $table->string('title')->nullable(); // Звание
            $table->timestamps();
        });

        Schema::create('gi_tb_chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->string('chat_title')->nullable();
            $table->timestamps();
        });

        Schema::create('gi_tb_chat_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->bigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('gi_tb_chat_admins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id');
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('gi_tb_messages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('update_id');
            $table->bigInteger('message_id');
            $table->bigInteger('from_id');
            $table->bigInteger('chat_id');
            $table->string('chat_title')->nullable();
            $table->integer('date')->default(0);
            $table->bigInteger('reply_message_id')->nullable();
            $table->bigInteger('reply_from_id')->nullable();
            $table->longText('text')->nullable();
            $table->json('json')->nullable();
            $table->timestamps();
        });

        Schema::create('gi_tb_timer', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('chat_id');
            $table->string('param');
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
        Schema::dropIfExists('gi_tb_user');
        Schema::dropIfExists('gi_tb_chats');
        Schema::dropIfExists('gi_tb_chat_users');
        Schema::dropIfExists('gi_tb_messages');
        Schema::dropIfExists('gi_tb_timer');
    }
}
