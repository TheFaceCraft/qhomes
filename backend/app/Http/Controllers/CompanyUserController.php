<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of company users (Super Admin only).
     */
    public function index(): View
    {
        // Check if user is super admin
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can access this page.');
        }

        $companyUsers = User::where('role', 'company_user')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.company-users.index', compact('companyUsers'));
    }

    /**
     * Show the form for creating a new company user.
     */
    public function create(): View
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can create company users.');
        }

        return view('admin.company-users.create');
    }

    /**
     * Store a newly created company user.
     */
    public function store(Request $request): RedirectResponse
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can create company users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'company_user';
        
        User::create($validated);

        return redirect()->route('admin.company-users.index')
                       ->with('success', 'Company user created successfully!');
    }

    /**
     * Display the specified company user.
     */
    public function show(User $companyUser): View
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can view company users.');
        }

        // Ensure we're viewing a company user
        if (!$companyUser->isCompanyUser()) {
            abort(404, 'Company user not found.');
        }

        // Get statistics for this company user
        $stats = [
            'agents' => $companyUser->companyAgents()->count(),
            'properties' => $companyUser->accessible_properties()->count(),
            'active_properties' => $companyUser->accessible_properties()
                ->where('status', 'active')
                ->count(),
        ];

        // Get recent properties (last 10)
        $recentProperties = $companyUser->accessible_properties()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.company-users.show', compact('companyUser', 'stats', 'recentProperties'));
    }

    /**
     * Activate a company user.
     */
    public function activate(User $companyUser): RedirectResponse
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can activate company users.');
        }

        if (!$companyUser->isCompanyUser()) {
            abort(404, 'Company user not found.');
        }

        $companyUser->activate();

        return redirect()->back()->with('success', 'Company user activated successfully!');
    }

    /**
     * Deactivate a company user.
     */
    public function deactivate(User $companyUser): RedirectResponse
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can deactivate company users.');
        }

        if (!$companyUser->isCompanyUser()) {
            abort(404, 'Company user not found.');
        }

        $companyUser->deactivate();

        return redirect()->back()->with('success', 'Company user deactivated successfully!');
    }

    /**
     * Show company user statistics.
     */
    public function stats(): View
    {
        if (!auth()->user()->isSystemSuperAdmin()) {
            abort(403, 'Only System Super Admin can view statistics.');
        }

        $stats = [
            'total_company_users' => User::where('role', 'company_user')->count(),
            'active_company_users' => User::where('role', 'company_user')->where('status', 'active')->count(),
            'inactive_company_users' => User::where('role', 'company_user')->where('status', 'inactive')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'total_properties' => \App\Models\Property::count(),
        ];

        return view('admin.stats', compact('stats'));
    }
}
