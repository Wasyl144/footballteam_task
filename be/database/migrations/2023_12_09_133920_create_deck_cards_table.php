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
        Schema::create('deck_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Card::class)->references('id')->on('cards');
            $table->foreignIdFor(\App\Models\Deck::class)->references('id')->on('decks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deck_cards');
    }
};
