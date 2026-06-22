<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('floor_id')->constrained()->cascadeOnDelete();
            $table->string('room_number', 20);
            $table->unsignedTinyInteger('capacity');
            $table->string('gender', 20)->nullable();
            $table->string('status', 20)->default('available');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('legacy_id')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['floor_id', 'room_number']);
            $table->index(['status', 'gender']);
            $table->index('floor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
