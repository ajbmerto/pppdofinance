<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Budget & BUR Tracker</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> var url = "<?php echo url('/'); ?>"; </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom scrollbar for beautiful UX */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Highlight burgundy color matching user's image */
        .color-burgundy {
            background-color: #800000;
        }
        .text-burgundy {
            color: #800000;
        }
        .border-burgundy {
            border-color: #800000;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased">

    <!-- Top Navigation Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-0 md:h-16 flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-red-50 rounded-lg text-red-700 color-burgundy text-white shrink-0">
                    <!-- SVG Dashboard Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg md:text-xl font-bold tracking-tight text-slate-900">PPPDO Financial Dashboard</h1>
                    <p class="text-[11px] md:text-xs text-slate-500 font-medium">As of <?php echo date("F d, Y"); ?></p>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 w-full md:w-auto justify-end">
                <button onclick="exportToCSV()" class="flex-1 md:flex-none flex items-center justify-center space-x-2 px-3 py-2.5 md:py-2 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 text-slate-700 text-xs md:text-sm font-semibold transition shadow-sm min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Export CSV</span>
                </button>
                <a href="{{route('add')}}" class="flex-1 md:flex-none flex items-center justify-center space-x-2 px-3 py-2.5 md:py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs md:text-sm font-semibold transition shadow-sm min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Utilization</span>
                </a>
                <!-- <button onclick="openModal('add')" class="flex-1 md:flex-none flex items-center justify-center space-x-2 px-3 py-2.5 md:py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-xs md:text-sm font-semibold transition shadow-sm min-h-[44px]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Budget Line</span>
                </button> -->
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">
        
        <!-- Toast / Message Box (Custom alert alternative) -->
        <div id="toast" class="hidden fixed bottom-5 left-5 right-5 sm:left-auto sm:right-5 z-50 transform translate-y-10 opacity-0 transition-all duration-300 ease-out bg-slate-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center space-x-3">
            <span id="toast-icon" class="text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <span id="toast-message" class="text-xs md:text-sm font-medium">Data successfully saved.</span>
        </div>

        <!-- KPI Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            <!-- 1. Total Budget -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-row sm:flex-col justify-between items-center sm:items-start hover:shadow-md transition">
                <div class="flex sm:flex-row items-center justify-between w-full">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Beg. Total Budget</span>
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="mt-0 sm:mt-4 text-right sm:text-left">
                    <h3 id="kpi-total-budget" class="text-lg md:text-2xl font-bold text-slate-900">$0.00</h3>
                    <p class="text-[10px] md:text-xs text-slate-500 mt-0.5">Authorized Allocations</p>
                </div>
            </div>

            <!-- 2. Total Obligations -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-row sm:flex-col justify-between items-center sm:items-start hover:shadow-md transition">
                <div class="flex sm:flex-row items-center justify-between w-full">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Obligations</span>
                    <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </span>
                </div>
                <div class="mt-0 sm:mt-4 text-right sm:text-left">
                    <h3 id="kpi-total-obligations" class="text-lg md:text-2xl font-bold text-slate-900">Php 0.00</h3>
                    <p class="text-[10px] md:text-xs text-emerald-600 font-semibold mt-0.5">As of <?php echo date("F Y"); ?></p>
                </div>
            </div>

            <!-- 3. Overall Projected BUR -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-row sm:flex-col justify-between items-center sm:items-start hover:shadow-md transition w-full">
                <div class="flex sm:flex-row items-center justify-between w-full">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Projected BUR</span>
                    <span class="p-2 bg-purple-50 text-purple-600 rounded-lg hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </span>
                </div>
                <div class="mt-0 sm:mt-4 text-right sm:text-left w-full sm:w-auto">
                    <h3 id="kpi-average-bur" class="text-lg md:text-2xl font-bold text-slate-900">0.00%</h3>
                    <div class="hidden sm:block w-32 md:w-full bg-slate-100 rounded-full h-1.5 mt-2">
                        <div id="kpi-bur-bar" class="bg-purple-600 h-1.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- 4. Remaining Balance (Highlighted in Burgundy Theme) -->
            <div class="color-burgundy text-white p-5 md:p-6 rounded-2xl shadow-md flex flex-row sm:flex-col justify-between items-center sm:items-start hover:shadow-lg transition">
                <div class="flex sm:flex-row items-center justify-between w-full">
                    <span class="text-xs font-bold uppercase tracking-wider text-red-200">Remaining Balance Q2</span>
                    <span class="p-2 bg-red-950/50 text-red-200 rounded-lg hidden sm:block">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                    </span>
                </div>
                <div class="mt-0 sm:mt-4 text-right sm:text-left">
                    <h3 id="kpi-remaining-balance" class="text-lg md:text-2xl font-bold">Php 0.00</h3>
                    <p class="text-[10px] md:text-xs text-red-200/80 mt-0.5">Critically tracked column</p>
                </div>
            </div>
        </section>

        <!-- Charts Section -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Allocation Comparison Chart -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-xs lg:col-span-2">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h4 class="text-sm md:text-md font-bold text-slate-900">Budget Distribution</h4>
                        <p class="text-[11px] text-slate-500">Breakdown of baseline budget, obligations and procurement</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px] text-slate-600">
                        <span class="flex items-center space-x-1"><span class="inline-block w-2.5 h-2.5 bg-indigo-500 rounded-full"></span> <span>Budget</span></span>
                        <span class="flex items-center space-x-1"><span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full"></span> <span>Obligations</span></span>
                        <span class="flex items-center space-x-1"><span class="inline-block w-2.5 h-2.5 bg-amber-500 rounded-full"></span> <span>Procurement</span></span>
                    </div>
                </div>
                <div class="h-64 sm:h-72 md:h-80 w-full relative">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <!-- Mini Simulation & BUR Gauge -->
            <div class="bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                <div>
                    <h4 class="text-sm md:text-md font-bold text-slate-900 mb-1">Financial Forecaster</h4>
                    <p class="text-[11px] text-slate-500 mb-4 md:mb-6">Convert "Under Procurement" pipeline to simulated real Obligations to watch BUR surge.</p>
                    
                    <div class="space-y-5">
                        <!-- Slider 1 -->
                        <div>
                            <div class="flex justify-between text-xs font-semibold mb-2">
                                <span class="text-slate-600">Procurement Conversion Rate</span>
                                <span id="slider-val" class="text-indigo-600 font-bold">50%</span>
                            </div>
                            <!-- Touch target optimized: padding and height spacing -->
                            <div class="py-2">
                                <input id="conversion-slider" type="range" min="0" max="100" value="50" class="w-full h-3 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600 focus:outline-none" oninput="runSimulation()">
                            </div>
                            <span class="text-[10px] text-slate-400 block mt-1">Converts a percentage of procurement assets into Obligations.</span>
                        </div>

                        <!-- Info/Outcome Box -->
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">Simulated Extra Obligations:</span>
                                <span id="sim-extra-ob" class="font-bold text-slate-800">Php 0.00</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500 font-medium">New Simulated BUR:</span>
                                <span id="sim-new-bur" class="font-bold text-indigo-600">0.00%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 border-t border-slate-100 pt-4 hidden">
                    <div class="flex justify-between items-center text-[10px] md:text-xs">
                        <span class="font-semibold text-slate-700">Formula Legend:</span>
                        <span class="text-slate-500 bg-slate-100 px-2 py-0.5 rounded">Standard BUR Format</span>
                    </div>
                    <div class="mt-2 text-[10px] text-slate-400 leading-relaxed space-y-0.5">
                        <p><strong class="text-slate-600">Projected BUR</strong> = ((Obligations + Under Procurement) / Beg. Budget) * 100</p>
                        <p><strong class="text-slate-600">Remaining Balance Q2</strong> = Beg. Budget - Obligations - Under Procurement</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Financial Ledger Table & Mobile View -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <!-- Table Header Controls -->
            <div class="p-4 md:p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-slate-50/50">
                <div>
                    <h3 class="text-sm md:text-md font-bold text-slate-900">Line-Item Budgetary Ledger</h3>
                    <p class="text-[11px] text-slate-500">Comprehensive breakdown matching operational accounts and quarterly plans.</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-auto sm:flex-grow md:flex-grow-0">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input id="search-input" type="text" placeholder="Filter program..." oninput="renderLedger()" class="w-full sm:w-64 pl-9 pr-4 py-2.5 sm:py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white min-h-[40px]">
                    </div>
                    
                    <!-- Quick Filters -->
                    <select id="filter-utilization" onchange="renderLedger()" class="w-full sm:w-auto border border-slate-300 rounded-lg text-xs px-3 py-2.5 sm:py-2 bg-white text-slate-600 font-semibold min-h-[40px]">
                        <option value="all">All BUR Ranges</option>
                        <option value="high">High utilization (&ge; 70%)</option>
                        <option value="low">Low utilization (&lt; 70%)</option>
                    </select>
                </div>
            </div>

            <!-- Responsive Desktop/Tablet Table Layout -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                            <th class="py-4 px-6">Program / Sub-Activity</th>
                            <th class="py-4 px-4 text-right">Beg. Total Budget</th>
                            <th class="py-4 px-4 text-right">Obligations (As of <?php echo date("M Y"); ?>)</th>
                            <th class="py-4 px-4 text-right">Under Procurement</th>
                            <!-- <th class="py-4 px-4 text-right">Remaining Activities (Apr.-Dec.)</th> -->
                            <th class="py-4 px-4 text-center">Projected BUR (%)</th>
                            <!-- Burgundy colored remaining balance column head -->
                            <th class="py-4 px-6 text-right text-white color-burgundy font-bold">Remaining Balance Q2</th>
                            <!-- <th class="py-4 px-6 text-center">Actions</th> -->
                        </tr>
                    </thead>
                    <tbody id="ledger-rows-desktop" class="divide-y divide-slate-100 text-sm font-medium">
                        <!-- Rendered program rows for desktop go here -->
                    </tbody>
                </table>
            </div>

            <!-- Optimized Mobile/Portrait Card-List Layout (Visible only on mobile screen widths) -->
            <div id="ledger-rows-mobile" class="block md:hidden divide-y divide-slate-100">
                <!-- Rendered program cards for mobile go here -->
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden flex-col items-center justify-center p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4h16z"></path></svg>
                <p class="text-slate-500 font-bold text-sm">No budgetary programs found</p>
                <p class="text-xs text-slate-400 mt-1">Try resetting your filters or adding a new budget program line-item.</p>
            </div>
        </section>
    </main>

    <!-- Interactive Budget Modal (Handles Add and Edit) -->
    <div id="budget-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <!-- Modal Panel wrapper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Mobile sliding panel layout: full width on mobile with smooth bottom slide up -->
            <div class="inline-block align-bottom sm:align-middle bg-white rounded-t-2xl sm:rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full sm:max-w-lg">
                <!-- Modal header -->
                <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-sm md:text-md font-bold text-slate-900" id="modal-title">Add New Budgetary Program</h3>
                    <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 transition" aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal form body -->
                <form id="budget-form" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <input type="hidden" id="program-id">
                    
                    <div>
                        <label for="form-program-name" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Program / Sub-Activity Name</label>
                        <input type="text" id="form-program-name" required placeholder="e.g., Enterprise Software Licensing" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[44px]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="form-beg-budget" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Beg. Total Budget ($)</label>
                            <input type="number" step="0.01" min="0" id="form-beg-budget" required placeholder="0.00" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[44px]">
                        </div>
                        <div>
                            <label for="form-obligations" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Obligations ($)</label>
                            <input type="number" step="0.01" min="0" id="form-obligations" required placeholder="0.00" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[44px]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="form-procurement" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Under Procurement ($)</label>
                            <input type="number" step="0.01" min="0" id="form-procurement" required placeholder="0.00" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[44px]">
                        </div>
                        <div>
                            <label for="form-remaining-act" class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Remaining Activities ($)</label>
                            <input type="number" step="0.01" min="0" id="form-remaining-act" required placeholder="0.00" class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-h-[44px]">
                        </div>
                    </div>

                    <!-- Footnotes and guidelines inside modal -->
                    <div class="bg-red-50/50 p-3 rounded-lg border border-red-100 mt-2 text-[10px] text-slate-600 space-y-1">
                        <p class="font-bold text-burgundy">Note on Financial Rules:</p>
                        <p>1. Remaining Balance Q2 column will reflect: <code class="bg-white px-1 py-0.5 rounded border">Total Budget - Obligations - Procurement</code>.</p>
                        <p>2. Projected BUR is computed as: <code class="bg-white px-1 py-0.5 rounded border">(Obligations + Under Procurement) / Total Budget</code>.</p>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 mt-6 pb-4 sm:pb-0">
                        <button type="button" onclick="closeModal()" class="flex-1 sm:flex-none px-4 py-2.5 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50 transition min-h-[44px]">Cancel</button>
                        <button type="submit" class="flex-1 sm:flex-none px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-lg text-sm font-semibold transition min-h-[44px]">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-400 font-medium">
            &copy; 2026 Fiscal Management Division. Designed to comply with standard Q1/Q2 reporting layouts.
        </div>
    </footer>

    <!-- Chart and Ledger Javascript Logic -->
    <script>
        // Initial Mock Budget Items
		let budgetData = [];


		async function getUsers() {
			try {
				const response = await fetch(url+'/getfunds');
				const data = await response.json();
                return data;
			} catch (error) {
				console.error('Error:', error);
			}
		}
		
        // let budgetData = await getUsers();
        getUsers().then(data => {
            budgetData = data;

            updateDashboard();
            // renderLedger();
            // renderCharts();
            // runSimulation();
        });

        // let budgetData = [
        //     { id: 1, name: "Infrastructure Modernization", begBudget: 1500000, obligations: 650000, procurement: 350000, remainingActivities: 400000 },
        //     { id: 2, name: "Enterprise Software Licenses", begBudget: 800000, obligations: 500000, procurement: 150000, remainingActivities: 100000 },
        //     { id: 3, name: "Research & Development Lab Tech", begBudget: 1200000, obligations: 400000, procurement: 500000, remainingActivities: 200000 },
        //     { id: 4, name: "Information Security & Compliance", begBudget: 950000, obligations: 700000, procurement: 100000, remainingActivities: 120000 },
        //     { id: 5, name: "Outreach & Marketing Campaign", begBudget: 600000, obligations: 200000, procurement: 500000, remainingActivities: 300000 }
        // ];

        let chartInstance = null;

        // Currency Formatter
        function formatCurrency(val) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'PHP' }).format(val);
        }

        // Percentage Formatter
        function formatPercent(val) {
            return `${val.toFixed(2)}%`;
        }

        // Show toast notification
        function showToast(message, type = "success") {
            const toast = document.getElementById('toast');
            const msgSpan = document.getElementById('toast-message');
            const icon = document.getElementById('toast-icon');

            msgSpan.textContent = message;
            if (type === "success") {
                icon.innerHTML = `<svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
            } else {
                icon.innerHTML = `<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.remove('translate-y-10', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => toast.classList.add('hidden'), 300);
            }, 3000);
        }

        // Initialize and recalculate totals
        function updateDashboard() {
            let totalBudget = 0;
            let totalObligations = 0;
            let totalProcurement = 0;
            let totalRemainingAct = 0;

            budgetData.forEach(item => {
                totalBudget += item.begBudget;
                totalObligations += item.obligations;
                totalProcurement += item.procurement;
                totalRemainingAct += item.remainingActivities;
            });

            // Calculate overall BUR = ((Total Obligations + Total Procurement) / Total Budget) * 100
            const overallBur = totalBudget > 0 ? ((totalObligations + totalProcurement) / totalBudget) * 100 : 0;
            const remainingBalance = totalBudget - totalObligations - totalProcurement;

            // Apply values to standard KPI slots
            document.getElementById('kpi-total-budget').textContent = formatCurrency(totalBudget);
            document.getElementById('kpi-total-obligations').textContent = formatCurrency(totalObligations);
            document.getElementById('kpi-average-bur').textContent = formatPercent(overallBur);
            document.getElementById('kpi-bur-bar').style.width = `${Math.min(overallBur, 100)}%`;
            document.getElementById('kpi-remaining-balance').textContent = formatCurrency(remainingBalance);

            // Re-render chart and table
            renderLedger();
            renderCharts();
            runSimulation();
            fireuptd();
        }

        // fire up TD
        function fireuptd() {
            // const tds = document.querySelector('#ledger-rows-desktop tr td');

            const table = document.getElementById('ledger-rows-desktop');

            table.addEventListener('click', firenow);
        }

        function firenow(e) {
            const td = e.target.closest('td');
            if (!td) return;

            const tr = td.closest('tr');
            // alert(tr.dataset.div)
            // console.log('Row ID:', tr.id);
            // console.log('Cell:', td.textContent);

            window.open(`${url}/view/${tr.dataset.div}`);
        }

        // Toggle Expandable Details for mobile cards
        function toggleMobileCardDetails(id) {
            const el = document.getElementById(`mobile-details-${id}`);
            const icon = document.getElementById(`mobile-icon-${id}`);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                el.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Render Ledger Rows (Desktop table and Mobile card list)
        function renderLedger() {
            const tbodyDesktop = document.getElementById('ledger-rows-desktop');
            const tbodyMobile = document.getElementById('ledger-rows-mobile');
            const searchVal = document.getElementById('search-input').value.toLowerCase();
            const filterBur = document.getElementById('filter-utilization').value;
            const emptyState = document.getElementById('empty-state');

            tbodyDesktop.innerHTML = '';
            tbodyMobile.innerHTML = '';
            
            // Filter array based on search and selected utilization filters
            const filteredData = budgetData.filter(item => {
                const matchesSearch = item.name.toLowerCase().includes(searchVal);
                const projectedBur = item.begBudget > 0 ? ((item.obligations + item.procurement) / item.begBudget) * 100 : 0;
                
                let matchesBur = true;
                if (filterBur === "high") {
                    matchesBur = projectedBur >= 70;
                } else if (filterBur === "low") {
                    matchesBur = projectedBur < 70;
                }

                return matchesSearch && matchesBur;
            });

            if (filteredData.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
            }

            filteredData.forEach(item => {
                // Projected BUR = ((Obligations + Procurement) / Beg. Budget) * 100
                const bur = item.begBudget > 0 ? ((item.obligations + item.procurement) / item.begBudget) * 100 : 0;
                // Remaining Balance for Q2 = Beg. Budget - Obligations - Procurement
                const remainingBal = item.begBudget - item.obligations - item.procurement;

                // --- 1. RENDER DESKTOP TABLE ROW ---
                const tr = document.createElement('tr');
                tr.dataset.div = item.id;
                tr.className = "hover:bg-slate-50 transition border-b border-slate-100";
                tr.innerHTML = `
                    <td class="py-4 px-6 text-slate-900 font-semibold max-w-[200px] truncate" title="${item.name}">${item.name}</td>
                    <td class="py-4 px-4 text-right text-slate-700">${formatCurrency(item.begBudget)}</td>
                    <td class="py-4 px-4 text-right text-slate-700">${formatCurrency(item.obligations)}</td>
                    <td class="py-4 px-4 text-right text-slate-600">${formatCurrency(item.procurement)}</td>
                    
                    <td class="py-4 px-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ${bur >= 70 ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'}">
                            ${formatPercent(bur)}
                        </span>
                    </td>
                    <!-- Remaining Balance styled beautifully with matching burgundy palette -->
                    <td class="py-4 px-6 text-right font-bold text-red-100 color-burgundy">
                        ${formatCurrency(remainingBal)}
                    </td>
                    <td class="py-4 px-6 text-center hidden">
                        <div class="flex items-center justify-center space-x-2">
                            <button onclick="editProgram(${item.id})" class="p-1 hover:text-indigo-600 text-slate-400 transition" title="Edit line-item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="deleteProgram(${item.id})" class="p-1 hover:text-red-600 text-slate-400 transition" title="Delete line-item">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </td>
                `;
                tbodyDesktop.appendChild(tr);
                // <td class="py-4 px-4 text-right text-slate-500">${formatCurrency(item.remainingActivities)}</td>
                // --- 2. RENDER MOBILE CARD ---
                const card = document.createElement('div');
                card.className = "p-4 space-y-3 bg-white hover:bg-slate-50 transition";
                card.innerHTML = `
                    <div class="flex items-start justify-between gap-2">
                        <div onclick="toggleMobileCardDetails(${item.id})" class="cursor-pointer flex-1">
                            <h4 class="text-xs font-bold text-slate-900 leading-tight flex items-center gap-1.5">
                                <span>${item.name}</span>
                                <svg id="mobile-icon-${item.id}" class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-medium">BUR: ${formatPercent(bur)}</span>
                                <span class="text-[10px] text-slate-500">Budget: ${formatCurrency(item.begBudget)}</span>
                            </div>
                        </div>
                        <!-- Key Burgundy Balance displayed right on the card top corner -->
                        <div class="text-right shrink-0">
                            <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider">Remaining Balance</span>
                            <span class="text-xs font-bold text-burgundy bg-red-50 px-2.5 py-1 rounded-lg border border-red-100 block mt-0.5">
                                ${formatCurrency(remainingBal)}
                            </span>
                        </div>
                    </div>

                    <!-- Expandable details drawer on touch -->
                    <div id="mobile-details-${item.id}" class="hidden bg-slate-50 p-3 rounded-xl space-y-2 text-xs border border-slate-100">
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                            <div>
                                <span class="text-[10px] text-slate-400 block">Obligations (As of March 31)</span>
                                <strong class="text-slate-800">${formatCurrency(item.obligations)}</strong>
                            </div>
                            <div>
                                <span class="text-[10px] text-slate-400 block">Under Procurement</span>
                                <strong class="text-slate-800">${formatCurrency(item.procurement)}</strong>
                            </div>
                            <div class="col-span-2">
                                <span class="text-[10px] text-slate-400 block">Remaining Activities (April-Dec)</span>
                                <strong class="text-slate-800">${formatCurrency(item.remainingActivities)}</strong>
                            </div>
                        </div>

                        <!-- Action Bar inside drawer -->
                        <div class="flex items-center justify-end space-x-2 pt-2 border-t border-slate-200/60">
                            <button onclick="editProgram(${item.id})" class="flex items-center space-x-1.5 px-3 py-1.5 bg-slate-200/80 hover:bg-slate-200 text-slate-700 rounded-md font-semibold transition text-[11px] min-h-[36px]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <span>Edit</span>
                            </button>
                            <button onclick="deleteProgram(${item.id})" class="flex items-center space-x-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-md font-semibold transition text-[11px] min-h-[36px]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                <span>Delete</span>
                            </button>
                        </div>
                    </div>
                `;
                tbodyMobile.appendChild(card);
            });
        }

        // Render Chart.js
        function renderCharts() {
            const ctx = document.getElementById('barChart').getContext('2d');
            
            // Map the programs
            const labels = budgetData.map(item => item.name);
            const totalBudgets = budgetData.map(item => item.begBudget);
            const obligations = budgetData.map(item => item.obligations);
            const procurement = budgetData.map(item => item.procurement);

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Beg. Total Budget',
                            data: totalBudgets,
                            backgroundColor: 'rgba(79, 70, 229, 0.85)', // Tailwind indigo-600
                            borderRadius: 4,
                        },
                        {
                            label: 'Obligations',
                            data: obligations,
                            backgroundColor: 'rgba(16, 185, 129, 0.85)', // Tailwind emerald-500
                            borderRadius: 4,
                        },
                        {
                            label: 'Under Procurement',
                            data: procurement,
                            backgroundColor: 'rgba(245, 158, 11, 0.85)', // Tailwind amber-500
                            borderRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false, // Custom visual legend in HTML to optimize mobile layout space
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.dataset.label}: ${formatCurrency(context.parsed.y)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: window.innerWidth < 640 ? 8 : 10
                                },
                                maxRotation: 45,
                                minRotation: 45,
                                callback: function(value) {
                                    const rawLabel = this.getLabelForValue(value);
                                    return rawLabel.length > 15 ? rawLabel.substr(0, 15) + '...' : rawLabel;
                                }
                            }
                        },
                        y: {
                            grid: {
                                borderDash: [4, 4]
                            },
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1e6) {
                                        return '$' + (value / 1e6).toFixed(1) + 'M';
                                    } else if (value >= 1e3) {
                                        return '$' + (value / 1e3).toFixed(0) + 'k';
                                    }
                                    return '$' + value;
                                },
                                font: {
                                    family: 'Plus Jakarta Sans',
                                    size: window.innerWidth < 640 ? 8 : 10
                                }
                            }
                        }
                    }
                }
            });
        }

        // Forecaster / What-If simulation
        function runSimulation() {
            const rate = parseFloat(document.getElementById('conversion-slider').value);
            document.getElementById('slider-val').textContent = `${rate}%`;

            let totalBudget = 0;
            let totalObligations = 0;
            let totalProcurement = 0;

            budgetData.forEach(item => {
                totalBudget += item.begBudget;
                totalObligations += item.obligations;
                totalProcurement += item.procurement;
            });

            // Simulate conversion of procurement into obligations
            const simulatedExtraObligations = totalProcurement * (rate / 100);
            const newSimulatedObligations = totalObligations + simulatedExtraObligations;
            const newSimulatedProcurement = totalProcurement - simulatedExtraObligations;

            // Simulated Projected BUR = ((Simulated Obligations + Simulated Remaining Procurement) / Total Budget) * 100
            const simulatedBur = totalBudget > 0 ? ((newSimulatedObligations + newSimulatedProcurement) / totalBudget) * 100 : 0;
            
            // Show how active obligations BUR (excluding future pipeline) improves
            const obligationsOnlyBur = totalBudget > 0 ? (newSimulatedObligations / totalBudget) * 100 : 0;

            document.getElementById('sim-extra-ob').textContent = formatCurrency(simulatedExtraObligations);
            document.getElementById('sim-new-bur').textContent = `${obligationsOnlyBur.toFixed(2)}% (Obligations Only)`;
        }

        // Open Add/Edit Modal
        function openModal(mode, programId = null) {
            const modal = document.getElementById('budget-modal');
            const title = document.getElementById('modal-title');
            const form = document.getElementById('budget-form');
            
            form.reset();
            document.getElementById('program-id').value = '';

            if (mode === 'edit' && programId) {
                const program = budgetData.find(item => item.id === programId);
                if (program) {
                    title.textContent = "Edit Budgetary Program";
                    document.getElementById('program-id').value = program.id;
                    document.getElementById('form-program-name').value = program.name;
                    document.getElementById('form-beg-budget').value = program.begBudget;
                    document.getElementById('form-obligations').value = program.obligations;
                    document.getElementById('form-procurement').value = program.procurement;
                    document.getElementById('form-remaining-act').value = program.remainingActivities;
                }
            } else {
                title.textContent = "Add New Budgetary Program";
            }

            modal.classList.remove('hidden');
        }

        // Close Modal
        function closeModal() {
            document.getElementById('budget-modal').classList.add('hidden');
        }

        // Handle Add/Edit Form submit
        function handleFormSubmit(event) {
            event.preventDefault();

            const id = document.getElementById('program-id').value;
            const name = document.getElementById('form-program-name').value;
            const begBudget = parseFloat(document.getElementById('form-beg-budget').value);
            const obligations = parseFloat(document.getElementById('form-obligations').value);
            const procurement = parseFloat(document.getElementById('form-procurement').value);
            const remainingActivities = parseFloat(document.getElementById('form-remaining-act').value);

            // Basic validation
            if (obligations + procurement > begBudget) {
                showToast("Obligations + Under Procurement shouldn't exceed Beginning Budget!", "error");
                return;
            }

            if (id) {
                // Update
                const index = budgetData.findIndex(item => item.id === parseInt(id));
                if (index !== -1) {
                    budgetData[index] = { id: parseInt(id), name, begBudget, obligations, procurement, remainingActivities };
                    showToast("Program line-item updated successfully!");
                }
            } else {
                // Add
                const newId = budgetData.length > 0 ? Math.max(...budgetData.map(o => o.id)) + 1 : 1;
                budgetData.push({ id: newId, name, begBudget, obligations, procurement, remainingActivities });
                showToast("New program added to baseline budget ledger!");
            }

            closeModal();
            updateDashboard();
        }

        // Delete Program
        function deleteProgram(id) {
            budgetData = budgetData.filter(item => item.id !== id);
            showToast("Program deleted from the ledger.", "success");
            updateDashboard();
        }

        // Edit trigger
        function editProgram(id) {
            openModal('edit', id);
        }

        // Export data to CSV
        function exportToCSV() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Program/Sub-Activity,Beg. Total Budget,Obligations (Mar 31),Under Procurement,Remaining Activities,Projected BUR %,Remaining Balance Q2\r\n";

            budgetData.forEach(item => {
                const bur = item.begBudget > 0 ? (((item.obligations + item.procurement) / item.begBudget) * 100).toFixed(2) : 0;
                const remainingBal = item.begBudget - item.obligations - item.procurement;
                const row = `"${item.name.replace(/"/g, '""')}",${item.begBudget},${item.obligations},${item.procurement},${item.remainingActivities},${bur}%,${remainingBal}`;
                csvContent += row + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "FY2026_Budget_BUR_Ledger.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showToast("Ledger CSV downloaded successfully!");
        }

        // Handle scaling adjustments on window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                renderCharts();
            }, 250);
        });

        // Initial setup on load
        window.onload = function() {
            updateDashboard();
        };

    </script>
</body>
</html>