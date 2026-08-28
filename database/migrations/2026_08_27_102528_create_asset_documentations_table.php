<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Operator who logs this
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat']);
            $table->string('photo_full'); // Required
            $table->string('photo_damage')->nullable(); // Optional if not damaged
            $table->text('damage_description')->nullable();
            $table->string('action_taken')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_documentations');
    }
};
