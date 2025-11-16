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
        Schema::create('membership_studio_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
            $table->foreignId('studio_class_id')->constrained('studio_classes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['membership_id', 'studio_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membership_studio_class');
    }
};
