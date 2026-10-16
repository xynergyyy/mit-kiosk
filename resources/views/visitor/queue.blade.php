@extends('layouts.app')

@section('content')
<!-- Warm Wayfinding Design Tokens -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --primary: #005da7;
        --primary-container: #68abff;
        --surface: #f5f7f9;
        --surface-container-low: #eef1f3;
        --surface-container-lowest: #ffffff;
        --on-surface: #2c2f31;
        --on-surface-variant: #595c5e;
        --secondary-container: #86f8c4;
        --on-secondary-container: #005e40;
        --outline-variant: rgba(171, 173, 175, 0.15);
    }

    body {
        background-color: var(--surface);
        color: var(--on-surface);
        font-family: 'Inter', sans-serif;
    }

    .display-lg { font-family: 'Manrope', sans-serif; font-size: 3rem; line-height: 1.1; font-weight: 800; }
    .display-md { font-family: 'Manrope', sans-serif; font-size: 2.25rem; line-height: 1.2; font-weight: 700; }
    .title-lg { font-family: 'Manrope', sans-serif; font-size: 1.5rem; line-height: 1.2; font-weight: 700; }
    .title-md { font-family: 'Manrope', sans-serif; font-size: 1.125rem; line-height: 1.2; font-weight: 700; }
    .label-lg { font-family: 'Inter', sans-serif; font-size: 1rem; line-height: 1.5; font-weight: 600; }
    .label-md { font-family: 'Inter', sans-serif; font-size: 0.875rem; line-height: 1.5; font-weight: 500; }
    .body-md { font-family: 'Inter', sans-serif; font-size: 1rem; line-height: 1.5; font-weight: 400; }
</style>

<main class="min-h-screen bg-[#f5f7f9] text-[#2c2f31] flex flex-col items-center p-6 md:p-10 lg:p-12" data-waypoint-id="{{ $waypoint?->id }}">
    <!-- Header Section -->
    <header class="w-full max-w-[1600px] flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
        <div class="text-center md:text-left">
            <h1 class="display-lg text-[#005da7] tracking-tight">Now Serving</h1>
            <p class="title-lg text-[#595c5e] mt-2 font-medium">
                {{ $waypoint ? $waypoint->name : 'Campus-wide Consultations' }}
            </p>
        </div>
        <div class="flex flex-col items-center md:items-end gap-3">
            <!-- High-contrast recognizable 12-hour Live Clock -->
            <div id="last-updated" class="text-sm font-extrabold text-slate-800 bg-white px-5 py-2.5 rounded-full border border-slate-300/80 shadow-sm flex items-center tracking-wide">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse mr-2.5"></span>
                <span>Connecting...</span>
            </div>
            <a href="/" class="label-md font-bold text-[#005da7] hover:underline flex items-center gap-2">
                <span class="material-symbols-outlined text-sm font-bold">arrow_back</span>
                Back to Map
            </a>
        </div>
    </header>

    <!-- Queue Grid (3 Waypoints) -->
    <div id="waypoints-grid" class="w-full max-w-[1600px] grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <!-- Waypoint columns injected here -->
    </div>
</main>

<script>
    const waypointIdRaw = document.querySelector('main[data-waypoint-id]')?.dataset?.waypointId;
    const waypointId = waypointIdRaw ? Number(waypointIdRaw) : 0;
    const apiUrl = waypointId ? `/api/kiosk/queue/${waypointId}` : `/api/kiosk/queue`;

    // Only display user role
    function roleLabel(role) {
        if (!role) return 'Faculty';
        if (role === 'guidance_counselor') return 'Guidance Counselor';
        if (role === 'principal') return 'Principal';
        if (role === 'admin') return 'Admin';
        return 'Faculty';
    }

    function displayTicketNumber(item, role) {
        if (item && item.display_ticket_number) {
            return String(item.display_ticket_number);
        }
        const raw = item && item.ticket_number ? item.ticket_number : 0;
        const n = Number(raw || 0);
        const offset = (role === 'principal') ? 100 : (role === 'guidance_counselor') ? 200 : 0;
        return String(n + offset).padStart(3, '0');
    }

    function format12HourTime(date) {
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });
    }

    function render(data) {
        const host = document.getElementById('waypoints-grid');
        const lastUpdated = document.getElementById('last-updated');

        host.innerHTML = '';

        const waypoints = (data && Array.isArray(data.waypoints)) ? data.waypoints : [];

        if (!waypoints.length) {
            host.innerHTML = `
                <div class="col-span-full bg-white/40 rounded-3xl p-12 text-center border border-dashed border-[#abadaf]/20">
                    <p class="label-md text-[#595c5e]/40">No active waypoints available</p>
                </div>
            `;
            return;
        }

        // Process 3 Waypoint Columns
        waypoints.forEach(wpItem => {
            const wp = wpItem.waypoint;
            const staff = wpItem.staff;
            const current = wpItem.current;
            const role = staff?.role;
            const staffRoleName = roleLabel(role);
            const isServing = Boolean(current);
            const upNext = Array.isArray(wpItem.up_next) ? wpItem.up_next.slice(0, 5) : [];

            const col = document.createElement('div');
            col.className = 'flex flex-col gap-6';

            // Build Upcoming Text-Only Items (Center-aligned Number + Staff Name)
            let upcomingListHtml = '';
            if (upNext.length > 0) {
                upcomingListHtml = `
                    <div class="flex flex-col items-center justify-center w-full divide-y divide-slate-100">
                        ${upNext.map((ticketItem, idx) => {
                            const tNum = displayTicketNumber(ticketItem, role);
                            const assignedStaffName = ticketItem?.assigned_user?.name || staff?.name || 'Staff';
                            
                            if (idx === 0) {
                                // First upcoming in line marked in yellow/amber text
                                return `
                                    <div class="flex flex-col items-center justify-center text-center w-full py-2 first:pt-0">
                                        <span class="font-manrope text-lg md:text-xl font-black text-[#d97706] tracking-tight leading-none">${tNum}</span>
                                        <span class="text-[11px] font-bold text-amber-700/90 mt-0.5 tracking-normal">${assignedStaffName}</span>
                                    </div>
                                `;
                            } else {
                                // Subsequent upcoming tickets in neutral clean text
                                return `
                                    <div class="flex flex-col items-center justify-center text-center w-full py-2">
                                        <span class="font-manrope text-base md:text-lg font-bold text-[#2c2f31] tracking-tight leading-none">${tNum}</span>
                                        <span class="text-[11px] font-medium text-slate-500 mt-0.5 tracking-normal">${assignedStaffName}</span>
                                    </div>
                                `;
                            }
                        }).join('')}
                    </div>
                `;
            } else {
                upcomingListHtml = `
                    <div class="py-4 text-center text-xs font-semibold text-slate-400 italic">
                        No upcoming tickets
                    </div>
                `;
            }

            col.innerHTML = `
                <!-- Waypoint Name Header at the Top -->
                <div class="px-2">
                    <h2 class="title-md text-[#595c5e] uppercase tracking-widest font-extrabold">${wp?.name || 'Waypoint'}</h2>
                </div>

                <!-- Box 1: Now Serving Card -->
                <div class="bg-white rounded-3xl shadow-[0_32px_32px_rgba(44,47,49,0.06)] p-8 flex flex-col items-center text-center relative overflow-hidden transition-all duration-500 border border-slate-100">
                    ${isServing ? '<div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-[#005da7] to-[#68abff]"></div>' : ''}
                    
                    <!-- Staff Name -->
                    <h3 class="title-md text-[#2c2f31] mb-1 font-bold">${staff?.name || 'Unassigned Staff'}</h3>
                    
                    <!-- Staff Role (Only Role) -->
                    <p class="label-md text-[#005da7] mb-6 uppercase tracking-wider font-extrabold">${staffRoleName}</p>
                    
                    <!-- Current Ticket Number -->
                    <div class="display-lg text-[#2c2f31] text-7xl md:text-8xl mb-6 ${isServing ? 'text-[#005da7]' : 'opacity-20'}">
                        ${isServing ? displayTicketNumber(current, role) : '---'}
                    </div>
                    
                    <!-- Serving Status Badge -->
                    <div class="${isServing ? 'bg-[#86f8c4] text-[#005e40]' : 'bg-[#eef1f3] text-[#595c5e]'} rounded-full px-6 py-2 label-lg font-bold">
                        ${isServing ? 'Serving Now' : 'Idle'}
                    </div>
                </div>

                <!-- Box 2: Up Next Separate Box -->
                <div class="bg-white rounded-3xl shadow-[0_16px_24px_rgba(44,47,49,0.04)] p-6 border border-slate-100 flex flex-col">
                    <h4 class="title-md text-[#595c5e] uppercase tracking-widest font-extrabold text-left w-full mb-4 px-1">Up Next</h4>
                    ${upcomingListHtml}
                </div>
            `;
            host.appendChild(col);
        });

        // High-contrast recognizable 12-hour format Live Clock
        lastUpdated.innerHTML = `
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2.5"></span>
            <span class="font-extrabold text-slate-800 tracking-wide">Live • ${format12HourTime(new Date())}</span>
        `;
    }

    async function refresh() {
        try {
            const res = await fetch(apiUrl, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            render(data);
        } catch (e) {
            console.error("Queue Refresh Error:", e);
        }
    }

    refresh();
    setInterval(refresh, 5000);
</script>
@endsection
