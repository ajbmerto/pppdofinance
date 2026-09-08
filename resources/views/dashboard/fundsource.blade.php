<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fund Management Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0369a1',
                            700: '#075985',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-brand-600 text-white p-2 rounded-lg">
                    <i data-lucide="wallet" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-900 leading-none">Fund Explorer</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Financial Source Directory</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button id="exportBtn" class="inline-flex items-center gap-2 px-3 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Export CSV
                </button>
                <button id="addNewBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add New Fund
                </button>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Stats Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                    <i data-lucide="database" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Records</p>
                    <h3 id="statTotalFunds" class="text-2xl font-bold text-slate-900">0</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                    <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Value</p>
                    <h3 id="statTotalValue" class="text-2xl font-bold text-slate-900">$0</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-amber-50 text-amber-600 rounded-lg">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Divisions</p>
                    <h3 id="statDivisions" class="text-2xl font-bold text-slate-900">0</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                    <i data-lucide="calendar" class="w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Active Years</p>
                    <h3 id="statYears" class="text-2xl font-bold text-slate-900">0</h3>
                </div>
            </div>
        </div>

        <!-- Controls / Search & Filters -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-96">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="searchInput" placeholder="Search by name, source ID..." class="w-full pl-9 pr-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500">
            </div>

            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <!-- Division Filter -->
                <select id="divisionFilter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Divisions</option>
                </select>

                <!-- Year Active Filter -->
                <select id="yearFilter" class="px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                    <option value="">All Years</option>
                </select>

                <button id="resetFiltersBtn" class="text-xs font-medium text-slate-500 hover:text-slate-800 underline px-2">Reset</button>
            </div>
        </div>

        <!-- Data Table Container -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('fsrcid')">
                                <div class="flex items-center gap-1">Source ID (fsrcid) <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('fundname')">
                                <div class="flex items-center gap-1">Fund Name (fundname) <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('fundvalue')">
                                <div class="flex items-center gap-1">Value (fundvalue) <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('yearactive')">
                                <div class="flex items-center gap-1">Active Year (yearactive) <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('divisionid')">
                                <div class="flex items-center gap-1">Division ID (divisionid) <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 cursor-pointer hover:bg-slate-100" onclick="sortTable('updated_at')">
                                <div class="flex items-center gap-1">Timestamps <i data-lucide="arrow-up-down" class="w-3 h-3"></i></div>
                            </th>
                            <th class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="divide-y divide-slate-200">
                        <!-- Dynamic content populated by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden py-12 text-center">
                <i data-lucide="folder-search" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                <h3 class="text-base font-semibold text-slate-700">No records found</h3>
                <p class="text-xs text-slate-500 mt-1">Try adjusting your search criteria or add a new fund.</p>
            </div>
        </div>

    </main>

    <!-- Modal Form (Add / Edit) -->
    <div id="fundModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-200">
        <div class="bg-white rounded-xl shadow-xl border border-slate-200 w-full max-w-lg mx-4 overflow-hidden transform scale-95 transition-transform duration-200" id="modalContainer">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
                <h3 id="modalTitle" class="text-lg font-bold text-slate-800">Add New Fund</h3>
                <button id="closeModalBtn" class="text-slate-400 hover:text-slate-600 rounded-lg p-1">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="fundForm" class="p-6 space-y-4" method="post" action="{{route('savefund')}}">
                @csrf
                <input type="hidden" id="editIndex" value="-1">

                <div class="grid grid-cols-2 gap-4">
                    <!-- fsrcid -->
                    <!-- <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="fsrcid">Fund Source ID (fsrcid)</label>
                        <input type="text" id="fsrcid" required placeholder="e.g. FSRC-101" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div> -->

                    <!-- divisionid -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="divisionid">Division ID (divisionid)</label>
                        <select name="divisionselect" class="px-3 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <?php
                                foreach($division as $ds) {
                                    echo "<option value='{$ds->divid}'> {$ds->divisionname} </option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <!-- fundname -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1" for="fundname">Fund Name (fundname)</label>
                    <input type="text" name="fundname" required placeholder="e.g. Infrastructure Growth Grant" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- fundvalue -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="fundvalue">Fund Value ($)</label>
                        <input type="number" name="fundvalue" step="0.01" min="0" required placeholder="0.00" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>

                    <!-- yearactive -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1" for="yearactive">Year Active</label>
                        <input type="number" name="yearactive" min="1900" max="2100" required placeholder="2026" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none">
                    </div>
                </div>

                <!-- Automatic/Readonly Timestamps Displayed in Edit Mode -->
                <div id="timestampSection" class="hidden grid-cols-2 gap-4 bg-slate-50 p-3 rounded-lg border border-slate-200">
                    <div>
                        <span class="block text-[10px] text-slate-400 font-medium">CREATED AT</span>
                        <span id="display_created_at" class="text-xs text-slate-600 font-mono">--</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-slate-400 font-medium">UPDATED AT</span>
                        <span id="display_updated_at" class="text-xs text-slate-600 font-mono">--</span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <button type="button" id="cancelModalBtn" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-brand-600 text-white rounded-lg text-sm font-medium hover:bg-brand-700 shadow-sm">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed bottom-5 right-5 bg-slate-900 text-white px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 transition-all duration-300 transform translate-y-20 opacity-0 z-50">
        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400" id="toastIcon"></i>
        <span id="toastMessage" class="text-sm font-medium">Operation completed</span>
    </div>

    <!-- Application Script -->
    <script>
        // Default Mock Data containing user requested fields
        const initialMockData = [
            {
                fsrcid: "FSRC-001",
                fundname: "Community Tech Development",
                fundvalue: 150000.00,
                yearactive: 2024,
                divisionid: "DIV-WEST",
                created_at: "2024-01-15T08:30:00.000Z",
                updated_at: "2024-02-01T10:15:00.000Z"
            },
            {
                fsrcid: "FSRC-002",
                fundname: "Green Energy Initiative",
                fundvalue: 450000.50,
                yearactive: 2025,
                divisionid: "DIV-NORTH",
                created_at: "2024-03-10T11:20:00.000Z",
                updated_at: "2024-03-10T11:20:00.000Z"
            },
            {
                fsrcid: "FSRC-003",
                fundname: "Healthcare Access Grant",
                fundvalue: 275000.00,
                yearactive: 2026,
                divisionid: "DIV-SOUTH",
                created_at: "2025-06-01T14:45:00.000Z",
                updated_at: "2026-01-12T09:00:00.000Z"
            },
            {
                fsrcid: "FSRC-004",
                fundname: "Youth Education Support",
                fundvalue: 85000.00,
                yearactive: 2025,
                divisionid: "DIV-EAST",
                created_at: "2025-08-20T16:00:00.000Z",
                updated_at: "2025-08-20T16:00:00.000Z"
            }
        ];

        // State Management
        let funds = [];
        let sortColumn = 'created_at';
        let sortAsc = false;

        // DOM Elements
        const tableBody = document.getElementById('tableBody');
        const emptyState = document.getElementById('emptyState');
        const searchInput = document.getElementById('searchInput');
        const divisionFilter = document.getElementById('divisionFilter');
        const yearFilter = document.getElementById('yearFilter');
        const resetFiltersBtn = document.getElementById('resetFiltersBtn');

        const fundModal = document.getElementById('fundModal');
        const modalContainer = document.getElementById('modalContainer');
        const modalTitle = document.getElementById('modalTitle');
        const fundForm = document.getElementById('fundForm');
        const addNewBtn = document.getElementById('addNewBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const timestampSection = document.getElementById('timestampSection');

        // Form Fields
        const editIndexInput = document.getElementById('editIndex');
        const fsrcidInput = document.getElementById('fsrcid');
        const fundnameInput = document.getElementById('fundname');
        const fundvalueInput = document.getElementById('fundvalue');
        const yearactiveInput = document.getElementById('yearactive');
        const divisionidInput = document.getElementById('divisionid');
        const displayCreatedAt = document.getElementById('display_created_at');
        const displayUpdatedAt = document.getElementById('display_updated_at');

        // Initialize App
        window.addEventListener('DOMContentLoaded', () => {
            loadData();
            initLucide();
            setupEventListeners();
        });

        function initLucide() {
            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function loadData() {
            const stored = localStorage.getItem('fund_data');
            if (stored) {
                try {
                    funds = JSON.parse(stored);
                } catch (e) {
                    funds = [...initialMockData];
                }
            } else {
                funds = [...initialMockData];
                saveData();
            }
            populateFilters();
            renderTable();
            updateStats();
        }

        function saveData() {
            localStorage.setItem('fund_data', JSON.stringify(funds));
        }

        // Format Utilities
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
        }

        function formatDate(isoString) {
            if (!isoString) return '--';
            const date = new Date(isoString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        // Populate Select Filters
        function populateFilters() {
            const divisions = [...new Set(funds.map(f => f.divisionid))].sort();
            const years = [...new Set(funds.map(f => f.yearactive))].sort((a, b) => b - a);

            divisionFilter.innerHTML = '<option value="">All Divisions</option>' +
                divisions.map(d => `<option value="${d}">${d}</option>`).join('');

            yearFilter.innerHTML = '<option value="">All Years</option>' +
                years.map(y => `<option value="${y}">${y}</option>`).join('');
        }

        // Update Summary Dashboard Stats
        function updateStats() {
            document.getElementById('statTotalFunds').textContent = funds.length;
            
            const totalVal = funds.reduce((acc, curr) => acc + Number(curr.fundvalue || 0), 0);
            document.getElementById('statTotalValue').textContent = formatCurrency(totalVal);

            const uniqueDivs = new Set(funds.map(f => f.divisionid)).size;
            document.getElementById('statDivisions').textContent = uniqueDivs;

            const uniqueYears = new Set(funds.map(f => f.yearactive)).size;
            document.getElementById('statYears').textContent = uniqueYears;
        }

        // Render Table Rows
        function renderTable() {
            const query = searchInput.value.toLowerCase().trim();
            const selectedDiv = divisionFilter.value;
            const selectedYear = yearFilter.value;

            // Filtering
            let filtered = funds.filter(fund => {
                const matchesSearch = 
                    fund.fsrcid.toLowerCase().includes(query) ||
                    fund.fundname.toLowerCase().includes(query) ||
                    fund.divisionid.toLowerCase().includes(query);
                
                const matchesDiv = selectedDiv === '' || fund.divisionid === selectedDiv;
                const matchesYear = selectedYear === '' || String(fund.yearactive) === selectedYear;

                return matchesSearch && matchesDiv && matchesYear;
            });

            // Sorting
            filtered.sort((a, b) => {
                let valA = a[sortColumn];
                let valB = b[sortColumn];

                if (typeof valA === 'string') valA = valA.toLowerCase();
                if (typeof valB === 'string') valB = valB.toLowerCase();

                if (valA < valB) return sortAsc ? -1 : 1;
                if (valA > valB) return sortAsc ? 1 : -1;
                return 0;
            });

            // HTML Generation
            if (filtered.length === 0) {
                tableBody.innerHTML = '';
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
                tableBody.innerHTML = filtered.map((fund) => {
                    // Finding the actual index in the funds array
                    const originalIndex = funds.findIndex(f => f.fsrcid === fund.fsrcid && f.created_at === fund.created_at);

                    return `
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-medium text-slate-800">${escapeHtml(fund.fsrcid)}</td>
                            <td class="px-6 py-4 font-medium text-slate-900">${escapeHtml(fund.fundname)}</td>
                            <td class="px-6 py-4 font-semibold text-emerald-600">${formatCurrency(fund.fundvalue)}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-blue-200">
                                    ${fund.yearactive}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">${escapeHtml(fund.divisionid)}</td>
                            <td class="px-6 py-4 text-xs text-slate-500">
                                <div title="Created At">C: ${formatDate(fund.created_at)}</div>
                                <div title="Updated At" class="text-slate-400">U: ${formatDate(fund.updated_at)}</div>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="openEditModal(${originalIndex})" class="p-1.5 text-slate-400 hover:text-brand-600 rounded-md hover:bg-slate-100 transition-colors" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </button>
                                <button onclick="deleteRecord(${originalIndex})" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-md hover:bg-slate-100 transition-colors" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            initLucide();
        }

        function sortTable(column) {
            if (sortColumn === column) {
                sortAsc = !sortAsc;
            } else {
                sortColumn = column;
                sortAsc = true;
            }
            renderTable();
        }

        // Helper function to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Modal Controllers
        function openAddModal() {
            modalTitle.textContent = "Add New Fund";
            editIndexInput.value = "-1";
            fundForm.reset();
            timestampSection.classList.add('hidden');
            
            // Set current year as default
            // yearactiveInput.value = new Date().getFullYear();

            showModal();
        }

        function openEditModal(index) {
            const fund = funds[index];
            if (!fund) return;

            modalTitle.textContent = "Edit Fund Record";
            editIndexInput.value = index;

            fsrcidInput.value = fund.fsrcid;
            fundnameInput.value = fund.fundname;
            fundvalueInput.value = fund.fundvalue;
            yearactiveInput.value = fund.yearactive;
            divisionidInput.value = fund.divisionid;

            displayCreatedAt.textContent = formatDate(fund.created_at);
            displayUpdatedAt.textContent = formatDate(fund.updated_at);
            timestampSection.classList.remove('hidden');

            showModal();
        }

        function showModal() {
            fundModal.classList.remove('hidden');
            setTimeout(() => {
                fundModal.classList.remove('opacity-0');
                modalContainer.classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            modalContainer.classList.add('scale-95');
            fundModal.classList.add('opacity-0');
            setTimeout(() => {
                fundModal.classList.add('hidden');
            }, 200);
        }

        // Form Submit Handler
        // fundForm.addEventListener('submit', (e) => {
        //     e.preventDefault();

        //     const idx = parseInt(editIndexInput.value);
        //     const nowIso = new Date().toISOString();

        //     const formData = {
        //         fsrcid: fsrcidInput.value.trim(),
        //         fundname: fundnameInput.value.trim(),
        //         fundvalue: parseFloat(fundvalueInput.value),
        //         yearactive: parseInt(yearactiveInput.value),
        //         divisionid: divisionidInput.value.trim(),
        //     };

        //     if (idx === -1) {
        //         // Create New Record
        //         formData.created_at = nowIso;
        //         formData.updated_at = nowIso;
        //         funds.unshift(formData);
        //         showToast("Fund record added successfully!");
        //     } else {
        //         // Update Existing Record
        //         funds[idx] = {
        //             ...funds[idx],
        //             ...formData,
        //             updated_at: nowIso
        //         };
        //         showToast("Fund record updated successfully!");
        //     }

        //     saveData();
        //     populateFilters();
        //     renderTable();
        //     updateStats();
        //     closeModal();
        // });

        // Delete Function
        function deleteRecord(index) {
            const fund = funds[index];
            if (confirm(`Are you sure you want to delete "${fund.fundname}" (${fund.fsrcid})?`)) {
                funds.splice(index, 1);
                saveData();
                populateFilters();
                renderTable();
                updateStats();
                showToast("Record deleted", true);
            }
        }

        // Event Listeners
        function setupEventListeners() {
            addNewBtn.addEventListener('click', openAddModal);
            closeModalBtn.addEventListener('click', closeModal);
            cancelModalBtn.addEventListener('click', closeModal);

            searchInput.addEventListener('input', renderTable);
            divisionFilter.addEventListener('change', renderTable);
            yearFilter.addEventListener('change', renderTable);

            resetFiltersBtn.addEventListener('click', () => {
                searchInput.value = '';
                divisionFilter.value = '';
                yearFilter.value = '';
                renderTable();
            });

            document.getElementById('exportBtn').addEventListener('click', exportToCSV);

            // Close modal on background click
            fundModal.addEventListener('click', (e) => {
                if (e.target === fundModal) closeModal();
            });
        }

        // Toast Feedback System
        function showToast(message, isWarning = false) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const toastIcon = document.getElementById('toastIcon');

            toastMessage.textContent = message;
            
            if (isWarning) {
                toastIcon.setAttribute('data-lucide', 'alert-circle');
                toastIcon.className = "w-5 h-5 text-rose-400";
            } else {
                toastIcon.setAttribute('data-lucide', 'check-circle');
                toastIcon.className = "w-5 h-5 text-emerald-400";
            }
            initLucide();

            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        // Export to CSV Function
        function exportToCSV() {
            if (funds.length === 0) {
                showToast("No data to export", true);
                return;
            }

            const headers = ["fsrcid", "fundname", "fundvalue", "yearactive", "divisionid", "created_at", "updated_at"];
            const csvRows = [];

            // Add Header Row
            csvRows.push(headers.join(','));

            // Add Data Rows
            for (const row of funds) {
                const values = headers.map(header => {
                    const val = row[header] === undefined || row[header] === null ? '' : row[header];
                    // Escape quotes and commas
                    const escaped = ('' + val).replace(/"/g, '""');
                    return `"${escaped}"`;
                });
                csvRows.push(values.join(','));
            }

            // Create Download Link
            const csvString = csvRows.join('\n');
            const blob = new Blob([csvString], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('href', url);
            a.setAttribute('download', `fund_records_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            showToast("CSV exported successfully");
        }
    </script>
</body>
</html>