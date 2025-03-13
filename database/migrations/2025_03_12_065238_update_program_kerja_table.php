<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('program_kerja', function (Blueprint $table) {
            $table->decimal('total_budget', 15, 2)->nullable()->change();
            $table->decimal('self_budget', 15, 2)->nullable()->change();
            $table->decimal('proposal_budget', 15, 2)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('program_kerja', function (Blueprint $table) {
            $table->decimal('total_budget', 15, 2)->default(0)->change();
            $table->decimal('self_budget', 15, 2)->default(0)->change();
            $table->decimal('proposal_budget', 15, 2)->default(0)->change();
        });
    }
};
