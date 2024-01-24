
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
            // Make the specified fields nullable
            $table->string('firstName')->nullable()->change();
            $table->string('lastName')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('userType')->nullable()->change();
            $table->boolean('terms')->nullable()->change();
            $table->string('provider_id')->nullable()->change();
            $table->string('provider_name')->nullable()->change();
            $table->text('google_access_token_json')->nullable()->change();
            $table->string('password')->nullable()->change();
            // Add new fields here...
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to define down method for this case
    }
};
