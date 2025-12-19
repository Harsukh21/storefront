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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('two_factor_enabled')->default(false)->after('remember_token');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('two_factor_enabled')->default(false)->after('remember_token');
        });

        Schema::create('user_two_factor_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index();
            $table->string('code', 10);
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('admin_two_factor_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('admin_id')->index();
            $table->string('code', 10);
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_two_factor_codes');
        Schema::dropIfExists('user_two_factor_codes');

        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('two_factor_enabled');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('two_factor_enabled');
        });
    }
};
