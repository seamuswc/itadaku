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
        Schema::create('dones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('redeem_id');
            $table->string('shipping_number');
            $table->foreign('redeem_id')->references('id')->on('redeems')->onDelete('cascade');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('done');
    }
};
