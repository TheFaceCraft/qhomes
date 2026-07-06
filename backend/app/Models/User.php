<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'agent_id',
        'is_active',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the agent associated with the user.
     */
    public function agent()
    {
        return $this->belongsTo(\App\Models\Agent::class);
    }

    /**
     * Get the properties owned by this user (for company users).
     */
    public function properties()
    {
        return $this->hasMany(\App\Models\Property::class);
    }

    /**
     * Get all agents that belong to this company user.
     */
    public function companyAgents()
    {
        // For company users, return a relationship
        if ($this->isCompanyUser()) {
            return $this->hasMany(\App\Models\Agent::class, 'created_by', 'name');
        }
        
        // Return empty relationship for other roles
        return $this->hasMany(\App\Models\Agent::class, 'created_by', 'name')->whereNull('created_by');
    }

    /**
     * Get all properties accessible by this user based on role and business logic.
     */
    public function getAccessiblePropertiesAttribute()
    {
        return $this->accessible_properties()->get();
    }

    /**
     * Get query for all properties accessible by this user based on role and business logic.
     */
    public function accessible_properties()
    {
        if ($this->isCompanyUser()) {
            // Company users can see their own properties and properties added by their agents
            $agentUserIds = [];
            
            // Get agents created by this company user that have user accounts
            $agents = $this->companyAgents()->get();
            foreach ($agents as $agent) {
                if ($agent->hasUserAccount()) {
                    $agentUserIds[] = $agent->user->id;
                }
            }
            
            return \App\Models\Property::where(function($query) use ($agentUserIds) {
                $query->where('user_id', $this->id); // Own properties
                if (!empty($agentUserIds)) {
                    $query->orWhereIn('user_id', $agentUserIds); // Agent properties
                }
            });
        } elseif ($this->isAgent()) {
            // Agents can only see their own properties
            return \App\Models\Property::where('user_id', $this->id);
        }
        
        return \App\Models\Property::where('id', null); // No access
    }

    /**
     * Get permissions for this user
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Company users have all permissions (like super admins)
        if ($this->isCompanyUser()) {
            return true;
        }

        return $this->permissions()->where('name', $permission)->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Company users have all permissions (like super admins)
        if ($this->isCompanyUser()) {
            return true;
        }

        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    /**
     * Sync user permissions
     */
    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }

    /**
     * Check if user is a company user.
     */
    public function isCompanyUser(): bool
    {
        return $this->role === 'company_user';
    }

    /**
     * Check if user is an agent.
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Check if user is a super admin (deprecated - use isCompanyUser instead).
     * @deprecated Use isCompanyUser() instead
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is the system super admin with full access.
     */
    public function isSystemSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Activate the user.
     */
    public function activate(): void
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Deactivate the user.
     */
    public function deactivate(): void
    {
        $this->update(['status' => 'inactive']);
    }

    /**
     * Ensure only one super admin exists.
     */
    public static function ensureSingleSuperAdmin(): bool
    {
        $superAdminCount = self::where('role', 'super_admin')->count();
        return $superAdminCount <= 1;
    }

    /**
     * Check if super admin already exists.
     */
    public static function superAdminExists(): bool
    {
        return self::where('role', 'super_admin')->exists();
    }

    /**
     * Get role display name.
     */
    public function getRoleDisplayAttribute(): string
    {
        return match($this->role) {
            'super_admin' => 'System Super Admin',
            'company_user' => 'Company User',
            'agent' => 'Agent',
            default => 'Unknown'
        };
    }
}
