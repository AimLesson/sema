<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_kerja_id')->constrained('program_kerja')->onDelete('cascade');

            // Approval statuses (nullable at first, updated when approved/rejected)
            $table->boolean('superadmin_approval')->nullable();
            $table->text('superadmin_notes')->nullable();

            $table->boolean('wakil_ketua_bidang_2_approval')->nullable();
            $table->text('wakil_ketua_bidang_2_notes')->nullable();

            $table->boolean('wakil_ketua_bidang_3_approval')->nullable();
            $table->text('wakil_ketua_bidang_3_notes')->nullable();

            $table->boolean('ketua_senat_mahasiswa_approval')->nullable();
            $table->text('ketua_senat_mahasiswa_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
