<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Enterprise Employee & Division Assignment System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0284c7',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .badge-pill { display: inline-flex; align-items: center; padding: 0.125rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; }
        .modal-backdrop { background-color: rgba(15, 23, 42, 0.55); backdrop-filter: blur(4px); }
        .transition-all-200 { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50">

    <!-- Top Navigation Bar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- App Title and Logo -->
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-tr from-sky-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xl shadow-md">
                        <i class="fa-solid me-0 fa-sitemap"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-800 leading-tight">DiviManager Pro</h1>
                        <p class="text-xs text-slate-500">Employee Division & Role Assignment Portal</p>
                    </div>
                </div>

                <!-- Global Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button onclick="openEmployeeModal()" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all-200 flex items-center shadow-sm">
                        <i class="fa-solid fa-user-plus mr-2"></i> Add Employee
                    </button>
                    <button onclick="openDivisionModal()" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-3 py-2 rounded-lg text-sm font-medium transition-all-200 flex items-center">
                        <i class="fa-solid fa-layer-group mr-2"></i> New Division
                    </button>
                    <button onclick="resetDataConfirmation()" title="Reset Sample Data" class="text-slate-400 hover:text-red-600 p-2 rounded-lg hover:bg-slate-100 transition-all-200">
                        <i class="fa-solid fa-rotate-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Employees -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Staff</p>
                    <h3 id="stat-total-employees" class="text-2xl font-bold text-slate-800 mt-1">0</h3>
                    <p class="text-xs text-emerald-600 mt-1"><i class="fa-solid fa-check-circle mr-1"></i>Active Workforce</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Total Divisions -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Divisions</p>
                    <h3 id="stat-total-divisions" class="text-2xl font-bold text-slate-800 mt-1">0</h3>
                    <p class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-building mr-1"></i>Operational Units</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-building-user"></i>
                </div>
            </div>

            <!-- Unassigned Personnel -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Unassigned</p>
                    <h3 id="stat-unassigned" class="text-2xl font-bold text-amber-600 mt-1">0</h3>
                    <p class="text-xs text-amber-600 mt-1"><i class="fa-solid fa-triangle-exclamation mr-1"></i>Action Required</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
            </div>

            <!-- System Admins & Managers -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Privileged Roles</p>
                    <h3 id="stat-privileged" class="text-2xl font-bold text-purple-600 mt-1">0</h3>
                    <p class="text-xs text-purple-600 mt-1"><i class="fa-solid fa-shield-halved mr-1"></i>Admins & Leads</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
            </div>
        </div>

        <!-- Tabs Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 mb-6 pb-2">
            <div class="flex space-x-1 sm:space-x-2 overflow-x-auto pb-2 sm:pb-0 custom-scrollbar">
                <button onclick="switchTab('table')" id="tab-btn-table" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap bg-sky-600 text-white shadow-sm">
                    <i class="fa-solid fa-list mr-2"></i> Employee Directory
                </button>
                <button onclick="switchTab('board')" id="tab-btn-board" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-table-columns mr-2"></i> Division Cards
                </button>
                <button onclick="switchTab('roles')" id="tab-btn-roles" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-shield-cat mr-2"></i> User Types & Matrix
                </button>
                <button onclick="switchTab('analytics')" id="tab-btn-analytics" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap text-slate-600 hover:bg-slate-100">
                    <i class="fa-solid fa-chart-pie mr-2"></i> Analytics Summary
                </button>
            </div>
        </div>

        <!-- TAB 1: EMPLOYEE DIRECTORY & TABLE VIEW -->
        <div id="tab-content-table" class="space-y-4">
            <!-- Filter & Search Toolbar -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-3">
                <!-- Search Input -->
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="filter-search" oninput="applyFilters()" placeholder="Search employee by name, email, or ID..." class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500">
                </div>

                <!-- Filters Group -->
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Division Filter -->
                    <select id="filter-division" onchange="applyFilters()" class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="ALL">All Divisions</option>
                        <!-- Dynamic Options loaded by JS -->
                    </select>

                    <!-- User Type Filter -->
                    <select id="filter-usertype" onchange="applyFilters()" class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="ALL">All User Types</option>
                        <option value="Super Admin">Super Admin</option>
                        <option value="Division Lead">Division Lead</option>
                        <option value="Manager">Manager</option>
                        <option value="Senior Staff">Senior Staff</option>
                        <option value="Staff">Staff</option>
                        <option value="Auditor">Auditor</option>
                    </select>

                    <!-- Status Filter -->
                    <select id="filter-status" onchange="applyFilters()" class="text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500">
                        <option value="ALL">All Statuses</option>
                        <option value="Active">Active</option>
                        <option value="On Leave">On Leave</option>
                        <option value="Inactive">Inactive</option>
                    </select>

                    <button onclick="clearFilters()" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all-200">
                        <i class="fa-solid fa-xmark mr-1"></i> Clear
                    </button>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-3.5">Employee</th>
                                <th class="px-6 py-3.5">Assigned Division</th>
                                <th class="px-6 py-3.5">User Type / Role</th>
                                <!-- <th class="px-6 py-3.5">Status</th> -->
                                <th class="px-6 py-3.5 text-right">Quick Assignment / Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employee-table-body" class="divide-y divide-slate-200 text-sm">
                            <?php
                                if (count($collection) > 0) {
                                    foreach($collection as $c) {
                                        echo "<tr class='hover:bg-slate-50/80 transition-all-200'>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap'> 
                                                    <div class='flex items-center space-x-3'>
                                                        <div>
                                                            <div class='font-semibold text-slate-800'>{$c->name}</div>
                                                            <div class='text-xs text-slate-500'> {$c->email} </div>
                                                        </div>
                                                    </div>
                                                  </td>";
                                            echo "<td>";
                                                echo "<select id='divsel'class='text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500'>";
                                                        foreach($division as $d) {
                                                            echo "<option value='{$d->divid}'> {$d->divisionname} </option>";
                                                        }
                                                 echo "</select>";
                                                 echo "</td>";
                                            echo "<td>";
                                                echo "<select id='rolesel' class='text-sm border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500'>";
                                                    echo "<option value='normal'> Staff </option>";
                                                    echo "<option value='chief'> Division Chief </option>";
                                                echo "</select>";
                                            echo "</td>";
                                            echo "<td class='px-6 py-4 whitespace-nowrap text-right text-sm'>
                                                        <button onclick='saveemp({$c->id})' class='text-sky-600 hover:text-sky-800 font-medium mr-3 inline-flex items-center text-xs px-3 py-2 border border-sky-600 rounded-md hover:bg-sky-50 transition'>
                                                            <i class='fa-solid fa-pen-to-square mr-1'></i> Save
                                                        </button>
                                                        <button onclick='deleteemp({$c->id})' class='text-slate-400 hover:text-red-600 inline-flex items-center text-xs'>
                                                            <i class='fa-solid fa-trash-can'></i>
                                                        </button>
                                                    </td>";
                                        echo "</tr>";
                                    }   
                                }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State Container -->
                <div id="table-empty-state" class="hidden p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                        <i class="fa-solid fa-user-slash"></i>
                    </div>
                    <h4 class="text-base font-semibold text-slate-700">No employees found</h4>
                    <p class="text-xs text-slate-500 mt-1">Try adjusting your search keywords or active dropdown filters.</p>
                </div>
            </div>
        </div>

        <!-- TAB 2: DIVISION BOARD VIEW -->
        <div id="tab-content-board" class="hidden space-y-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Division Overview Cards</h2>
                    <p class="text-xs text-slate-500">Group view of personnel allocated per operational division.</p>
                </div>
            </div>

            <div id="division-board-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Division Cards loaded dynamically -->
            </div>
        </div>

        <!-- TAB 3: USER TYPES & PERMISSION MATRIX -->
        <div id="tab-content-roles" class="hidden space-y-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-base font-bold text-slate-800">User Types & Access Rights Matrix</h2>
                    <p class="text-xs text-slate-500">Overview of roles available in the organization and their default privilege levels.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase">
                                <th class="px-4 py-3">User Type</th>
                                <th class="px-4 py-3">Badge Style</th>
                                <th class="px-4 py-3">Division Scope</th>
                                <th class="px-4 py-3">System Permissions</th>
                                <th class="px-4 py-3 text-center">Active Users</th>
                            </tr>
                        </thead>
                        <tbody id="user-types-matrix-body" class="divide-y divide-slate-200">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 4: ANALYTICS & CHARTS -->
        <div id="tab-content-analytics" class="hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Chart 1: Employee Distribution by Division -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-chart-pie text-sky-600 mr-2"></i> Division Member Headcount
                    </h3>
                    <div class="relative h-64">
                        <canvas id="chartDivisionDistribution"></canvas>
                    </div>
                </div>

                <!-- Chart 2: User Types Distribution -->
                <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fa-solid fa-chart-bar text-indigo-600 mr-2"></i> User Type Spread
                    </h3>
                    <div class="relative h-64">
                        <canvas id="chartUserTypeDistribution"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL 1: ADD / EDIT EMPLOYEE & ASSIGNMENT -->
    <div id="modal-employee" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="modal-backdrop fixed inset-0" onclick="closeEmployeeModal()"></div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-lg mx-4 z-10 overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 id="employee-modal-title" class="text-base font-bold text-slate-800">Assign & Update Employee</h3>
                <button onclick="closeEmployeeModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Form -->
            <form id="form-employee" onsubmit="saveEmployee(event)" class="p-6 space-y-4">
                <input type="hidden" id="emp-id">

                <!-- Full Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Full Name *</label>
                    <input type="text" id="emp-name" required placeholder="e.g. Eleanor Vance" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Corporate Email *</label>
                    <input type="email" id="emp-email" required placeholder="e.g. e.vance@company.org" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>

                <!-- Division Selection -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Assign Division *</label>
                    <select id="emp-division" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <!-- Dynamic Division List -->
                    </select>
                </div>

                <!-- User Type Selection -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">System User Type / Access Level *</label>
                    <select id="emp-usertype" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="Staff">Staff (Standard Member Access)</option>
                        <option value="Senior Staff">Senior Staff (Advanced Actions)</option>
                        <option value="Manager">Manager (Team Management)</option>
                        <option value="Division Lead">Division Lead (Full Division Admin)</option>
                        <option value="Super Admin">Super Admin (Global System Access)</option>
                        <option value="Auditor">Auditor (Read-Only Access)</option>
                    </select>
                </div>

                <!-- Status Selection -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Employment Status *</label>
                    <select id="emp-status" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="Active">Active</option>
                        <option value="On Leave">On Leave</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 flex justify-end space-x-2 border-t border-slate-100">
                    <button type="button" onclick="closeEmployeeModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100 transition-all-200">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-sky-600 hover:bg-sky-700 text-white shadow-sm transition-all-200">Save Assignment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: NEW DIVISION MODAL -->
    <div id="modal-division" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="modal-backdrop fixed inset-0" onclick="closeDivisionModal()"></div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xl w-full max-w-md mx-4 z-10 overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-800">Create New Division</h3>
                <button onclick="closeDivisionModal()" class="text-slate-400 hover:text-slate-600 p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form id="form-division" onsubmit="saveDivision(event)" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Division Name *</label>
                    <input type="text" id="div-name" required placeholder="e.g. Cybersecurity & InfoSec" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Division Code *</label>
                    <input type="text" id="div-code" required placeholder="e.g. SEC" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none uppercase">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Color Theme Badge</label>
                    <select id="div-color" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none bg-white">
                        <option value="sky">Sky Blue</option>
                        <option value="indigo">Indigo</option>
                        <option value="emerald">Emerald Green</option>
                        <option value="purple">Purple</option>
                        <option value="rose">Rose Red</option>
                        <option value="amber">Amber Orange</option>
                        <option value="teal">Teal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Description</label>
                    <textarea id="div-desc" rows="2" placeholder="Brief summary of division responsibilities..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-sky-500 focus:outline-none"></textarea>
                </div>
                <div class="pt-4 flex justify-end space-x-2 border-t border-slate-100">
                    <button type="button" onclick="closeDivisionModal()" class="px-4 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-100">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-semibold bg-sky-600 hover:bg-sky-700 text-white shadow-sm">Create Division</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: CONFIRMATION DIALOG (Replaces alert/confirm) -->
    <div id="modal-confirm" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="modal-backdrop fixed inset-0"></div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-2xl w-full max-w-sm mx-4 z-10 p-6 text-center">
            <div class="w-12 h-12 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-3 text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h4 id="confirm-title" class="text-base font-bold text-slate-800 mb-1">Are you sure?</h4>
            <p id="confirm-message" class="text-xs text-slate-500 mb-6">This action cannot be undone.</p>
            <div class="flex justify-center space-x-2">
                <button id="confirm-btn-cancel" class="px-4 py-2 text-xs font-semibold rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100">Cancel</button>
                <button id="confirm-btn-proceed" class="px-4 py-2 text-xs font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 shadow-sm">Proceed</button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2 pointer-events-none"></div>

<script>
    
    function deleteemp(id) {
        alert(id);
    }

    function saveemp(id){
        var divsel  = document.getElementById('divsel').value; 
        var rolesel = document.getElementById('rolesel').value; 
        
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content');

        fetch('/saveemp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id      : id,
                divid   : divsel,
                rolesel : rolesel
            })
        })
        .then(response => response.json())
        .then(data => {
            if (true) {
                Alert("Employee Successfully Updated")
            }
        })
        .catch(error => {
            alert(error);
        });
    }
</script>
  
</body>
</html>