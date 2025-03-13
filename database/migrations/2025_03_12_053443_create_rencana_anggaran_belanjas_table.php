<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rencana_anggaran_belanja', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('program_kerja_id')->constrained('program_kerja')->onDelete('cascade');
            $table->enum('kategori', ['income', 'outcome']);
            $table->foreignId('divisi_id')->constrained('divisi')->onDelete('cascade');
            $table->integer('qty');
            $table->string('unit');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('unit_total', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rencana_anggaran_belanja');
    }
};
