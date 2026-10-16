<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Campus Guide') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary":   "#4A90E2",
                        "secondary": "#006948",
                        "success":   "#48BB78",
                        "warning":   "#ED8936",
                        "surface":            "#F7F9FC",
                        "surface-container":  "#EDF1F7",
                        "surface-container-low":    "#F0F4FA",
                        "surface-container-lowest": "#FFFFFF",
                        "on-surface":         "#2D3748",
                        "on-surface-variant": "#718096",
                        "outline-variant":    "#CBD5E0",
                    },
                    fontFamily: {
                        "headline": ["Manrope", "sans-serif"],
                        "body":     ["Plus Jakarta Sans", "sans-serif"],
                        "label":    ["Plus Jakarta Sans", "sans-serif"],
                        "manrope":  ["Manrope", "sans-serif"],
                    },
                },
            },
        }
    </script>

    <script>
        // Dark Mode Logic - Isolated to Admin
        (function() {
            const storedTheme = localStorage.getItem('theme');
            const isAdmin = window.location.pathname.includes('/admin');
            
            // If in admin area and no preference yet, default to dark
            // Otherwise follow stored preference
            if (isAdmin && (storedTheme === 'dark' || storedTheme === null)) {
                document.documentElement.classList.add('dark');
            } else if (storedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 16px; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Manrope', sans-serif; }

        .map-container {
            background-image: radial-gradient(circle at 2px 2px, #c8d3df 1px, transparent 0);
            background-size: 36px 36px;
            background-color: #F0F4FA;
        }

        /* Shimmer skeleton */
        @keyframes shimmer {
            0%   { background-position: -600px 0; }
            100% { background-position: 600px 0; }
        }
        .skeleton {
            background: linear-gradient(90deg, #e8edf3 25%, #f5f7fa 50%, #e8edf3 75%);
            background-size: 600px 100%;
            animation: shimmer 1.4s infinite linear;
            border-radius: 0.75rem;
        }

        /* Pulse ring on current-location */
        @keyframes pulse-ring {
            0%   { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(74,144,226,0.5); }
            70%  { transform: scale(1);   box-shadow: 0 0 0 10px rgba(74,144,226,0); }
            100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(74,144,226,0); }
        }
        .pulse-ring { animation: pulse-ring 2s ease-out infinite; }

        /* Waypoint pop-in */
        @keyframes pin-pop {
            0%   { transform: translate(-50%,-100%) scale(0); opacity: 0; }
            70%  { transform: translate(-50%,-100%) scale(1.15); opacity: 1; }
            100% { transform: translate(-50%,-100%) scale(1); opacity: 1; }
        }
        .pin-pop { animation: pin-pop 0.25s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* Waypoint bounce on click */
        @keyframes pin-bounce {
            0%,100% { transform: translate(-50%,-100%) scale(1); }
            40%      { transform: translate(-50%,-115%) scale(1.2); }
        }
        .pin-bounce { animation: pin-bounce 0.35s ease; }

        /* Modal entrance */
        @keyframes modal-enter {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1)   translateY(0); }
        }
        .modal-enter { animation: modal-enter 0.2s ease-out forwards; }

        /* Confetti burst */
        @keyframes confetti-fly {
            0%   { transform: translate(0,0) scale(1); opacity: 1; }
            100% { transform: var(--tx) scale(0); opacity: 0; }
        }
        .confetti-dot {
            position: absolute; width: 10px; height: 10px; border-radius: 50%;
            animation: confetti-fly 0.7s ease-out forwards;
        }

        /* Sidebar collapsible rail */
        #left-rail { transition: width 0.3s cubic-bezier(0.4,0,0.2,1); }
        #left-rail .rail-label { transition: opacity 0.2s, width 0.2s; }
        #left-rail.collapsed .rail-label { opacity: 0; width: 0; overflow: hidden; }
        #left-rail.expanded  .rail-label { opacity: 1; width: auto; }

        /* Clock */
        #nav-clock { font-variant-numeric: tabular-nums; }
    </style>
    @yield('styles')
</head>
<body class="bg-surface dark:bg-slate-950 text-on-surface dark:text-slate-100 transition-colors duration-300">
    @yield('content')
    @yield('scripts')
</body>
</html>
