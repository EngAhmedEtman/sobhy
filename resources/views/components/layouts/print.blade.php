<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title ?? 'طباعة التقرير'); ?></title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind CSS (CDN to avoid disk space issues) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { sans: ['Cairo', 'sans-serif'] },
            colors: {
                primary: { 50: '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0', 300: '#86efac', 400: '#4ade80', 500: '#22c55e', 600: '#16a34a', 700: '#15803d', 800: '#166534', 900: '#14532d' },
                danger: { 50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5', 400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c', 800: '#991b1b', 900: '#7f1d1d' }
            }
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; }
        
        /* Force print modifiers to apply on screen in this layout (preview mode) */
        .print\:hidden { display: none !important; }
        .print\:block { display: block !important; }
        .no-print { display: none !important; }

        /* Global Print Layout Styling (Applies to both preview and actual print) */
        html, body { 
            background: #fff !important; 
            margin: 0 !important; 
            padding: 0 !important; 
            color: #000 !important;
        }
        
        /* Hide URL printing and margins in some browsers */
        @media print {
            @page { margin: 0.5cm; }
        }

        /* Strip web UI styling for a formal paper look */
        * {
            box-shadow: none !important;
            text-shadow: none !important;
        }
        
        /* Remove background colors from summary cards and badges, make borders black */
        .bg-slate-50, .bg-primary-50, .bg-success-50, .bg-danger-50, .bg-warning-50, .bg-indigo-50, .bg-amber-50, .bg-slate-100 {
            background: transparent !important;
        }
        
        /* Force text colors to black/dark gray for readability */
        .text-primary-600, .text-primary-700, .text-success-600, .text-danger-600, .text-warning-600, .text-indigo-600, .text-amber-600, .text-slate-500, .text-slate-600, .text-slate-400 {
            color: #333 !important;
        }

        /* Professional Table Formatting */
        .table-container table {
            width: 100% !important;
            border-collapse: collapse !important;
            border: 1px solid #cbd5e1 !important; /* slate-300 */
            margin-bottom: 20px !important;
        }
        .table-container thead {
            display: table-header-group !important;
        }
        .table-container tr {
            page-break-inside: avoid !important;
        }
        .table-container th, .table-container td {
            border: 1px solid #cbd5e1 !important; /* slate-300 */
            padding: 4px 8px !important;
            color: #1e293b !important; /* slate-800 */
            border-radius: 0 !important;
            white-space: nowrap !important;
            font-size: 0.75rem !important;
        }
        .table-container th {
            background-color: #f8fafc !important; /* slate-50 */
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            font-weight: bold !important;
        }

        /* Fix rounded corners and borders on cards */
        .rounded-xl, .rounded-2xl, .rounded-lg, .rounded-md {
            border-radius: 0 !important;
        }
        .border-primary-100, .border-success-100, .border-warning-100, .border-danger-100, .border-indigo-100, .border-amber-100, .border-slate-100, .border-slate-200 {
            border-color: #444 !important;
            border-width: 1px !important;
        }

        /* Hide SVG icons inside summary cards to keep them strictly text/numbers */
        .p-1\.5 svg, .bg-slate-50 svg {
            display: none !important;
        }


        
        /* Fix horizontal scroll issue */
        .overflow-x-auto, .overflow-hidden {
            overflow: visible !important;
        }

        /* Repeating Print Footer at Absolute Bottom (ONLY IN PRINT) */
        @media print {
            #print-wrapper {
                zoom: 1 !important;
                transform: none !important;
                width: 100% !important;
                min-width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .page-container {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .table-container {
                max-width: 100% !important;
                width: 100% !important;
            }
            .footer-space {
                height: 120px; /* Space reserved for the footer */
            }
            .fixed-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background: #fff;
                padding-bottom: 20px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-white text-slate-800" style="overflow-x: hidden; margin: 0; padding: 0;">

    <div id="print-wrapper" style="width: 100%; transform-origin: top right;">
        <!-- The main layout table used to reserve space for the footer -->
        <table style="width: 100%; border: none !important;" class="!border-none !m-0 layout-table">
            <thead class="!border-none"><tr class="!border-none"><td class="!border-none !p-0">
                <div class="h-4"></div>
            </td></tr></thead>
            <tbody class="!border-none"><tr class="!border-none"><td class="!border-none !p-0">
                <div class="page-container w-full max-w-5xl mx-auto p-4 sm:p-8 table-container">
                    {{ $slot }}
                </div>
            </td></tr></tbody>
            <tfoot class="!border-none"><tr class="!border-none"><td class="!border-none !p-0">
                <!-- This empty div reserves space at the bottom of the table on every page in print, preventing overlap with the fixed footer -->
                <div class="footer-space"></div>
            </td></tr></tfoot>
        </table>

        <!-- The repeating footer element (Flows normally on screen, fixed to bottom on print) -->
        <div class="fixed-footer px-4 sm:px-8 mt-6">
            <div class="max-w-5xl mx-auto">
                <x-print.footer />
            </div>
        </div>
    </div>

    <script>
        function autoFitPrintSheet() {
            if (window.matchMedia('print').matches) return;
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) return;

            const w = window.innerWidth;
            const wrapper = document.getElementById('print-wrapper');
            if (!wrapper) return;
            
            const targetWidth = 794;
            if (w > 0 && w < targetWidth) {
                const scale = (w - 4) / targetWidth;
                wrapper.style.width = targetWidth + 'px';
                wrapper.style.minWidth = targetWidth + 'px';
                wrapper.style.zoom = scale;
                if (!('zoom' in wrapper.style) || navigator.userAgent.includes('Firefox')) {
                    wrapper.style.transform = `scale(${scale})`;
                    wrapper.style.transformOrigin = 'top right';
                }
            } else {
                wrapper.style.width = '100%';
                wrapper.style.minWidth = 'unset';
                wrapper.style.zoom = '1';
                wrapper.style.transform = 'none';
            }
        }

        window.addEventListener('beforeprint', () => {
            const wrapper = document.getElementById('print-wrapper');
            if (wrapper) {
                wrapper.style.zoom = '1';
                wrapper.style.transform = 'none';
                wrapper.style.width = '100%';
                wrapper.style.minWidth = '100%';
            }
        });

        window.addEventListener('afterprint', () => {
            autoFitPrintSheet();
        });

        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autoprint')) {
                const wrapper = document.getElementById('print-wrapper');
                if (wrapper) {
                    wrapper.style.zoom = '1';
                    wrapper.style.transform = 'none';
                    wrapper.style.width = '100%';
                    wrapper.style.minWidth = '100%';
                }
                setTimeout(() => { window.print(); }, 400);
            } else {
                autoFitPrintSheet();
                setTimeout(autoFitPrintSheet, 200);
            }
        });
        window.addEventListener('resize', autoFitPrintSheet);
    </script>
</body>
</html>
