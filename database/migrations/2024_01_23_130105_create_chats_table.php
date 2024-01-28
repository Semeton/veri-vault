<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('recipient_id')->unsigned();
            $table->uuid('uuid');
            $table->string('chat_key');
            $table->string('sender_secret')->nullable();
            $table->string('sender_lock_secret')->nullable();
            $table->boolean('sender_lock')->default(0);
            $table->string('recipient_secret')->nullable();
            $table->string('recipient_lock_secret')->nullable();
            $table->boolean('recipient_lock')->default(0);
            $table->boolean('archive')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
