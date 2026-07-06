<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, alter the enum to include both old and new values temporarily
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'agent', 'company_user') DEFAULT 'company_user'");
        
        // Then, update existing super_admin users to company_user
        DB::table('users')
            ->where('role', 'super_admin')
            ->update(['role' => 'company_user']);

        // Finally, remove super_admin from the enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('company_user', 'agent') DEFAULT 'company_user'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, update existing company_user users back to super_admin
        DB::table('users')
            ->where('role', 'company_user')
            ->update(['role' => 'super_admin']);

        // Then alter the enum back to the original values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'agent') DEFAULT 'agent'");
    }
};
