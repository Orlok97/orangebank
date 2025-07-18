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
        Schema::create('extrato_correntes', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo',10,2);
            $table->unsignedBigInteger('user_id');
            $table->string('tipo');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extrato_correntes');
    }
};
