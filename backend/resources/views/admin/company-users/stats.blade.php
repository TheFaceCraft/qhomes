<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('System Statistics') }}
            </h2>
            <a href="{{ route('admin.company-users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Company Users
            </a>
        </div>
    </x-slot>

    <x-slot name="sidebar">
        <x-admin-sidebar />
    </x-slot>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <!-- Total Company Users -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-blue-500 bg-opacity-10">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.196-2.121M9 2h6v6a3 3 0 01-3 3H9a3 3 0 01-3-3V2z M15 8a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Company Users</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalCompanyUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Company Users -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-green-500 bg-opacity-10">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active Company Users</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeCompanyUsers }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Agents -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-purple-500 bg-opacity-10">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Agents</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalAgents }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Properties -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-orange-500 bg-opacity-10">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Properties</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalProperties }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- User Statistics -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">User Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Active Company Users</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-900">{{ $activeCompanyUsers }}</span>
                            <span class="text-xs text-gray-500">({{ $totalCompanyUsers > 0 ? round(($activeCompanyUsers / $totalCompanyUsers) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Inactive Company Users</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-900">{{ $inactiveCompanyUsers }}</span>
                            <span class="text-xs text-gray-500">({{ $totalCompanyUsers > 0 ? round(($inactiveCompanyUsers / $totalCompanyUsers) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Active Agents</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-900">{{ $activeAgents }}</span>
                            <span class="text-xs text-gray-500">({{ $totalAgents > 0 ? round(($activeAgents / $totalAgents) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-700">Inactive Agents</span>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-900">{{ $inactiveAgents }}</span>
                            <span class="text-xs text-gray-500">({{ $totalAgents > 0 ? round(($inactiveAgents / $totalAgents) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property Statistics -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Property Statistics</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Properties by Company Users</span>
                        <span class="text-sm text-gray-900">{{ $propertiesByCompanyUsers }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Properties by Agents</span>
                        <span class="text-sm text-gray-900">{{ $propertiesByAgents }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-sm font-medium text-gray-700">Average Properties per Company User</span>
                        <span class="text-sm text-gray-900">{{ $activeCompanyUsers > 0 ? round($totalProperties / $activeCompanyUsers, 1) : 0 }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm font-medium text-gray-700">Recent Properties (Last 30 Days)</span>
                        <span class="text-sm text-gray-900">{{ $recentProperties }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    @if($recentlyJoinedUsers && $recentlyJoinedUsers->count() > 0)
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recently Joined Users</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Join Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentlyJoinedUsers as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @if($user->role === 'company_user') bg-blue-100 text-blue-800 
                                                @elseif($user->role === 'agent') bg-purple-100 text-purple-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($user->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-admin-layout>
