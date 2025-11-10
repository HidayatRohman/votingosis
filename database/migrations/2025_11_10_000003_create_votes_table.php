<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voting_id')->constrained('votings')->cascadeOnDelete();
            $table->timestamps();

            // Setiap user hanya boleh satu kali vote (untuk satu kandidat)
            $table->unique('user_id');
            // Cegah duplikasi vote yang sama (opsional jika suatu saat aturan berubah)
            $table->unique(['user_id', 'voting_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};