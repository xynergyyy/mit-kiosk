@props([
    'routeName' => '',
    'exportRoute' => '',
    'totalCount' => 0,
    'label' => 'Records'
])

@php
    $currentFilter = request('filter', 'all');
    $currentSort = request('sort', 'desc');
    $startDate = request('start_date', '');
    $endDate = request('end_date', '');
    $isFiltered = ($currentFilter !== 'all' && !empty($currentFilter)) || !empty($startDate) || !empty($endDate) || $currentSort !== 'desc';
@endphp

<div class="mb-8 space-y-4">
    <!-- Filter Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-800 transition-colors duration-300">
        <form method="GET" action="{{ route($routeName) }}" id="filter-sort-form" class="space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-4">
                
                <!-- Quick Filter Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mr-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">filter_list</span>
                        Filter:
                    </span>

                    @php
                        $filters = [
                            'all' => 'All Time',
                            'day' => 'Today',
                            'week' => 'This Week',
                            'month' => 'This Month',
                            'year' => 'This Year',
                            'custom' => 'Custom Range'
                        ];
                    @endphp

                    @foreach($filters as $key => $title)
                        <label class="cursor-pointer">
                            <input type="radio" name="filter" value="{{ $key }}" class="sr-only filter-radio" 
                                {{ $currentFilter === $key ? 'checked' : '' }} 
                                onchange="handleFilterChange('{{ $key }}')">
                            <span class="px-4 py-2 rounded-xl text-xs font-black transition-all flex items-center gap-1.5 {{ $currentFilter === $key ? 'bg-primary text-white shadow-md shadow-primary/20 scale-105' : 'bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                                @if($key === 'custom')
                                    <span class="material-symbols-outlined text-sm">calendar_month</span>
                                @endif
                                {{ $title }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <!-- Sort & Export Actions -->
                <div class="flex items-center gap-3">
                    <!-- Sorting Dropdown -->
                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/80 rounded-xl px-3 py-1.5 border border-slate-100 dark:border-slate-800">
                        <span class="material-symbols-outlined text-sm text-slate-400">sort</span>
                        <select name="sort" onchange="document.getElementById('filter-sort-form').submit()" 
                            class="bg-transparent text-xs font-bold text-slate-700 dark:text-slate-300 outline-none cursor-pointer">
                            <option value="desc" {{ $currentSort === 'desc' ? 'selected' : '' }} class="dark:bg-slate-900">Newest First</option>
                            <option value="asc" {{ $currentSort === 'asc' ? 'selected' : '' }} class="dark:bg-slate-900">Oldest First</option>
                        </select>
                    </div>

                    @if($isFiltered)
                        <a href="{{ route($routeName) }}" class="px-3 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 text-xs font-black transition-all flex items-center gap-1" title="Clear Filters">
                            <span class="material-symbols-outlined text-sm">close</span>
                            Clear
                        </a>
                    @endif

                    <!-- Export CSV Button -->
                    <a href="{{ route($exportRoute, request()->query()) }}" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-600/20 flex items-center gap-2 transition-all active:scale-95"
                        title="Export filtered data to CSV / Excel">
                        <span class="material-symbols-outlined text-base">table_view</span>
                        Export CSV
                    </a>
                </div>
            </div>

            <!-- Custom Date Range Picker (collapsible / toggleable) -->
            <div id="custom-date-range" class="{{ $currentFilter === 'custom' ? 'flex' : 'hidden' }} flex-wrap items-center gap-4 pt-3 border-t border-slate-100 dark:border-slate-800/60 transition-all">
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400">Start Date:</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                        class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary outline-none transition-all" />
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-xs font-bold text-slate-500 dark:text-slate-400">End Date:</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                        class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-primary outline-none transition-all" />
                </div>
                <button type="submit" class="px-4 py-2 rounded-xl bg-primary hover:bg-blue-600 text-white text-xs font-black shadow-sm transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">search</span>
                    Apply Date Range
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function handleFilterChange(key) {
        const customContainer = document.getElementById('custom-date-range');
        if (key === 'custom') {
            customContainer.classList.remove('hidden');
            customContainer.classList.add('flex');
        } else {
            customContainer.classList.add('hidden');
            customContainer.classList.remove('flex');
            // Auto submit when non-custom filter is selected
            document.getElementById('filter-sort-form').submit();
        }
    }
</script>
