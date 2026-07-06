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
            // Add status field for user activation/deactivation
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
        });

        // Update role enum to include super_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'company_user', 'agent')");
        
        // Create the super admin user (only if it doesn't exist)
        $superAdminExists = \App\Models\User::where('role', 'super_admin')->exists();
        if (!$superAdminExists) {
            \App\Models\User::create([
                'name' => 'System Super Admin',
                'email' => 'superadmin@qhomes.com',
                'email_verified_at' => now(),
                'password' => bcrypt('SuperAdmin@123'), // Default password - should be changed
                'role' => 'super_admin',
                'status' => 'active',
            ]);
            
            echo "Super Admin user created with email: superadmin@qhomes.com and password: SuperAdmin@123\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Revert role enum to previous state
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('company_user', 'agent')");
        
        // Remove super admin user
        \App\Models\User::where('role', 'super_admin')->delete();
    }
};
