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
        Schema::create('moves', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Player::class)->references('id')->on('players');
            $table->foreignIdFor(\App\Models\Round::class)->references('id')->on('rounds');
            $table->foreignIdFor(\App\Models\DeckCard::class)->references('id')->on('deck_cards');
            $table->smallInteger('points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moves');
    }
};
