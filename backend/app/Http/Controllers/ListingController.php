<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Property::query();
        
        // Filter by user role
        if ($user->role === 'agent') {
            $query->where('user_id', $user->id);
        } elseif ($user->isCompanyUser()) {
            $companyAgents = $user->companyAgents();
            $agentUserIds = [];
            
            foreach ($companyAgents as $agent) {
                if ($agent->hasUserAccount()) {
                    $agentUserIds[] = $agent->user->id;
                }
            }
            
            $query->where(function($q) use ($user, $agentUserIds) {
                $q->where('user_id', $user->id);
                if (!empty($agentUserIds)) {
                    $q->orWhereIn('user_id', $agentUserIds);
                }
            });
        } else {
            $query->where('id', null);
        }
        
        // Get filter values from request
        $statusFilter = $request->get('status', 'all');
        $featuredFilter = $request->get('featured', 'all');
        $search = $request->get('search', '');
        
        // Apply status filter
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        // Apply featured filter
        if ($featuredFilter === 'featured') {
            $query->where('is_featured', true);
        } elseif ($featuredFilter === 'regular') {
            $query->where('is_featured', false);
        }
        
        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // Pagination
        $properties = $query->paginate(12);
        
        // Get statistics for the dashboard
        $stats = $this->getDashboardStats($user);
        
        return view('listings.index', compact('properties', 'statusFilter', 'featuredFilter', 'search', 'stats'));
    }
    
    private function getDashboardStats($user)
    {
        $stats = [];
        
        if ($user->role === 'agent') {
            $query = Property::where('user_id', $user->id);
            
            $stats = [
                'total' => $query->count(),
                'active' => $query->where('status', 'active')->count(),
                'inactive' => $query->where('status', 'inactive')->count(),
                'draft' => $query->where('status', 'draft')->count(),
                'sold_rented' => $query->whereIn('status', ['sold', 'rented'])->count(),
                'featured' => $query->where('is_featured', true)->count(),
            ];
            
        } elseif ($user->isCompanyUser()) {
            $companyAgents = $user->companyAgents();
            $agentUserIds = [];
            
            foreach ($companyAgents as $agent) {
                if ($agent->hasUserAccount()) {
                    $agentUserIds[] = $agent->user->id;
                }
            }
            
            $query = Property::where(function($q) use ($user, $agentUserIds) {
                $q->where('user_id', $user->id);
                if (!empty($agentUserIds)) {
                    $q->orWhereIn('user_id', $agentUserIds);
                }
            });
            
            $stats = [
                'total' => $query->count(),
                'active' => $query->where('status', 'active')->count(),
                'inactive' => $query->where('status', 'inactive')->count(),
                'draft' => $query->where('status', 'draft')->count(),
                'sold_rented' => $query->whereIn('status', ['sold', 'rented'])->count(),
                'featured' => $query->where('is_featured', true)->count(),
            ];
        } else {
            $stats = [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'draft' => 0,
                'sold_rented' => 0,
                'featured' => 0,
            ];
        }
        
        return $stats;
    }
}