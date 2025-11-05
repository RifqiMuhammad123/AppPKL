<?php

// database/migrations/xxxx_xx_xx_add_password_encrypted_to_admins_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->text('password_encrypted')->nullable()->after('password');
    });

    }

    public function down()
    {
        Schema::table('admin', function (Blueprint $table) {
            $table->dropColumn('password_encrypted');
        });
    }
};
