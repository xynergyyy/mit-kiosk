<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $waypoint->name }} — 3D Experience | MIT Kiosk</title>
    <meta name="description" content="Explore {{ $waypoint->name }} in an immersive 360° panoramic view.">

    <!-- Tailwind + Fonts (matching Stitch design system) -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Pannellum 3D Viewer -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css"/>
    <script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#005da7",
                        "on-primary": "#eef3ff",
                        "surface": "#f5f7f9",
                        "on-surface": "#2c2f31",
                        "on-surface-variant": "#595c5e",
                        "surface-container-lowest": "#ffffff",
                    },
                    fontFamily: {
                        "manrope": ["Manrope"],
                        "inter": ["Inter"],
                    }
                }
            }
        }
    </script>

    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .glass-panel {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        /* Pannellum container must fill parent */
        #panorama-container { width: 100%; height: 100%; }
        /* Override Pannellum branding bar */
        .pnlm-about-msg { display: none !important; }
        /* Style fallback image container */
        #fallback-view {
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: auto;
            background: #0b0f10;
            position: relative;
        }
        #fallback-view img {
            height: 100%;
            width: auto;
            display: block;
        }
    </style>
</head>

<body class="bg-black overflow-hidden h-screen w-screen">

    <!-- ===== TOP NAVBAR ===== -->
    <header class="fixed top-0 left-0 w-full z-50 flex justify-between items-center px-6 py-3 bg-black/60 glass-panel border-b border-white/10">
        <div class="flex items-center gap-4">
            <a href="{{ route('visitor.kiosk') }}"
               class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold transition-all active:scale-95 text-sm">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Back to Map
            </a>
        </div>

        <div class="glass-panel bg-white/10 px-6 py-2 rounded-xl border border-white/20 text-center">
            <p class="text-white/50 text-[10px] font-black uppercase tracking-widest">Now Viewing</p>
            <h1 class="text-white font-extrabold font-manrope text-base leading-tight">{{ $waypoint->name }}</h1>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-[10px] text-white/40 font-bold uppercase tracking-widest px-3 py-1.5 bg-white/5 rounded-lg border border-white/10">{{ $waypoint->location_code }}</span>
        </div>
    </header>

    <!-- ===== LEFT SIDEBAR CONTROLS ===== -->
    <aside id="viewer-controls" class="fixed left-4 top-1/2 -translate-y-1/2 flex flex-col gap-2 p-2 z-40 bg-black/50 glass-panel rounded-2xl border border-white/10 shadow-2xl">
        <button id="btn-zoom-in" title="Zoom In" class="flex flex-col items-center justify-center p-3 gap-1 hover:bg-white/10 transition-colors rounded-xl text-white active:scale-95">
            <span class="material-symbols-outlined">add</span>
            <span class="font-manrope text-[9px] font-bold text-white/60">Zoom In</span>
        </button>
        <button id="btn-zoom-out" title="Zoom Out" class="flex flex-col items-center justify-center p-3 gap-1 hover:bg-white/10 transition-colors rounded-xl text-white active:scale-95">
            <span class="material-symbols-outlined">remove</span>
            <span class="font-manrope text-[9px] font-bold text-white/60">Zoom Out</span>
        </button>
        <div class="h-px bg-white/10 mx-2"></div>
        <button id="btn-reset" title="Reset View" class="flex flex-col items-center justify-center p-3 gap-1 text-primary bg-white/10 rounded-xl active:scale-95">
            <span class="material-symbols-outlined">restart_alt</span>
            <span class="font-manrope text-[9px] font-bold text-white/60">Reset</span>
        </button>
    </aside>

    <!-- ===== MAIN PANORAMA VIEWPORT ===== -->
    <main class="w-full h-full">
        <!-- Pannellum 3D Viewer (primary) -->
        <div id="panorama-container"></div>

        <!-- Static image fallback (shown if Pannellum fails) -->
        <div id="fallback-view">
            <img id="fallback-img" src="{{ $waypoint->pano_url }}" alt="360° view of {{ $waypoint->name }}"/>
            <div class="absolute top-24 left-1/2 -translate-x-1/2 glass-panel bg-amber-500/20 border border-amber-400/30 text-amber-300 rounded-xl px-6 py-3 text-sm font-bold flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">warning</span>
                Interactive 3D viewer unavailable — showing static preview. Scroll horizontally to explore.
            </div>
        </div>
    </main>

    <script type="application/json" id="pano-data">{!! json_encode(['panoUrl' => $waypoint->pano_url, 'hotspots' => $hotspots, 'waypointName' => $waypoint->name]) !!}</script>

    <!-- ===== JAVASCRIPT ===== -->
    <script>
        const panoData = JSON.parse(document.getElementById('pano-data')?.textContent || '{}');
        const panoUrl = panoData.panoUrl;
        const hotspots = Array.isArray(panoData.hotspots) ? panoData.hotspots : [];
        const waypointName = panoData.waypointName;

        // Build Pannellum hotspot config from sibling waypoints
        const pannellumHotspots = hotspots.map((wp, index) => ({
            pitch: -5,
            yaw: (index * (360 / Math.max(hotspots.length, 1))), // spread evenly
            type: 'custom',
            text: wp.name,
            cssClass: 'pano-hotspot',
            clickHandlerFunc: () => {
                window.location.href = `/panorama/${wp.id}`;
            }
        }));

        // --- INIT PANNELLUM ---
        let viewer = null;

        function initPannellum() {
            try {
                viewer = pannellum.viewer('panorama-container', {
                    type: 'equirectangular',
                    panorama: panoUrl,
                    autoLoad: true,
                    autoRotate: -1.5,
                    autoRotateInactivityDelay: 3000,
                    showZoomCtrl: false,    // we use our own buttons
                    showFullscreenCtrl: false,
                    compass: false,
                    hotSpots: pannellumHotspots,
                    hfov: 100,
                    minHfov: 50,
                    maxHfov: 130,
                    strings: {
                        loadingLabel: 'Loading 3D Experience...',
                    }
                });

                // Wire sidebar controls to Pannellum API
                document.getElementById('btn-zoom-in').addEventListener('click', () => {
                    viewer.setHfov(viewer.getHfov() - 15);
                });
                document.getElementById('btn-zoom-out').addEventListener('click', () => {
                    viewer.setHfov(viewer.getHfov() + 15);
                });
                document.getElementById('btn-reset').addEventListener('click', () => {
                    viewer.setHfov(100);
                    viewer.setPitch(0);
                    viewer.setYaw(0);
                });

            } catch (error) {
                console.error('Pannellum failed to load:', error);
                showStaticFallback();
            }
        }

        // --- STATIC FALLBACK ---
        function showStaticFallback() {
            document.getElementById('panorama-container').style.display = 'none';
            document.getElementById('viewer-controls').style.display = 'none';
            document.getElementById('fallback-view').style.display = 'flex';
        }

        // If no pano_url set, redirect back to kiosk
        if (!panoUrl) {
            window.location.href = '{{ route("visitor.kiosk") }}';
        } else {
            // Small delay to let Pannellum CDN script fully run
            window.addEventListener('load', () => {
                if (typeof pannellum !== 'undefined') {
                    initPannellum();
                } else {
                    console.warn('Pannellum.js not available — falling back to static view');
                    showStaticFallback();
                }
            });
        }
    </script>

    <!-- Custom Hotspot Styles (injected after Pannellum loads) -->
    <style>
        .pano-hotspot {
            width: 32px;
            height: 32px;
            background: #005da7;
            border: 3px solid white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 0 6px rgba(0,93,167,0.3);
            animation: pulse-hotspot 2s infinite;
        }
        @keyframes pulse-hotspot {
            0%, 100% { box-shadow: 0 0 0 4px rgba(0,93,167,0.3); }
            50% { box-shadow: 0 0 0 10px rgba(0,93,167,0.1); }
        }
        .pnlm-tooltip span {
            background: rgba(0,0,0,0.8);
            color: white;
            font-family: 'Manrope', sans-serif;
            font-weight: bold;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 8px;
        }
    </style>
</body>
</html>
