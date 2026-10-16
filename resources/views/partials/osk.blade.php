<div id="virtual-keyboard"
    class="fixed bottom-10 left-1/2 -translate-x-1/2 w-[95%] max-w-[880px] bg-slate-900/90 backdrop-blur-2xl border border-white/10 p-6 rounded-[2.5rem] shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] z-[9999] select-none osk-hidden ring-1 ring-white/20">
    
    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent rounded-[2.5rem] pointer-events-none"></div>
    
    <!-- Keyboard Header -->
    <div class="flex items-center justify-between px-4 mb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                <span class="material-symbols-outlined text-2xl">keyboard</span>
            </div>
            <div>
                <h3 class="text-white font-bold text-sm tracking-tight leading-none">VIRTUAL KEYBOARD</h3>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-1">Touch Optimized Control</p>
            </div>
        </div>

        <!-- Drag Handle -->
        <div id="osk-handle" class="flex-1 flex justify-center py-4 cursor-grab active:cursor-grabbing hover:bg-white/5 rounded-2xl mx-8 transition-colors">
            <div class="w-16 h-1.5 bg-slate-700 rounded-full"></div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" onclick="window.resetKeyboardPosition()" title="Reset Position"
                class="w-12 h-12 flex flex-col items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 rounded-2xl transition-all active:scale-90 group">
                <span class="material-symbols-outlined text-2xl group-hover:rotate-180 transition-transform duration-500">recenter</span>
                <span class="text-[9px] font-black mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">RESET</span>
            </button>
            <button type="button" onclick="window.hideKeyboard()" title="Close Keyboard"
                class="w-12 h-12 flex flex-col items-center justify-center text-slate-400 hover:text-white hover:bg-red-500/20 rounded-2xl transition-all active:scale-90 group">
                <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">close</span>
                <span class="text-[9px] font-black mt-0.5 opacity-0 group-hover:opacity-100 transition-opacity">CLOSE</span>
            </button>
        </div>
    </div>

    <div class="p-4 space-y-4">
        <!-- Keyboard Rows -->
        <div class="space-y-4">
            <!-- Row 1 -->
            <div class="flex justify-center gap-4">
                <button type="button" class="key" onclick="handleKey('1')">1</button>
                <button type="button" class="key" onclick="handleKey('2')">2</button>
                <button type="button" class="key" onclick="handleKey('3')">3</button>
                <button type="button" class="key" onclick="handleKey('4')">4</button>
                <button type="button" class="key" onclick="handleKey('5')">5</button>
                <button type="button" class="key" onclick="handleKey('6')">6</button>
                <button type="button" class="key" onclick="handleKey('7')">7</button>
                <button type="button" class="key" onclick="handleKey('8')">8</button>
                <button type="button" class="key" onclick="handleKey('9')">9</button>
                <button type="button" class="key" onclick="handleKey('0')">0</button>
                <button type="button" class="key key-special bg-white/20 w-24" onclick="handleKey('Backspace')">
                    <span class="material-symbols-outlined text-2xl">backspace</span>
                </button>
            </div>
            <!-- Row 2 -->
            <div class="flex justify-center gap-4">
                <button type="button" class="key" onclick="handleKey('q')">q</button>
                <button type="button" class="key" onclick="handleKey('w')">w</button>
                <button type="button" class="key" onclick="handleKey('e')">e</button>
                <button type="button" class="key" onclick="handleKey('r')">r</button>
                <button type="button" class="key" onclick="handleKey('t')">t</button>
                <button type="button" class="key" onclick="handleKey('y')">y</button>
                <button type="button" class="key" onclick="handleKey('u')">u</button>
                <button type="button" class="key" onclick="handleKey('i')">i</button>
                <button type="button" class="key" onclick="handleKey('o')">o</button>
                <button type="button" class="key" onclick="handleKey('p')">p</button>
            </div>
            <!-- Row 3 -->
            <div class="flex justify-center gap-4">
                <button type="button" class="key" onclick="handleKey('a')">a</button>
                <button type="button" class="key" onclick="handleKey('s')">s</button>
                <button type="button" class="key" onclick="handleKey('d')">d</button>
                <button type="button" class="key" onclick="handleKey('f')">f</button>
                <button type="button" class="key" onclick="handleKey('g')">g</button>
                <button type="button" class="key" onclick="handleKey('h')">h</button>
                <button type="button" class="key" onclick="handleKey('j')">j</button>
                <button type="button" class="key" onclick="handleKey('k')">k</button>
                <button type="button" class="key" onclick="handleKey('l')">l</button>
                <button type="button" class="key" onclick="handleKey('@')">@</button>
            </div>
            <!-- Row 4 -->
            <div class="flex justify-center gap-4">
                <button type="button" class="key key-special key-shift w-24 bg-white/20" onclick="handleKey('Shift')">
                    <span class="material-symbols-outlined text-2xl">shift</span>
                </button>
                <button type="button" class="key" onclick="handleKey('z')">z</button>
                <button type="button" class="key" onclick="handleKey('x')">x</button>
                <button type="button" class="key" onclick="handleKey('c')">c</button>
                <button type="button" class="key" onclick="handleKey('v')">v</button>
                <button type="button" class="key" onclick="handleKey('b')">b</button>
                <button type="button" class="key" onclick="handleKey('n')">n</button>
                <button type="button" class="key" onclick="handleKey('m')">m</button>
                <button type="button" class="key" onclick="handleKey('.')">.</button>
                <button type="button" class="key key-special key-done w-32 bg-blue-600 text-white hover:bg-blue-500" onclick="window.hideKeyboard()">DONE</button>
            </div>
            <!-- Row 5 -->
            <div class="flex justify-center gap-4">
                <button type="button" class="key key-special w-[680px] bg-white/20" onclick="handleKey(' ')">SPACE BAR</button>
            </div>
        </div>
    </div>
</div>

<style>
    .key {
        @apply h-[60px] w-[60px] flex items-center justify-center bg-white/5 hover:bg-white/10 active:bg-blue-500 transition-all text-white font-extrabold rounded-[1.25rem] border border-white/5 shadow-[0_4px_12px_rgba(0,0,0,0.2)] active:scale-90 active:shadow-inner;
        font-family: 'Manrope', 'Inter', system-ui, sans-serif;
        font-size: 1.4rem;
        letter-spacing: -0.02em;
    }
    .key-special {
        @apply font-black text-[10px] uppercase tracking-tighter bg-white/10;
        font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    }
    .key-shift-active {
        @apply bg-blue-600 text-white border-blue-400 shadow-[0_0_25px_rgba(37,99,235,0.6)] !important;
    }
    .key-done {
        @apply bg-blue-600 text-white border-blue-400 shadow-[0_8px_20px_rgba(37,99,235,0.3)] hover:bg-blue-500 active:bg-blue-700 !important;
    }
    .osk-hidden {
        display: none !important;
    }
    #virtual-keyboard:not(.osk-hidden) {
        display: block !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log("OSK: Script loaded and DOM ready.");
        
        const kb = document.getElementById('virtual-keyboard');
        const handle = document.getElementById('osk-handle');
        
        if (!kb || !handle) {
            console.error("OSK: Keyboard or handle element not found!");
            return;
        }

        let currentInput = null;
        window.oskEnabled = false;

        // Draggable Implementation
        let isDragging = false;
        let offsetX, offsetY;

        handle.addEventListener('mousedown', (e) => {
            isDragging = true;
            const rect = kb.getBoundingClientRect();
            offsetX = e.clientX - rect.left;
            offsetY = e.clientY - rect.top;
            kb.style.transition = 'none';
            kb.style.transform = 'none';
            kb.style.left = `${rect.left}px`;
            kb.style.top = `${rect.top}px`;
            kb.style.bottom = 'auto';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            kb.style.left = `${e.clientX - offsetX}px`;
            kb.style.top = `${e.clientY - offsetY}px`;
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            kb.style.transition = 'all 0.5s ease-[cubic-bezier(0.34,1.56,0.64,1)]';
        });

        // Touch Support
        handle.addEventListener('touchstart', (e) => {
            isDragging = true;
            const touch = e.touches[0];
            const rect = kb.getBoundingClientRect();
            offsetX = touch.clientX - rect.left;
            offsetY = touch.clientY - rect.top;
            kb.style.transition = 'none';
            kb.style.transform = 'none';
            kb.style.left = `${rect.left}px`;
            kb.style.top = `${rect.top}px`;
            kb.style.bottom = 'auto';
        });

        document.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const touch = e.touches[0];
            kb.style.left = `${touch.clientX - offsetX}px`;
            kb.style.top = `${touch.clientY - offsetY}px`;
        });

        document.addEventListener('touchend', () => {
            isDragging = false;
            kb.style.transition = 'all 0.5s ease-[cubic-bezier(0.34,1.56,0.64,1)]';
        });

        window.toggleOSK = function() {
            window.oskEnabled = !window.oskEnabled;
            console.log("OSK: Toggled. Enabled:", window.oskEnabled);
            if (!window.oskEnabled) {
                window.hideKeyboard();
            } else {
                const active = document.activeElement;
                if (active && active.dataset.osk === "1") {
                    window.showKeyboard(active);
                }
            }
            return window.oskEnabled;
        };

        window.showKeyboard = function(input) {
            if (!window.oskEnabled) return;
            currentInput = input;
            kb.classList.remove('osk-hidden');
            console.log("OSK: Showing for input", input.name);
            
            setTimeout(() => {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
        };

        window.hideKeyboard = function() {
            kb.classList.add('osk-hidden');
            currentInput = null;
            console.log("OSK: Hidden.");
        };

        window.resetKeyboardPosition = function() {
            console.log("OSK: Resetting position.");
            kb.style.top = '';
            kb.style.left = '';
            kb.style.bottom = '';
            kb.style.transform = '';
            kb.style.transition = 'all 0.5s cubic-bezier(0.34,1.56,0.64,1)';
        };

        let isShift = false;

        window.handleKey = function(key) {
            if (!currentInput) return;
            
            if (key === 'Enter') {
                window.hideKeyboard();
                currentInput.blur();
                return;
            } else if (key === 'Backspace') {
                currentInput.value = currentInput.value.slice(0, -1);
            } else if (key === 'Shift') {
                isShift = !isShift;
                document.querySelectorAll('.key-shift').forEach(btn => btn.classList.toggle('key-shift-active', isShift));
                
                // Update key labels
                document.querySelectorAll('.key:not(.key-special)').forEach(btn => {
                    const char = btn.textContent.trim();
                    if (char.length === 1) {
                        btn.textContent = isShift ? char.toUpperCase() : char.toLowerCase();
                    }
                });
                return;
            } else if (key === ' ') {
                currentInput.value += ' ';
            } else {
                let char = key;
                if (isShift) {
                    char = key.toUpperCase();
                    // Auto-toggle shift off after one char (optional mobile style)
                    // isShift = false;
                    // updateShiftUI();
                }
                currentInput.value += char;
            }

            currentInput.dispatchEvent(new Event('input'));
            currentInput.focus(); // Keep focus
        };

        // Auto-attach
        document.addEventListener('focusin', (e) => {
            if (e.target.dataset.osk === "1") {
                window.showKeyboard(e.target);
            }
        });

        // Close outside
        document.addEventListener('mousedown', (e) => {
            if (!kb.contains(e.target) && !e.target.dataset.osk && !e.target.closest('button')) {
                window.hideKeyboard();
            }
        });
    });
</script>
