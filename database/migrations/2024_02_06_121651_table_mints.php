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
        Schema::create('mints', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('tracking_number');
            $table->integer('nft_id')->nullable(); 
            $table->string('tx_hash', 70);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mints');
    }
};
