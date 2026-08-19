<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Quick Assignment / Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employee-table-body" class="divide-y divide-slate-200 text-sm">
                            <!-- Rows dynamically populated by JavaScript -->
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

        // Default initial data set
        const INITIAL_DIVISIONS = [
            { id: 'DIV-UNASSIGNED', name: 'Unassigned Pool', code: 'NONE', color: 'amber', description: 'Employees awaiting division allocation' },
            { id: 'DIV-IT', name: 'Information Technology', code: 'IT', color: 'sky', description: 'Core infrastructure, software, and systems support' },
            { id: 'DIV-HR', name: 'Human Resources', code: 'HR', color: 'rose', description: 'Talent acquisition, employee relations, and payroll' },
            { id: 'DIV-FIN', name: 'Finance & Accounting', code: 'FIN', color: 'emerald', description: 'Financial auditing, budgeting, and corporate accounts' },
            { id: 'DIV-MKT', name: 'Marketing & Brand', code: 'MKT', color: 'purple', description: 'Public relations, digital media, and campaigns' },
            { id: 'DIV-OPS', name: 'Operations & Logistics', code: 'OPS', color: 'teal', description: 'Supply chain management and internal facility operations' }
        ];

        const INITIAL_EMPLOYEES = [
            { id: 'EMP-101', name: 'Sarah Jenkins', email: 's.jenkins@company.com', divisionId: 'DIV-IT', userType: 'Division Lead', status: 'Active', dateJoined: '2022-03-15' },
            { id: 'EMP-102', name: 'Michael Chen', email: 'm.chen@company.com', divisionId: 'DIV-IT', userType: 'Senior Staff', status: 'Active', dateJoined: '2021-08-10' },
            { id: 'EMP-103', name: 'Amara Okafor', email: 'a.okafor@company.com', divisionId: 'DIV-HR', userType: 'Manager', status: 'Active', dateJoined: '2020-01-22' },
            { id: 'EMP-104', name: 'David Miller', email: 'd.miller@company.com', divisionId: 'DIV-FIN', userType: 'Division Lead', status: 'Active', dateJoined: '2019-11-05' },
            { id: 'EMP-105', name: 'Elena Rostova', email: 'e.rostova@company.com', divisionId: 'DIV-MKT', userType: 'Super Admin', status: 'Active', dateJoined: '2018-05-12' },
            { id: 'EMP-106', name: 'James Wilson', email: 'j.wilson@company.com', divisionId: 'DIV-UNASSIGNED', userType: 'Staff', status: 'Active', dateJoined: '2024-02-01' },
            { id: 'EMP-107', name: 'Sofia Rodriguez', email: 's.rodriguez@company.com', divisionId: 'DIV-OPS', userType: 'Staff', status: 'On Leave', dateJoined: '2023-06-18' },
            { id: 'EMP-108', name: 'Lucas Vance', email: 'l.vance@company.com', divisionId: 'DIV-UNASSIGNED', userType: 'Auditor', status: 'Active', dateJoined: '2024-01-15' }
        ];

        const USER_TYPES_CONFIG = {
            'Super Admin': { badge: 'bg-purple-100 text-purple-800 border-purple-200', scope: 'Global System', permissions: 'Full Access, Assign Roles, Create Divisions, System Config' },
            'Division Lead': { badge: 'bg-indigo-100 text-indigo-800 border-indigo-200', scope: 'Division Level', permissions: 'Division Admin, Member Allocation, Resource Approval' },
            'Manager': { badge: 'bg-sky-100 text-sky-800 border-sky-200', scope: 'Team Level', permissions: 'Team Operations, Direct Reports, Staff Reviews' },
            'Senior Staff': { badge: 'bg-teal-100 text-teal-800 border-teal-200', scope: 'Assigned Division', permissions: 'Standard Actions, Task Execution, Senior Workflows' },
            'Staff': { badge: 'bg-slate-100 text-slate-700 border-slate-200', scope: 'Assigned Division', permissions: 'Standard Member Access, Read & Submit Work' },
            'Auditor': { badge: 'bg-amber-100 text-amber-800 border-amber-200', scope: 'Read-Only Global', permissions: 'View Division Logs & Analytics Only' }
        };

        // App Local Storage State
        let divisions = [];
        let employees = [];
        let activeTab = 'table';
        let chartInstance1 = null;
        let chartInstance2 = null;

        function loadState() {
            const storedDivs = localStorage.getItem('divimanager_divisions');
            const storedEmps = localStorage.getItem('divimanager_employees');

            divisions = storedDivs ? JSON.parse(storedDivs) : [...INITIAL_DIVISIONS];
            employees = storedEmps ? JSON.parse(storedEmps) : [...INITIAL_EMPLOYEES];
        }

        function saveState() {
            localStorage.setItem('divimanager_divisions', JSON.stringify(divisions));
            localStorage.setItem('divimanager_employees', JSON.stringify(employees));
            renderAll();
        }

        function resetDataConfirmation() {
            showConfirmationModal('Reset All Data?', 'This will restore default divisions and employees dataset.', () => {
                divisions = [...INITIAL_DIVISIONS];
                employees = [...INITIAL_EMPLOYEES];
                saveState();
                showToast('System reset to default state.', 'info');
            });
        }

        function renderEmployeeTable() {
            const tbody = document.getElementById('employee-table-body');
            const emptyState = document.getElementById('table-empty-state');

            const searchVal = document.getElementById('filter-search').value.toLowerCase();
            const divFilter = document.getElementById('filter-division').value;
            const typeFilter = document.getElementById('filter-usertype').value;
            const statusFilter = document.getElementById('filter-status').value;

            // Filter logic
            const filtered = employees.filter(emp => {
                const matchesSearch = emp.name.toLowerCase().includes(searchVal) ||
                                      emp.email.toLowerCase().includes(searchVal) ||
                                      emp.id.toLowerCase().includes(searchVal);
                const matchesDiv = divFilter === 'ALL' || emp.divisionId === divFilter;
                const matchesType = typeFilter === 'ALL' || emp.userType === typeFilter;
                const matchesStatus = statusFilter === 'ALL' || emp.status === statusFilter;

                return matchesSearch && matchesDiv && matchesType && matchesStatus;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');

            tbody.innerHTML = filtered.map(emp => {
                const div = divisions.find(d => d.id === emp.divisionId) || { name: 'Unassigned', color: 'slate' };
                const userTypeConf = USER_TYPES_CONFIG[emp.userType] || { badge: 'bg-slate-100 text-slate-700' };

                let statusBadge = 'bg-emerald-100 text-emerald-700';
                if (emp.status === 'On Leave') statusBadge = 'bg-amber-100 text-amber-700';
                if (emp.status === 'Inactive') statusBadge = 'bg-slate-100 text-slate-500';

                const divisionBadgeColor = getDivisionBadgeStyle(div.color);

                return `
                    <tr class="hover:bg-slate-50/80 transition-all-200">
                        <!-- Employee Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase shadow-inner">
                                    ${emp.name.split(' ').map(n=>n[0]).join('')}
                                </div>
                                <div>
                                    <div class="font-semibold text-slate-800">${escapeHtml(emp.name)}</div>
                                    <div class="text-xs text-slate-500">${escapeHtml(emp.email)} • <span class="text-slate-400">${emp.id}</span></div>
                                </div>
                            </div>
                        </td>

                        <!-- Assigned Division Dropdown / Badge -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge-pill border ${divisionBadgeColor}">
                                <i class="fa-solid fa-layer-group text-xs mr-1.5"></i> ${escapeHtml(div.name)}
                            </span>
                        </td>

                        <!-- User Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="badge-pill border ${userTypeConf.badge}">
                                <i class="fa-solid fa-user-gear text-xs mr-1.5"></i> ${emp.userType}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 rounded-md text-xs font-medium ${statusBadge}">
                                ${emp.status}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            <button onclick="openEmployeeModal('${emp.id}')" class="text-sky-600 hover:text-sky-800 font-medium mr-3 inline-flex items-center text-xs">
                                <i class="fa-solid fa-pen-to-square mr-1"></i> Edit & Assign
                            </button>
                            <button onclick="confirmDeleteEmployee('${emp.id}')" class="text-slate-400 hover:text-red-600 inline-flex items-center text-xs">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function renderDivisionBoard() {
            const grid = document.getElementById('division-board-grid');

            grid.innerHTML = divisions.map(div => {
                const members = employees.filter(e => e.divisionId === div.id);
                const colorStyle = getDivisionHeaderStyle(div.color);

                return `
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                        <!-- Division Header -->
                        <div class="p-4 ${colorStyle.bg} border-b border-slate-100 flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-white/80 ${colorStyle.text} uppercase tracking-wider">${div.code}</span>
                                    <h3 class="font-bold text-slate-800">${escapeHtml(div.name)}</h3>
                                </div>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">${escapeHtml(div.description || 'No description provided.')}</p>
                            </div>
                            <span class="text-xs font-bold bg-white px-2.5 py-1 rounded-full border border-slate-200 text-slate-700 shadow-xs">
                                ${members.length} ${members.length === 1 ? 'member' : 'members'}
                            </span>
                        </div>

                        <!-- Division Members List -->
                        <div class="p-4 flex-1 space-y-2 max-h-64 overflow-y-auto custom-scrollbar">
                            ${members.length === 0 ? `
                                <div class="text-center py-6 text-slate-400 text-xs italic">
                                    No personnel assigned to this division.
                                </div>
                            ` : members.map(m => `
                                <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 hover:bg-slate-100 transition-all-200">
                                    <div class="flex items-center space-x-2.5 overflow-hidden">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                            ${m.name.split(' ').map(n=>n[0]).join('')}
                                        </div>
                                        <div class="truncate">
                                            <p class="text-xs font-semibold text-slate-800 truncate">${escapeHtml(m.name)}</p>
                                            <p class="text-[10px] text-slate-500">${m.userType}</p>
                                        </div>
                                    </div>
                                    <button onclick="openEmployeeModal('${m.id}')" title="Reassign" class="text-slate-400 hover:text-sky-600 text-xs px-1.5 py-1">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                    </button>
                                </div>
                            `).join('')}
                        </div>

                        <!-- Card Footer -->
                        <div class="p-3 bg-slate-50 border-t border-slate-100 flex justify-between items-center text-xs">
                            <button onclick="quickAssignToDiv('${div.id}')" class="text-sky-600 hover:text-sky-700 font-semibold inline-flex items-center">
                                <i class="fa-solid fa-plus mr-1"></i> Add Member
                            </button>
                            ${div.id !== 'DIV-UNASSIGNED' ? `
                                <button onclick="confirmDeleteDivision('${div.id}')" class="text-slate-400 hover:text-red-600 text-xs">
                                    Remove Division
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderUserTypesMatrix() {
            const tbody = document.getElementById('user-types-matrix-body');

            tbody.innerHTML = Object.keys(USER_TYPES_CONFIG).map(typeKey => {
                const conf = USER_TYPES_CONFIG[typeKey];
                const count = employees.filter(e => e.userType === typeKey).length;

                return `
                    <tr class="hover:bg-slate-50/80 transition-all-200">
                        <td class="px-4 py-3 font-semibold text-slate-800">${typeKey}</td>
                        <td class="px-4 py-3">
                            <span class="badge-pill border ${conf.badge}">
                                ${typeKey}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-600 text-xs font-medium">${conf.scope}</td>
                        <td class="px-4 py-3 text-slate-500 text-xs">${conf.permissions}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-full text-xs">${count}</span>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function updateStatsAndDropdowns() {
            // Stat Cards
            document.getElementById('stat-total-employees').textContent = employees.length;
            document.getElementById('stat-total-divisions').textContent = divisions.filter(d => d.id !== 'DIV-UNASSIGNED').length;

            const unassignedCount = employees.filter(e => e.divisionId === 'DIV-UNASSIGNED').length;
            document.getElementById('stat-unassigned').textContent = unassignedCount;

            const privilegedCount = employees.filter(e => ['Super Admin', 'Division Lead', 'Manager'].includes(e.userType)).length;
            document.getElementById('stat-privileged').textContent = privilegedCount;

            // Populate Filter Division Dropdown
            const filterDivSelect = document.getElementById('filter-division');
            const currentFilterVal = filterDivSelect.value || 'ALL';
            filterDivSelect.innerHTML = `<option value="ALL">All Divisions</option>` +
                divisions.map(d => `<option value="${d.id}">${escapeHtml(d.name)}</option>`).join('');
            filterDivSelect.value = currentFilterVal;

            // Populate Modal Division Dropdown
            const empDivSelect = document.getElementById('emp-division');
            empDivSelect.innerHTML = divisions.map(d => `<option value="${d.id}">${escapeHtml(d.name)}</option>`).join('');
        }

        function renderAnalyticsCharts() {
            if (activeTab !== 'analytics') return;

            const divNames = divisions.map(d => d.name);
            const divCounts = divisions.map(d => employees.filter(e => e.divisionId === d.id).length);

            const userTypes = Object.keys(USER_TYPES_CONFIG);
            const userTypeCounts = userTypes.map(t => employees.filter(e => e.userType === t).length);

            // Chart 1: Division Doughnut
            const ctx1 = document.getElementById('chartDivisionDistribution').getContext('2d');
            if (chartInstance1) chartInstance1.destroy();
            chartInstance1 = new Chart(ctx1, {
                type: 'doughnut',
                data: {
                    labels: divNames,
                    datasets: [{
                        data: divCounts,
                        backgroundColor: ['#f59e0b', '#0284c7', '#f43f5e', '#10b981', '#a855f7', '#14b8a6', '#64748b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'right' } }
                }
            });

            // Chart 2: User Types Bar Chart
            const ctx2 = document.getElementById('chartUserTypeDistribution').getContext('2d');
            if (chartInstance2) chartInstance2.destroy();
            chartInstance2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: userTypes,
                    datasets: [{
                        label: 'Employee Count',
                        data: userTypeCounts,
                        backgroundColor: '#6366f1',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                    plugins: { legend: { display: false } }
                }
            });
        }

        function switchTab(tabId) {
            activeTab = tabId;
            const tabs = ['table', 'board', 'roles', 'analytics'];

            tabs.forEach(t => {
                const btn = document.getElementById(`tab-btn-${t}`);
                const content = document.getElementById(`tab-content-${t}`);

                if (t === tabId) {
                    btn.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap bg-sky-600 text-white shadow-sm';
                    content.classList.remove('hidden');
                } else {
                    btn.className = 'px-4 py-2 rounded-lg text-sm font-semibold transition-all-200 whitespace-nowrap text-slate-600 hover:bg-slate-100';
                    content.classList.add('hidden');
                }
            });

            renderAll();
        }

        function renderAll() {
            updateStatsAndDropdowns();
            renderEmployeeTable();
            renderDivisionBoard();
            renderUserTypesMatrix();
            renderAnalyticsCharts();
        }

        function applyFilters() {
            renderEmployeeTable();
        }

        function clearFilters() {
            document.getElementById('filter-search').value = '';
            document.getElementById('filter-division').value = 'ALL';
            document.getElementById('filter-usertype').value = 'ALL';
            document.getElementById('filter-status').value = 'ALL';
            renderEmployeeTable();
        }

        function openEmployeeModal(empId = null) {
            const modal = document.getElementById('modal-employee');
            const title = document.getElementById('employee-modal-title');
            const form = document.getElementById('form-employee');

            if (empId) {
                const emp = employees.find(e => e.id === empId);
                if (!emp) return;

                title.textContent = 'Edit Employee & Division Assignment';
                document.getElementById('emp-id').value = emp.id;
                document.getElementById('emp-name').value = emp.name;
                document.getElementById('emp-email').value = emp.email;
                document.getElementById('emp-division').value = emp.divisionId;
                document.getElementById('emp-usertype').value = emp.userType;
                document.getElementById('emp-status').value = emp.status;
            } else {
                title.textContent = 'Add New Employee';
                form.reset();
                document.getElementById('emp-id').value = '';
                document.getElementById('emp-division').value = 'DIV-UNASSIGNED';
                document.getElementById('emp-usertype').value = 'Staff';
                document.getElementById('emp-status').value = 'Active';
            }

            modal.classList.remove('hidden');
        }

        function closeEmployeeModal() {
            document.getElementById('modal-employee').classList.add('hidden');
        }

        function saveEmployee(event) {
            event.preventDefault();

            const id = document.getElementById('emp-id').value;
            const name = document.getElementById('emp-name').value.trim();
            const email = document.getElementById('emp-email').value.trim();
            const divisionId = document.getElementById('emp-division').value;
            const userType = document.getElementById('emp-usertype').value;
            const status = document.getElementById('emp-status').value;

            if (id) {
                // Update existing
                const index = employees.findIndex(e => e.id === id);
                if (index !== -1) {
                    employees[index] = { ...employees[index], name, email, divisionId, userType, status };
                    showToast('Employee details updated successfully!', 'success');
                }
            } else {
                // Create new
                const newEmp = {
                    id: 'EMP-' + Math.floor(100 + Math.random() * 900),
                    name,
                    email,
                    divisionId,
                    userType,
                    status,
                    dateJoined: new Date().toISOString().split('T')[0]
                };
                employees.push(newEmp);
                showToast('New employee added and assigned!', 'success');
            }

            saveState();
            closeEmployeeModal();
        }

        function quickAssignToDiv(divId) {
            openEmployeeModal();
            document.getElementById('emp-division').value = divId;
        }

        function confirmDeleteEmployee(empId) {
            const emp = employees.find(e => e.id === empId);
            if (!emp) return;

            showConfirmationModal('Delete Employee Record?', `Are you sure you want to remove ${emp.name} (${emp.id})?`, () => {
                employees = employees.filter(e => e.id !== empId);
                saveState();
                showToast('Employee record removed.', 'info');
            });
        }

        function openDivisionModal() {
            document.getElementById('form-division').reset();
            document.getElementById('modal-division').classList.remove('hidden');
        }

        function closeDivisionModal() {
            document.getElementById('modal-division').classList.add('hidden');
        }

        function saveDivision(event) {
            event.preventDefault();

            const name = document.getElementById('div-name').value.trim();
            const code = document.getElementById('div-code').value.trim().toUpperCase();
            const color = document.getElementById('div-color').value;
            const description = document.getElementById('div-desc').value.trim();

            const newDiv = {
                id: 'DIV-' + code.replace(/[^A-Z0-9]/g, ''),
                name,
                code,
                color,
                description
            };

            divisions.push(newDiv);
            saveState();
            closeDivisionModal();
            showToast(`Division "${name}" created successfully!`, 'success');
        }

        function confirmDeleteDivision(divId) {
            const div = divisions.find(d => d.id === divId);
            if (!div) return;

            showConfirmationModal(
                'Delete Division?',
                `Deleting "${div.name}" will move all assigned members to the Unassigned Pool.`,
                () => {
                    // Move members to unassigned
                    employees.forEach(e => {
                        if (e.divisionId === divId) {
                            e.divisionId = 'DIV-UNASSIGNED';
                        }
                    });
                    // Remove division
                    divisions = divisions.filter(d => d.id !== divId);
                    saveState();
                    showToast(`Division "${div.name}" deleted. Members moved to Unassigned.`, 'info');
                }
            );
        }

        function showConfirmationModal(title, message, onConfirm) {
            const modal = document.getElementById('modal-confirm');
            document.getElementById('confirm-title').textContent = title;
            document.getElementById('confirm-message').textContent = message;

            const btnCancel = document.getElementById('confirm-btn-cancel');
            const btnProceed = document.getElementById('confirm-btn-proceed');

            const cleanup = () => {
                modal.classList.add('hidden');
                btnCancel.onclick = null;
                btnProceed.onclick = null;
            };

            btnCancel.onclick = cleanup;
            btnProceed.onclick = () => {
                cleanup();
                onConfirm();
            };

            modal.classList.remove('hidden');
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            let bg = 'bg-slate-800 text-white';
            let icon = 'fa-check-circle text-emerald-400';

            if (type === 'info') {
                bg = 'bg-slate-800 text-white';
                icon = 'fa-circle-info text-sky-400';
            }

            toast.className = `${bg} px-4 py-3 rounded-xl shadow-lg text-xs font-medium flex items-center space-x-2 pointer-events-auto transform transition-all duration-300 opacity-0 translate-y-2`;
            toast.innerHTML = `<i class="fa-solid ${icon} text-sm"></i><span>${escapeHtml(message)}</span>`;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('opacity-0', 'translate-y-2');
            }, 10);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function getDivisionBadgeStyle(color) {
            const styles = {
                sky: 'bg-sky-50 text-sky-700 border-sky-200',
                indigo: 'bg-indigo-50 text-indigo-700 border-indigo-200',
                emerald: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                purple: 'bg-purple-50 text-purple-700 border-purple-200',
                rose: 'bg-rose-50 text-rose-700 border-rose-200',
                amber: 'bg-amber-50 text-amber-700 border-amber-200',
                teal: 'bg-teal-50 text-teal-700 border-teal-200',
                slate: 'bg-slate-50 text-slate-700 border-slate-200'
            };
            return styles[color] || styles.slate;
        }

        function getDivisionHeaderStyle(color) {
            const styles = {
                sky: { bg: 'bg-sky-50', text: 'text-sky-700' },
                indigo: { bg: 'bg-indigo-50', text: 'text-indigo-700' },
                emerald: { bg: 'bg-emerald-50', text: 'text-emerald-700' },
                purple: { bg: 'bg-purple-50', text: 'text-purple-700' },
                rose: { bg: 'bg-rose-50', text: 'text-rose-700' },
                amber: { bg: 'bg-amber-50', text: 'text-amber-700' },
                teal: { bg: 'bg-teal-50', text: 'text-teal-700' },
                slate: { bg: 'bg-slate-50', text: 'text-slate-700' }
            };
            return styles[color] || styles.slate;
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>"']/g, function(m) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                }[m];
            });
        }

        window.onload = function() {
            loadState();
            renderAll();
        };
    </script>
</body>
</html>