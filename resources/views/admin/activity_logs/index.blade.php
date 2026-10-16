@extends('layouts.app')

@section('content')
@include('admin.partials.navbar')

<main class="pl-64 pt-16 min-h-screen bg-slate-50 dark:bg-slate-950 transition-colors duration-300">
    <div class="p-8 max-w-[95rem] mx-auto">
        <header class="mb-8 flex flex-wrap justify-between items-end gap-4">
            <div>
                <h1 class="text-4xl font-extrabold font-manrope tracking-tight text-slate-800 dark:text-slate-100">Activity Logs</h1>
                <p class="text-slate-500 dark:text-slate-400">Complete audit trail of administrator and staff account actions.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.activity_logs.export', request()->query()) }}" 
                    class="px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-blue-600/20 flex items-center gap-2 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Export CSV / Excel
                </a>
                <div class="px-5 py-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col items-end">
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Total Logs</span>
                    <span class="text-2xl font-black text-slate-800 dark:text-slate-100">{{ $logs->total() }}</span>
                </div>
            </div>
        </header>

        <!-- Role Filter & Search Bar -->
        <div class="mb-6 p-4 bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mr-2">Filter Role:</span>
                @php $currentRole = request('role', 'all'); @endphp
                <a href="{{ route('admin.activity_logs', array_merge(request()->query(), ['role' => 'all'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $currentRole === 'all' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    All Roles
                </a>
                <a href="{{ route('admin.activity_logs', array_merge(request()->query(), ['role' => 'admin'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $currentRole === 'admin' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Admin Only
                </a>
                <a href="{{ route('admin.activity_logs', array_merge(request()->query(), ['role' => 'staff'])) }}"
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $currentRole === 'staff' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Staff / Faculty Only
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.activity_logs') }}" method="GET" class="flex items-center gap-2">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-2.5 text-slate-400 text-lg">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..."
                        class="pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary w-64">
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-primary hover:text-white rounded-xl text-xs font-black transition-all">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.activity_logs', array_diff_key(request()->query(), ['search' => ''])) }}" class="p-2 text-slate-400 hover:text-red-500" title="Clear search">
                        <span class="material-symbols-outlined text-sm">close</span>
                    </a>
                @endif
            </form>
        </div>

        @include('admin.partials.filter-bar', [
            'routeName' => 'admin.activity_logs',
            'exportRoute' => 'admin.activity_logs.export',
            'totalCount' => $logs->total(),
            'label' => 'Activity Records'
        ])

        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden transition-colors duration-300">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">#</th>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">Date & Time</th>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">Actor</th>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">Action Type</th>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">Activity Description</th>
                            <th class="text-left px-6 py-4 font-black uppercase tracking-widest text-[10px]">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($logs as $index => $log)
                            @php
                                $badgeStyle = match(true) {
                                    str_contains($log->action, 'add') || str_contains($log->action, 'accept') => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/30',
                                    str_contains($log->action, 'delete') || str_contains($log->action, 'decline') => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800/30',
                                    str_contains($log->action, 'update') => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800/30',
                                    str_contains($log->action, 'login') => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800/30',
                                    str_contains($log->action, 'logout') => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                    default => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800/30',
                                };

                                $roleBadge = match($log->user_role) {
                                    'admin' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 border-purple-200 dark:border-purple-800/30',
                                    default => 'bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 border-sky-200 dark:border-sky-800/30',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500">
                                    {{ $logs->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                                        {{ $log->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-semibold uppercase">
                                        {{ $log->created_at->format('h:i:s A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-black text-slate-800 dark:text-slate-200 text-xs">
                                        {{ $log->user_name ?? 'System' }}
                                    </div>
                                    <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider border {{ $roleBadge }}">
                                        {{ $log->user_role ?? 'system' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $badgeStyle }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-700 dark:text-slate-300">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-slate-400 dark:text-slate-600 font-bold">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-700">history_toggle_off</span>
                                        <span class="font-bold text-slate-500 dark:text-slate-400">No activity logs recorded yet.</span>
                                        <span class="text-xs font-medium text-slate-400 dark:text-slate-600">Actions by admin and staff accounts will be audited and logged here automatically.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
