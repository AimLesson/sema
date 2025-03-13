<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropColumn(['superadmin_approval', 'superadmin_notes']);
        });
    }

    public function down()
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->boolean('superadmin_approval')->nullable();
            $table->text('superadmin_notes')->nullable();
        });
    }
};
