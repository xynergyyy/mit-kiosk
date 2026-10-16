@extends('layouts.app')

@section('content')
<!-- Admin Navbar -->
@include('admin.partials.navbar')

<main class="pl-64 pt-16 min-h-screen bg-[#F0F4FA] dark:bg-slate-950 transition-colors duration-300">
    <div class="p-12 max-w-[1400px] mx-auto">
        <header class="mb-12 flex justify-between items-end">
            <div>
                <h1 class="text-5xl font-black tracking-tight text-slate-800 dark:text-slate-100 mb-3">Waypoint Management</h1>
                <p class="text-slate-500 dark:text-slate-400 text-lg">Digital touchpoints where visitors interact with staff across the campus.</p>
            </div>
            <a href="{{ route('admin.waypoints.create') }}" class="flex items-center gap-3 bg-slate-800 dark:bg-primary text-white px-8 py-5 rounded-[1.5rem] font-black uppercase tracking-widest text-xs shadow-xl shadow-slate-200 dark:shadow-primary/20 hover:bg-slate-900 dark:hover:bg-primary/80 hover:-translate-y-1 active:translate-y-0 transition-all group">
                <span class="material-symbols-outlined text-[20px] transition-transform group-hover:rotate-12">add_location_alt</span>
                Add New Waypoint
            </a>
        </header>

        @if(session('success'))
            <div class="mb-8 p-6 bg-white dark:bg-slate-900 rounded-3xl border-2 border-green-100 dark:border-green-900/30 flex items-center gap-4 animate-modal-enter shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-500">
                    <span class="material-symbols-outlined text-2xl font-black">check_circle</span>
                </div>
                <div>
                    <h4 class="font-black text-slate-800 dark:text-slate-100 tracking-tight leading-none mb-1">Success!</h4>
                    <p class="text-slate-500 dark:text-slate-400 font-medium">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-auto text-slate-300 dark:text-slate-600 hover:text-slate-500 dark:hover:text-slate-400 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            @forelse($waypoints as $waypoint)
            <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] shadow-sm border border-slate-200 dark:border-slate-800 hover:shadow-xl hover:border-primary/20 transition-all group">
                <div class="flex justify-between items-start mb-8">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 rounded-[1.25rem] flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-4xl">location_on</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] uppercase tracking-[0.2em] font-black text-slate-300 dark:text-slate-600 mb-1 leading-none">{{ $waypoint->location_code }}</span>
                        <div class="flex items-center gap-2 justify-end">
                            <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Live on Map</span>
                        </div>
                    </div>
                </div>

                <h3 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-2 leading-tight tracking-tight">{{ $waypoint->name }}</h3>
                <p class="text-slate-400 dark:text-slate-500 font-medium mb-8 flex items-center gap-2 text-sm">
                    <span class="material-symbols-outlined text-sm">layers</span>
                    Floor {{ $waypoint->floor }}
                    <span class="mx-2 text-slate-200 dark:text-slate-800">•</span>
                    <span class="material-symbols-outlined text-sm">gps_fixed</span>
                    {{ $waypoint->coordinates_x }}%, {{ $waypoint->coordinates_y }}%
                </p>
                
                <div class="pt-8 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php($assigned = $waypoint->assignedUsers)
                        @if($assigned && $assigned->count())
                            <div class="flex -space-x-3">
                                @foreach($assigned->take(3) as $u)
                                    <div class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border-4 border-white dark:border-slate-900 shadow-sm flex items-center justify-center text-[10px] font-black text-primary overflow-hidden">
                                        <div class="w-full h-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center">
                                            {{ strtoupper(substr($u->name, 0, 1)) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                <span class="block text-xs font-black text-slate-700 dark:text-slate-300 tracking-tight">{{ $assigned->count() }} staff</span>
                                <span class="block text-[9px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-600">Assigned</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-slate-300 dark:text-slate-700 italic">
                                <span class="material-symbols-outlined text-sm">group_off</span>
                                <span class="text-xs font-bold">Unassigned</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.waypoints.edit', $waypoint) }}" class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 hover:text-primary transition-all flex items-center justify-center">
                            <span class="material-symbols-outlined text-xl">edit</span>
                        </a>
                        <form action="{{ route('admin.waypoints.destroy', $waypoint) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 hover:bg-red-50 dark:hover:bg-red-900/30 hover:text-red-500 transition-all flex items-center justify-center">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
                <div class="col-span-3 py-32 text-center bg-white dark:bg-slate-900 rounded-[3rem] border-4 border-dashed border-slate-100 dark:border-slate-800">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200 dark:text-slate-700">
                        <span class="material-symbols-outlined text-6xl">map</span>
                    </div>
                    <h4 class="text-2xl font-black text-slate-800 dark:text-slate-100 mb-2 tracking-tight">No Waypoints Found</h4>
                    <p class="text-slate-400 dark:text-slate-500 font-medium mb-10 max-w-xs mx-auto">Start by pinning your first location on the campus map sanctuary.</p>
                    <a href="{{ route('admin.waypoints.create') }}" class="inline-flex items-center gap-3 bg-primary text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-primary/20 hover:-translate-y-1 transition-all">
                        <span class="material-symbols-outlined">add_location_alt</span>
                        Create First Waypoint
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
