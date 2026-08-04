<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Activity Entry & Masking Terminal</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font -->
    <script> var url = "<?php echo url('/'); ?>"; var d = "<?php echo $action; ?>"; </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Burgundy styling representing standard Q1/Q2 financial audits */
        .color-burgundy {
            background-color: #800000;
        }
        .text-burgundy {
            color: #800000;
        }
        .border-burgundy {
            border-color: #800000;
        }
        .focus-burgundy:focus {
            outline: none;
            border-color: #800000;
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.15);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col antialiased pb-12">

    <!-- Header Banner -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 color-burgundy text-white rounded-lg">
                    <!-- Dynamic Entry / Activity Mask SVG -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-md md:text-lg font-bold text-slate-900">Program Activity Logger: <?php echo $divs[0]->divisionname; ?></h1>
                    <p class="text-xs text-slate-500 font-medium">Input Mask & Financial Compliance Console</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Layout -->
    <main class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Side: Interactive Input Form -->
        <section class="lg:col-span-5 space-y-6">
           
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                <div>
                    <h2 class="text-md font-bold text-slate-900">Activity Entry Form</h2>
                    <p class="text-xs text-slate-500">All financial amounts are sanitized using reactive masking.</p>
                </div>
                <?php if ($action == "enable") { ?>
                    
                    <form id="activity-entry-form" method="POST" action="{{route('save')}}" class="space-y-4">
                        @csrf
                        <!-- Choose Program -->
                        <div>
                            <!-- <div class="flex justify-between items-center mb-1">
                                <label for="program-select" class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Select Program</label>
                                <button type="button" onclick="toggleCustomProgramField()" class="text-xs text-burgundy font-semibold hover:underline">
                                    + New Program
                                </button>
                            </div> -->
                            <select id="program-select" name='thebudgetline' required class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm bg-white focus:ring-2 focus:ring-slate-950 focus:border-slate-950 transition">
                                <option value="" disabled selected>-- Choose a Fund Source --</option>
                                <?php
                                    foreach($programs as $ps) {
                                        echo "<option value='{$ps->fsrcid}'> {$ps->fundname} </option>";
                                    }
                                ?>
                            </select>
                            
                            <!-- Custom program inline addition -->
                            <div id="custom-program-container" class="hidden mt-2 p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-2">
                                <input type="text" id="custom-program-input" placeholder="Type new program name..." class="w-full border border-slate-300 rounded-md px-3 py-1.5 text-xs bg-white focus-burgundy">
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="toggleCustomProgramField()" class="px-2.5 py-1 text-[11px] font-semibold text-slate-500 hover:bg-slate-200 rounded">Cancel</button>
                                    <button type="button" onclick="addCustomProgram()" class="px-2.5 py-1 text-[11px] font-semibold bg-slate-900 text-white rounded hover:bg-slate-800">Add</button>
                                </div>
                            </div>
                        </div>

                        <!-- Activity Name -->
                        <div>
                            <label for="activity-name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Activity / Purchase Description</label>
                            <input type="text" id="activity-name" name="activityname" required placeholder="e.g. Core server migrations Q2" class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-slate-950 focus:border-slate-950 transition">
                        </div>

                        <!-- Timeline Selectors (Start & End dates) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Target Timeline</label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block mb-0.5">START DATE</span>
                                    <input type="date" name='t_start' id="timeline-start" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-slate-950">
                                </div>
                                <div>
                                    <span class="text-[10px] text-slate-400 font-bold block mb-0.5">END DATE</span>
                                    <input type="date" name="t_end" id="timeline-end" required class="w-full border border-slate-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-slate-950">
                                </div>
                            </div>
                        </div>

                        <!-- Amount with strict Input Mask -->
                        <div>
                            <label for="mask-amount" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Allocation Amount</label>
                            <div class="relative">
                                <input type="text" name="alloc_amount" id="mask-amount" required placeholder="Php 0.00" class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-right font-mono font-bold focus-burgundy transition">
                            </div>
                            <span class="text-[10px] text-slate-400 mt-1 block">Sanitized numeric payload: <code id="raw-amount" class="bg-slate-100 px-1 py-0.5 rounded text-slate-600 font-bold">0.00</code></span>
                            <input type="hidden" id="raw-input" name="input_value"/>
                        </div>

                        <!-- Status Selector (Obligated vs Under Procurement) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Fund Allocation Status</label>
                            <div class="grid grid-cols-2 gap-3">
                                <!-- Radio Option 1: Obligated -->
                                <label class="relative flex flex-col p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:bg-slate-50 transition focus-within:ring-2 focus-within:ring-indigo-500">
                                    <input type="radio" name="status" id = "obligatedstat" value="obligated" checked class="sr-only" onchange="runLiveCalculations()">
                                    <span class="text-xs font-bold text-slate-800">Obligated</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">Contract awarded / Committed</span>
                                    <span class="absolute top-3 right-3 text-emerald-500 radio-check">●</span>
                                </label>
                                
                                <!-- Radio Option 2: Under Procurement -->
                                <label class="relative flex flex-col p-3 rounded-xl border border-slate-200 bg-white cursor-pointer hover:bg-slate-50 transition focus-within:ring-2 focus-within:ring-indigo-500">
                                    <input type="radio" name="status" id = "onprocstat" value="underproc" class="sr-only" onchange="runLiveCalculations()">
                                    <span class="text-xs font-bold text-slate-800">Procurement</span>
                                    <span class="text-[10px] text-slate-400 mt-0.5">In bidding / RFQ phase</span>
                                    <span class="absolute top-3 right-3 text-amber-500 hidden radio-check">●</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submission button -->
                        <button type="submit" name="savebtn" value="save" id="submit-btn" class="w-full py-2.5 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800 transition shadow-sm text-sm">
                            Log Activity Entry
                        </button>
                        <input type="hidden" id="theidid" name="theidid"/>
                        <button type="submit" name="savebtn" value="update" id="update-btn" class="hidden w-full py-2.5 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800 transition shadow-sm text-sm">
                            Update Activity Entry
                        </button>
                    </form>
                <?php } else {?>
                    <h1> Input is not possible in this view </h1>
                <?php } ?>
            </div>
            
        </section>

        <!-- Right Side: Real-Time Summaries and Ledger Logs -->
        <section class="lg:col-span-7 space-y-6">
            
            <!-- Real-Time Activity Analysis Dashboard -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Activity Log Diagnostics</h3>
                <div class="grid grid-cols-1 sm:grid-cols-1 gap-4 mb-5">
                    <div class="color-burgundy text-white p-4 rounded-xl shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-red-200 block">Total Budget</span>
                        <h4 id="calc-tot-budget" class="text-lg md:text-xl font-extrabold mt-1"><?php echo number_format($total,2); ?></h4>
                    </div>
                    <!-- <div class="color-burgundy text-white p-4 rounded-xl shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-red-200 block">Remaining</span>
                        <h4 id="calc-remaining" class="text-lg md:text-xl font-extrabold mt-1">0.00</h4>
                    </div> -->
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Total Obligated</span>
                        <h4 id="calc-obligated" class="text-lg md:text-xl font-extrabold text-emerald-700 mt-1">Php 0.00</h4>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Under Procurement</span>
                        <h4 id="calc-procurement" class="text-lg md:text-xl font-extrabold text-amber-700 mt-1">Php 0.00</h4>
                    </div>
                    <div class="color-burgundy text-white p-4 rounded-xl shadow-xs">
                        <span class="text-[10px] uppercase font-bold text-red-200 block">Total Activity Cost</span>
                        <h4 id="calc-total" class="text-lg md:text-xl font-extrabold mt-1">0.00</h4>
                    </div>
                </div>
            </div>

            <!-- Activity Ledger Log -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Committed Activity Ledger</h3>
                        <p class="text-[11px] text-slate-500">Detailed list of individual sub-activities logged</p>
                    </div>
                    <!-- <button onclick="clearLedger()" class="text-xs text-rose-600 hover:text-rose-800 font-semibold">
                        Clear Activity Log
                    </button> -->
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-[10px] font-bold uppercase border-b border-slate-200">
                                <th class="py-3 px-4">Program & Activity</th>
                                <th class="py-3 px-3 text-center">Timeline</th>
                                <th class="py-3 px-3 text-center">Status</th>
                                <th class="py-3 px-4 text-right">Amount</th>
                                <?php if ($action == "enable") { ?>
                                    <th class="py-3 px-4 text-center">Action</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="ledger-table-body" class="divide-y divide-slate-100 text-xs">
                            <!-- Populated dynamically -->
                        </tbody>
                    </table>
                </div>
                
                <!-- Empty State -->
                <div id="empty-state" class="hidden flex-col items-center justify-center p-8 text-center bg-white">
                    <p class="text-xs text-slate-400 font-semibold">No activities recorded yet.</p>
                </div>
            </div>

            <!-- Program Summary Rollups -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Program Roll-up Summary</h3>
                    <p class="text-xs text-slate-400">Aggregated allocation metrics grouped by core program category</p>
                </div>
                <div id="program-rollup-container" class="space-y-3">
                    <!-- Dynamic Program Summaries Rendered Here -->
                </div>
            </div>

        </section>
    </main>

    <!-- Global Toast Alert -->
    <div id="toast" class="hidden fixed bottom-5 right-5 z-50 bg-slate-950 text-white text-xs px-4 py-3 rounded-lg shadow-xl border border-slate-800 transition-all duration-200">
        Record logged to ledger.
    </div>

    <!-- Mask logic JS -->
    <script>
        // Array holding activity logs
        // let activityRecords = [
        //     { id: 1, program: "Infrastructure Modernization", name: "High-Capacity Core Switches", start: "2026-03-01", end: "2026-03-15", status: "Obligated", amount: 125000.00 },
        //     { id: 2, program: "Enterprise Software Licenses", name: "Database Cluster Upgrades", start: "2026-04-01", end: "2026-06-30", status: "Under Procurement", amount: 45000.00 },
        //     { id: 3, program: "Information Security & Compliance", name: "Penetration Testing Audit", start: "2026-02-15", end: "2026-03-01", status: "Obligated", amount: 32000.00 }
        // ];

        let activityRecords = [];

        async function getactrec(div) {
            try {
                const response = await fetch(`${url}/getactrecs/${div}`);
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error:', error);
            }
        }

        let act = window.location.pathname.split('/')[1];
        let div = window.location.pathname.split('/')[2];

        if (act == "add") { div = false; }

        getactrec(div).then(data => {
            activityRecords = data;
            updateLedgerUI();
        });

        const toUSD = (num) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'PHP' }).format(num);

        // Format raw number from currency string
        function getRawNumber(valueString) {
            if (!valueString) return 0;
            let clean = valueString.replace(/[^\d.]/g, '');
            let parsed = parseFloat(clean);
            return isNaN(parsed) ? 0 : parsed;
        }

        // Apply right-to-left decimal shift input mask
        function applyCurrencyMask(e) {
            let value = e.target.value;
            let clean = value.replace(/[^\d]/g, '');
            if (clean === '') {
                e.target.value = '';
                runLiveCalculations();
                return;
            }

            let cents = parseFloat(clean) / 100;
            if (cents > 999999999.99) {
                cents = 999999999.99;
            }

            let formatted = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(cents);

            e.target.value = formatted;
            runLiveCalculations();
        }

        // Real-time calculation previews
        function runLiveCalculations() {
            let currentAmount = getRawNumber(document.getElementById('mask-amount').value);
            document.getElementById('raw-amount').textContent = currentAmount.toFixed(2);
            document.getElementById("raw-input").value  = currentAmount.toFixed(2);

            // Handle visual radio card checkmark toggle
            const statusInputs = document.getElementsByName('status');
            statusInputs.forEach(input => {
                const label = input.closest('label');
                const checkElement = label.querySelector('.radio-check');
                if (input.checked) {
                    checkElement.classList.remove('hidden');
                } else {
                    checkElement.classList.add('hidden');
                }
            });
        }

        // Toggle custom program entry visibility
        function toggleCustomProgramField() {
            const container = document.getElementById('custom-program-container');
            container.classList.toggle('hidden');
        }

        // Add custom program to select list
        function addCustomProgram() {
            const input = document.getElementById('custom-program-input');
            const select = document.getElementById('program-select');
            const newProgramName = input.value.trim();

            if (newProgramName) {
                // Create option
                const option = document.createElement('option');
                option.value = newProgramName;
                option.textContent = newProgramName;
                option.selected = true;
                select.appendChild(option);
                
                input.value = '';
                toggleCustomProgramField();
                showToast(`Program "${newProgramName}" added.`);
            }
        }

        // Submit form handler
        function handleFormSubmit(e) {
            e.preventDefault();

            const program = document.getElementById('program-select').value;
            const name = document.getElementById('activity-name').value;
            const start = document.getElementById('timeline-start').value;
            const end = document.getElementById('timeline-end').value;
            const amount = getRawNumber(document.getElementById('mask-amount').value);
            const status = document.querySelector('input[name="status"]:checked').value;

            if (!program) {
                showToast("Please choose or add a program category.", "error");
                return;
            }

            // Push to record array
            const newId = activityRecords.length > 0 ? Math.max(...activityRecords.map(o => o.id)) + 1 : 1;
            activityRecords.push({ id: newId, program, name, start, end, status, amount });

            // Reset Form fields
            e.target.reset();
            document.getElementById('raw-amount').textContent = '0.00';
            document.getElementById("raw-input").value        = '0.00';
            
            // Trigger UI updates
            showToast("Activity logged successfully!");
            updateLedgerUI();
        }

        // Format timeline date range beautifully
        function formatDates(start, end) {
            if (!start || !end) return "N/A";
            const opt = { month: 'short', day: 'numeric', year: '2-digit' };
            const sDate = new Date(start).toLocaleDateString('en-US', opt);
            const eDate = new Date(end).toLocaleDateString('en-US', opt);
            return `${sDate} - ${eDate}`;
        }

        // Remove single activity row
        async function removeActivity(id) {
            var conf;

            conf = confirm("Are you sure you want to delete?");

            if (!conf) {
                return;
            } 

            try {
                const response = await fetch(`${url}/removeitem/${id}`);
                const data     = await response.json();

                if (data) {
                    activityRecords = activityRecords.filter(item => item.id !== id);
                    showToast("Activity entry removed.");
                    updateLedgerUI();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Clear all entries
        function clearLedger() {
            activityRecords = [];
            showToast("All activities cleared.");
            updateLedgerUI();
        }

        // Show standard message alert box
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2500);
        }

        // Complete UI Render Loop
        function updateLedgerUI() {
            const tbody = document.getElementById('ledger-table-body');
            const emptyState = document.getElementById('empty-state');
            tbody.innerHTML = '';

            let totalObligated = 0;
            let totalProcured = 0;

            if (activityRecords.length === 0) {
                emptyState.classList.remove('hidden');
                emptyState.classList.add('flex');
            } else {
                emptyState.classList.add('hidden');
                emptyState.classList.remove('flex');
            }

            // Create dictionary for program-level grouping
            let rollupGroup = {};

            activityRecords.forEach(item => {
                if (item.status === 'Obligated') {
                    totalObligated += item.amount;
                } else {
                    totalProcured += item.amount;
                }

                // Add to rollup dictionary
                if (!rollupGroup[item.program]) {
                    rollupGroup[item.program] = { obligated: 0, procured: 0, count: 0 };
                }
                if (item.status === 'Obligated') {
                    rollupGroup[item.program].obligated += item.amount;
                } else {
                    rollupGroup[item.program].procured += item.amount;
                }
                rollupGroup[item.program].count++;

                // Append row
                const tr     = document.createElement('tr');
                tr.className = "trclick hover:bg-slate-50 transition border-b border-slate-100 font-medium text-slate-700";
                tr.dataset.trid = `${item.id}`;
                tr.innerHTML = `
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-900">${item.name}</div>
                        <div class="text-[10px] text-slate-400 font-medium">${item.program}</div>
                    </td>
                    <td class="py-3 px-3 text-center text-slate-500 text-[11px] font-mono whitespace-nowrap">${formatDates(item.start, item.end)}</td>
                    <td class="py-3 px-3 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold ${item.status === 'Obligated' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100':'bg-amber-50 text-amber-700 border border-amber-100'}">
                            ${item.status}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right font-mono text-slate-900 font-bold">${toUSD(item.amount)}</td>`;

                if (d == "enable") {
                    tr.innerHTML += `    
                        <td class="py-3 px-4 text-center">
                            <button onclick="removeActivity(${item.id})" class="text-slate-400 hover:text-red-600 transition p-1" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>`;
                }
                tbody.appendChild(tr);
            });

            // Update Diagnostic Card widgets
            document.getElementById('calc-obligated').textContent = toUSD(totalObligated);
            document.getElementById('calc-procurement').textContent = toUSD(totalProcured);
            document.getElementById('calc-total').textContent = toUSD(totalObligated + totalProcured);

            // Render Program Roll-up Panel
            const rollupContainer = document.getElementById('program-rollup-container');
            rollupContainer.innerHTML = '';

            const keys = Object.keys(rollupGroup);
            if (keys.length === 0) {
                rollupContainer.innerHTML = `<p class="text-xs text-slate-400 font-semibold italic text-center py-4">No data to roll up.</p>`;
            } else {
                keys.forEach(prog => {
                    const rollup = rollupGroup[prog];
                    const totalCost = rollup.obligated + rollup.procured;
                    const block = document.createElement('div');
                    block.className = "bg-slate-50 p-3 rounded-xl border border-slate-200 space-y-2";
                    block.innerHTML = `
                        <div class="flex justify-between items-start">
                            <h4 class="text-xs font-bold text-slate-900 truncate max-w-[250px]" title="${prog}">${prog}</h4>
                            <span class="text-[10px] text-slate-500 font-semibold bg-white border px-2 py-0.5 rounded-full">${rollup.count} activities</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 pt-1 border-t border-slate-100 text-[10px]">
                            <div>
                                <span class="text-slate-400 block font-medium">Obligated</span>
                                <strong class="text-emerald-700 font-bold">${toUSD(rollup.obligated)}</strong>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Procurement</span>
                                <strong class="text-amber-700 font-bold">${toUSD(rollup.procured)}</strong>
                            </div>
                            <div class="text-right">
                                <span class="text-slate-400 block font-medium">Accumulated</span>
                                <strong class="text-slate-900 font-bold">${toUSD(totalCost)}</strong>
                            </div>
                        </div>
                    `;
                    rollupContainer.appendChild(block);
                });
            }

            document.querySelectorAll(".trclick").forEach(tr => {
                tr.addEventListener("click", clicktr);
            });

            runLiveCalculations();
        }

        async function clicktr() {
            var trid = this.dataset.trid;

            try {
                const resp = await fetch(`${url}/getdetails/${trid}`);

                const data     = await resp.json();

                document.getElementById("submit-btn").classList.add("hidden");
                document.getElementById("update-btn").classList.remove("hidden");
                document.getElementById("theidid").value = trid;

                document.getElementById("program-select").value             = data[0].fsrcidfk;
                document.getElementById("activity-name").value              = data[0].name;
                document.getElementById("timeline-start").value             = data[0].startdate;
                document.getElementById("timeline-end").value               = data[0].enddate;
                var input = document.getElementById("mask-amount").value    = data[0].fundvalue;
                    // input.addEventListener('input', applyCurrencyMask);

                var stat = data[0].expendituretype;    

                if (stat == "obligated") {
                    document.getElementById("obligatedstat").checked = true;
                    document.getElementById("onprocstat").checked    = false;
                } else if (stat == "underproc") {
                    document.getElementById("onprocstat").checked    = true;
                    document.getElementById("obligatedstat").checked = false;
                }

                runLiveCalculations();
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Listen for amount mask changes
        document.getElementById('mask-amount').addEventListener('input', applyCurrencyMask);

        // Initial setup on load
        window.onload = function() {
            updateLedgerUI();
        };
    </script>
</body>
</html>