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
        Schema::create('access_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('The name of the access permission');
            $table->uuid('a_p_code')->comment('The code for the access permission');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_permissions');
    }
};