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
        Schema::create('unggulans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ✅ Add name field
            $table->string('picture')->nullable(); // ✅ Add picture field (nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unggulans');
    }
};
