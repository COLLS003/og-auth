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
            //nullable field we are not guaranteed to have the valeus from google
            $table->string('firstName')->nullable()->change();
            $table->string('lastName')->nullable()->change();
            $table->string('password')->nullable()->change();
            //some new fields here...
            $table->string('provider_name')->nullable()->after('password');
            $table->string('provider_id')->nullable()->after('provider_name');
            $table->text('google_access_token_json')->nullable()->after('provider_name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
