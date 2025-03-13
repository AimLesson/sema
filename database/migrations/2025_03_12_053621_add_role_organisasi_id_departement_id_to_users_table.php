<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('password');
            $table->foreignId('organisasi_id')->after('role')->nullable()->constrained('organisasi')->onDelete('cascade');
            $table->foreignId('departement_id')->after('organisasi_id')->nullable()->constrained('departement')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropForeign(['organisasi_id']);
            $table->dropColumn('organisasi_id');
            $table->dropForeign(['departement_id']);
            $table->dropColumn('departement_id');
        });
    }
};
