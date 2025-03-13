<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove old 'role' column if it exists
            $table->dropColumn('role');

            // Add new 'role_id' column as a foreign key
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Revert changes
            $table->string('role')->nullable();
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
