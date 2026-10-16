<?php

return [
    'pc_name' => env('PRINTER_PC_NAME', '127.0.0.1'),
    'share_name' => env('PRINTER_SHARE_NAME', 'POS-58'),
    'enabled' => filter_var(env('PRINTER_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
    'orientation' => env('PRINTER_ORIENTATION', 'portrait'),
    'browser_print_fallback' => filter_var(env('KIOSK_BROWSER_PRINT_FALLBACK', false), FILTER_VALIDATE_BOOLEAN),
];
