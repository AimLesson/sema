<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->foreignId('organisasi_id')->constrained('organisasi')->onDelete('cascade');
            $table->foreignId('departement_id')->nullable()->constrained('departement')->onDelete('set null');
            $table->text('description');
            $table->decimal('total_budget', 15, 2);
            $table->decimal('self_budget', 15, 2);
            $table->decimal('proposal_budget', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program_kerja');
    }
};
