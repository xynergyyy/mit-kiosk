@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
<style>
    .preview-container {
        width: 100%;
        height: 180px;
        background-color: #0b0f10;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
    }
    .preview-container #pano-preview {
        width: 100%;
        height: 100%;
    }
</style>
@endsection

@section('content')
<main class="min-h-screen bg-[#F0F4FA] dark:bg-slate-950 selection:bg-primary/10 transition-colors duration-300">
    <div class="max-w-[1600px] mx-auto p-12">
        <!-- Header -->
        <header class="mb-12 flex items-end justify-between">
            <div>
                <a href="{{ route('admin.waypoints') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-black text-primary uppercase tracking-[0.2em] hover:gap-3 transition-all">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Back to Waypoints
                </a>
                <h1 class="text-5xl font-black tracking-tight text-slate-800 dark:text-slate-100">Add Waypoint</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 text-lg">Pin a new location on the campus map and assign staff members.</p>
            </div>
            <div class="flex items-center gap-4 bg-white dark:bg-slate-900 p-2 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm transition-colors">
                <div class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-400 block leading-none mb-1">Step 1</span>
                    <span class="text-sm font-bold text-blue-600 dark:text-blue-400">Fill Details</span>
                </div>
                <span class="material-symbols-outlined text-slate-300 dark:text-slate-700">chevron_right</span>
                <div class="px-4 py-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-600 block leading-none mb-1">Step 2</span>
                    <span class="text-sm font-bold text-slate-400 dark:text-slate-600">Drop Pin</span>
                </div>
            </div>
        </header>

        <form action="{{ route('admin.waypoints.store') }}" method="POST" id="waypoint-form" class="grid grid-cols-12 gap-12">
            @csrf
            
            <!-- Left: Form Controls -->
            <div class="col-span-12 lg:col-span-5 space-y-8">
                <section class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400">info</span>
                        </span>
                        <h3 class="text-xl font-black text-slate-800 dark:text-slate-100">Basic Information</h3>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Waypoint Name</label>
                            <input type="text" name="name" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold placeholder:text-slate-300 dark:placeholder:text-slate-600" placeholder="e.g. Registrar's Office">
                            @error('name') <p class="mt-2 text-xs text-red-500 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Location Code</label>
                            <input type="text" name="location_code" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold placeholder:text-slate-300 dark:placeholder:text-slate-600" placeholder="ADM-101">
                        </div>

                        <div>
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Floor</label>
                            <input type="number" name="floor" value="1" required class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" required rows="3" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold placeholder:text-slate-300 dark:placeholder:text-slate-600" placeholder="What can visitors do here?"></textarea>
                    </div>
                </section>

                <section class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400">group</span>
                        </span>
                        <h3 class="text-xl font-black text-slate-800 dark:text-slate-100">Assign Staff Members</h3>
                    </div>

                    <div class="grid grid-cols-1 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($staffUsers as $staff)
                            <label class="group relative flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent hover:border-primary/20 cursor-pointer transition-all has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:border-primary">
                                <input type="checkbox" name="assigned_user_ids[]" value="{{ $staff->id }}" class="hidden">
                                <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center border border-slate-200 dark:border-slate-700 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-slate-400 dark:text-slate-500">person</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-black text-slate-800 dark:text-slate-100 tracking-tight">{{ $staff->name }}</span>
                                    <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">{{ $staff->role }}</span>
                                </div>
                                <div class="ml-auto opacity-0 group-has-[:checked]:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-primary font-black">check_circle</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </section>

                <section class="bg-white dark:bg-slate-900 p-10 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm space-y-6 transition-colors">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <span class="material-symbols-outlined text-slate-500 dark:text-slate-400">photo_library</span>
                        </span>
                        <h3 class="text-xl font-black text-slate-800 dark:text-slate-100">Media & 3D Experience</h3>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-2">Waypoint Image URL</label>
                        <input type="text" name="image_url" id="image-url-input" value="{{ old('image_url') }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold placeholder:text-slate-300 dark:placeholder:text-slate-600" placeholder="e.g. /images/lobby.jpg or http://...">
                        <div class="mt-3 flex items-center gap-4 hidden" id="image-preview-wrapper">
                            <img id="image-preview" src="" class="w-20 h-20 rounded-xl object-cover border border-slate-200 dark:border-slate-800">
                            <span class="text-xs text-slate-400 dark:text-slate-500 font-bold">Image preview loaded</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">360° Panorama Image URL</label>
                            <button type="button" id="btn-clear-pano" class="text-xs text-red-500 hover:text-red-600 font-black uppercase tracking-wider">Clear</button>
                        </div>
                        <input type="text" name="pano_url" id="pano-url-input" value="{{ old('pano_url') }}" class="w-full px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-2 border-transparent rounded-2xl text-slate-800 dark:text-slate-100 focus:border-primary/20 focus:bg-white dark:focus:bg-slate-800 transition-all font-bold placeholder:text-slate-300 dark:placeholder:text-slate-600" placeholder="e.g. https://pannellum.org/images/alma.jpg">
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold self-center">Quick Examples:</span>
                            <button type="button" class="pano-preset px-3 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-black text-slate-600 dark:text-slate-300 rounded-lg hover:bg-primary/10 hover:text-primary transition-all" data-url="https://pannellum.org/images/alma.jpg">Alma</button>
                            <button type="button" class="pano-preset px-3 py-1 bg-slate-100 dark:bg-slate-800 text-[10px] font-black text-slate-600 dark:text-slate-300 rounded-lg hover:bg-primary/10 hover:text-primary transition-all" data-url="https://pannellum.org/images/cerro-toco-0.jpg">Cerro Toco</button>
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3">Live 3D Preview</span>
                        <div class="preview-container border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden bg-slate-950">
                            <div id="pano-preview"></div>
                            <div id="pano-preview-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center text-slate-500 dark:text-slate-600">
                                <span class="material-symbols-outlined text-4xl mb-2">360</span>
                                <p class="text-xs font-bold">No 360° Panorama URL set.</p>
                                <p class="text-[10px] mt-1">Paste a URL above to load the live 3D preview.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Hidden Coordinates -->
                <input type="hidden" name="coordinates_x" id="coord-x" value="50">
                <input type="hidden" name="coordinates_y" id="coord-y" value="50">

                <button type="submit" class="w-full py-6 bg-slate-800 dark:bg-primary text-white rounded-3xl font-black uppercase tracking-[0.2em] text-sm shadow-2xl shadow-slate-200 dark:shadow-primary/20 hover:bg-slate-900 dark:hover:bg-primary/80 hover:-translate-y-1 active:translate-y-0 transition-all flex items-center justify-center gap-3 group">
                    <span>Save Waypoint</span>
                    <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">rocket_launch</span>
                </button>
            </div>

            <!-- Right: Interactive Map Picker -->
            <div class="col-span-12 lg:col-span-7">
                <div class="sticky top-12 space-y-6">
                    <div class="bg-blue-600 dark:bg-blue-700 p-8 rounded-[2.5rem] shadow-xl shadow-blue-200 dark:shadow-blue-900/30 text-white relative overflow-hidden transition-colors">
                        <div class="relative z-10">
                            <h3 class="text-2xl font-black mb-2 flex items-center gap-3">
                                <span class="material-symbols-outlined text-[32px]">touch_app</span>
                                Map Placement
                            </h3>
                            <p class="text-blue-100 font-medium">Click or tap anywhere on the campus map below to set the precise waypoint location.</p>
                        </div>
                        <span class="material-symbols-outlined absolute -right-8 -bottom-8 text-[200px] text-white/10 rotate-12 pointer-events-none">map</span>
                    </div>

                    <div id="map-picker" class="relative overflow-hidden cursor-crosshair border-[12px] border-white dark:border-slate-900 shadow-2xl rounded-[3rem] bg-slate-200 dark:bg-slate-800 aspect-[4/3] group touch-none transition-colors">
                        <img src="{{ asset('images/campus_map_hires.png') }}" class="w-full h-full object-cover select-none pointer-events-none opacity-90 group-hover:opacity-100 transition-opacity" id="map-image">
                        
                        <!-- Grid Overlay -->
                        <div class="absolute inset-0 opacity-20 dark:opacity-10 pointer-events-none bg-[radial-gradient(circle_at_1px_1px,#fff_1px,transparent_0)] dark:bg-[radial-gradient(circle_at_1px_1px,#000_1px,transparent_0)] bg-[size:40px_40px]"></div>

                        <!-- Drop Pin Indicator -->
                        <div id="drop-pin" class="absolute pointer-events-none" style="display: none; transform: translate(-50%, -100%);">
                            <div class="relative flex flex-col items-center">
                                <!-- Label -->
                                <div class="bg-slate-800 dark:bg-primary text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 shadow-xl whitespace-nowrap animate-bounce">
                                    New Waypoint
                                </div>
                                <!-- Pin Icon -->
                                <div class="relative">
                                    <span class="material-symbols-outlined text-[#4A90E2] dark:text-blue-400 text-[56px] drop-shadow-[0_10px_10px_rgba(0,0,0,0.3)]">location_on</span>
                                    <div class="absolute inset-0 flex items-center justify-center pb-2">
                                        <div class="w-3 h-3 bg-white rounded-full"></div>
                                    </div>
                                </div>
                                <!-- Shadow -->
                                <div class="w-8 h-3 bg-black/20 rounded-full blur-[4px] -mt-2 scale-x-150"></div>
                            </div>
                        </div>

                        <!-- Current Coordinates Display -->
                        <div class="absolute bottom-6 right-6 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-6 py-3 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-800 flex items-center gap-6 transition-colors">
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">X Pos</span>
                                <span id="display-x" class="text-lg font-black text-slate-800 dark:text-slate-100">0.0%</span>
                            </div>
                            <div class="w-px h-8 bg-slate-200 dark:bg-slate-800"></div>
                            <div>
                                <span class="block text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mb-1">Y Pos</span>
                                <span id="display-y" class="text-lg font-black text-slate-800 dark:text-slate-100">0.0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 flex items-center gap-4 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-500">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold text-slate-700 dark:text-slate-200">Check twice before syncing.</p>
                            <p class="text-slate-400 dark:text-slate-500">Coordinates are relative to the map canvas width and height.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection

@section('scripts')
<script>
    const mapPicker = document.getElementById('map-picker');
    const dropPin = document.getElementById('drop-pin');
    const inputX = document.getElementById('coord-x');
    const inputY = document.getElementById('coord-y');
    const displayX = document.getElementById('display-x');
    const displayY = document.getElementById('display-y');

    mapPicker.addEventListener('click', (e) => {
        const rect = mapPicker.getBoundingClientRect();
        
        // Calculate percentages
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        // Update inputs (1 decimal precision)
        const fixedX = x.toFixed(1);
        const fixedY = y.toFixed(1);

        inputX.value = fixedX;
        inputY.value = fixedY;

        // Update display
        displayX.textContent = `${fixedX}%`;
        displayY.textContent = `${fixedY}%`;

        // Move pin
        dropPin.style.display = 'block';
        dropPin.style.left = `${fixedX}%`;
        dropPin.style.top = `${fixedY}%`;

        // Visual feedback
        mapPicker.classList.add('ring-4', 'ring-primary/20');
        setTimeout(() => mapPicker.classList.remove('ring-4', 'ring-primary/20'), 300);
    });

    // Handle touch events for tablets
    mapPicker.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const touch = e.touches[0];
        const rect = mapPicker.getBoundingClientRect();
        
        const x = ((touch.clientX - rect.left) / rect.width) * 100;
        const y = ((touch.clientY - rect.top) / rect.height) * 100;

        const fixedX = x.toFixed(1);
        const fixedY = y.toFixed(1);

        inputX.value = fixedX;
        inputY.value = fixedY;

        displayX.textContent = `${fixedX}%`;
        displayY.textContent = `${fixedY}%`;

        dropPin.style.display = 'block';
        dropPin.style.left = `${fixedX}%`;
        dropPin.style.top = `${fixedY}%`;
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
<script>
    let previewViewer = null;

    function loadPreview(url) {
        // Clear any previous element inside pano-preview
        const container = document.getElementById('pano-preview');
        container.innerHTML = '';
        
        const placeholder = document.getElementById('pano-preview-placeholder');
        if (!url) {
            placeholder.style.display = 'flex';
            placeholder.querySelector('p').textContent = 'No 360° Panorama URL set.';
            return;
        }
        placeholder.style.display = 'none';

        try {
            previewViewer = pannellum.viewer('pano-preview', {
                type: 'equirectangular',
                panorama: url,
                autoLoad: true,
                compass: false,
                showZoomCtrl: false,
                showFullscreenCtrl: false,
                showControls: true
            });
        } catch (e) {
            console.error('Failed to load Pannellum preview:', e);
            placeholder.style.display = 'flex';
            placeholder.querySelector('p').textContent = 'Preview failed to load. Check URL CORS settings.';
        }
    }

    function updateImagePreview(url) {
        const wrapper = document.getElementById('image-preview-wrapper');
        const img = document.getElementById('image-preview');
        if (url) {
            img.src = url;
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
        }
    }

    // Event listeners
    const panoInput = document.getElementById('pano-url-input');
    const imageInput = document.getElementById('image-url-input');
    const clearPanoBtn = document.getElementById('btn-clear-pano');

    // Debounce preview rendering
    let panoTimeout;
    panoInput.addEventListener('input', (e) => {
        clearTimeout(panoTimeout);
        panoTimeout = setTimeout(() => {
            loadPreview(e.target.value);
        }, 800);
    });

    let imageTimeout;
    imageInput.addEventListener('input', (e) => {
        clearTimeout(imageTimeout);
        imageTimeout = setTimeout(() => {
            updateImagePreview(e.target.value);
        }, 500);
    });

    clearPanoBtn.addEventListener('click', () => {
        panoInput.value = '';
        loadPreview('');
    });

    // Preset buttons
    document.querySelectorAll('.pano-preset').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.getAttribute('data-url');
            panoInput.value = url;
            loadPreview(url);
        });
    });

    // Initial load
    window.addEventListener('load', () => {
        loadPreview(panoInput.value);
        updateImagePreview(imageInput.value);
    });
</script>
@endsection
