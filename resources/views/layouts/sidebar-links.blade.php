<!-- Dashboard (Always Visible) -->
<a href="{{ route('dashboard') }}" 
   title="Dashboard"
   class="{{ request()->routeIs('dashboard') ? 'text-brand font-black' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-300">
    <i class="fa-solid fa-gauge-high text-lg w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-brand' : '' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3 transition-opacity">Dashboard</span>
</a>

<!-- Customer (Parent Menu) -->
<a href="{{ route('customers.index') }}" 
   title="Customer"
   class="{{ request()->routeIs('customers.*') ? 'text-brand font-black' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }} group flex items-center px-3 py-2.5 mt-1 text-sm font-medium rounded-xl transition-all duration-300">
    <i class="fa-solid fa-address-book text-lg w-6 shrink-0 {{ request()->routeIs('customers.*') ? 'text-brand' : '' }}"></i>
    <span x-show="!sidebarCollapsed" class="ml-3 transition-opacity">Customer</span>
</a>

@php
    $isPipelineActive = request()->routeIs('leads.*') || request()->routeIs('activities.*') || request()->routeIs('orders.*');
    $isOperationalActive = request()->routeIs('tasks.*') || request()->routeIs('notifications.*') || request()->routeIs('reports.*');
    $isMasterActive = request()->routeIs('products.*') || request()->routeIs('users.*') || request()->routeIs('roles.*');
    $isSettingsActive = request()->routeIs('settings.*');
@endphp

<!-- CRM PIPELINE GROUP -->
<div x-data="{ open: {{ $isPipelineActive ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="w-full group mt-1 flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all
                {{ $isPipelineActive ? 'bg-brand/5 text-brand' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            :title="sidebarCollapsed ? 'CRM Pipeline' : ''">
        <i class="fa-solid fa-diagram-project text-lg w-6 shrink-0 {{ $isPipelineActive ? 'text-brand' : 'text-gray-400 group-hover:text-brand' }}"></i>
        <span x-show="!sidebarCollapsed" class="ml-3 flex-1 text-left {{ $isPipelineActive ? 'font-black' : '' }}">CRM Pipeline</span>
        <i x-show="!sidebarCollapsed" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 {{ $isPipelineActive ? 'text-brand' : 'text-gray-300' }}" :class="open ? 'rotate-180' : ''"></i>
    </button>
    
    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 pl-9">
        <a href="{{ route('leads.index') }}" class="{{ request()->routeIs('leads.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Lead Management</a>
        <a href="{{ route('activities.index') }}" class="{{ request()->routeIs('activities.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Activity / Follow Up</a>
        <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Sales Order</a>
    </div>
</div>

<!-- OPERATIONAL GROUP -->
<div x-data="{ open: {{ $isOperationalActive ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="w-full group mt-1 flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all
                {{ $isOperationalActive ? 'bg-brand/5 text-brand' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            :title="sidebarCollapsed ? 'Operational' : ''">
        <i class="fa-solid fa-briefcase text-lg w-6 shrink-0 {{ $isOperationalActive ? 'text-brand' : 'text-gray-400 group-hover:text-brand' }}"></i>
        <span x-show="!sidebarCollapsed" class="ml-3 flex-1 text-left {{ $isOperationalActive ? 'font-black' : '' }}">Operational</span>
        <i x-show="!sidebarCollapsed" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 {{ $isOperationalActive ? 'text-brand' : 'text-gray-300' }}" :class="open ? 'rotate-180' : ''"></i>
    </button>
    
    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 pl-9">
        <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Tasks</a>
        <a href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Notification Center</a>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Reports & Analytics</a>
    </div>
</div>

<!-- MASTER DATA GROUP -->
<div x-data="{ open: {{ $isMasterActive ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="w-full group mt-1 flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all
                {{ $isMasterActive ? 'bg-brand/5 text-brand' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            :title="sidebarCollapsed ? 'Master Data' : ''">
        <i class="fa-solid fa-database text-lg w-6 shrink-0 {{ $isMasterActive ? 'text-brand' : 'text-gray-400 group-hover:text-brand' }}"></i>
        <span x-show="!sidebarCollapsed" class="ml-3 flex-1 text-left {{ $isMasterActive ? 'font-black' : '' }}">Master Data</span>
        <i x-show="!sidebarCollapsed" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 {{ $isMasterActive ? 'text-brand' : 'text-gray-300' }}" :class="open ? 'rotate-180' : ''"></i>
    </button>
    
    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 pl-9">
        <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Master Product</a>
        
        @if(Auth::user()->role === 'superadmin')
        <a href="{{ route('accounts.index') }}" class="{{ request()->routeIs('accounts.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Master Perusahaan</a>
        @endif

        @if(Auth::user()->role === 'superadmin' || Auth::user()->role === 'manager_marketing')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">User Management</a>
        @endif

        @if(Auth::user()->role === 'superadmin')
        <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Roles & Permissions</a>
        @endif
    </div>
</div>

<!-- SYSTEM SETTINGS GROUP -->
@if(Auth::user()->role === 'superadmin')
<div x-data="{ open: {{ $isSettingsActive ? 'true' : 'false' }} }">
    <button @click="open = !open" 
            class="w-full group mt-1 flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all
                {{ $isSettingsActive ? 'bg-brand/5 text-brand' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}"
            :title="sidebarCollapsed ? 'Settings' : ''">
        <i class="fa-solid fa-gears text-lg w-6 shrink-0 {{ $isSettingsActive ? 'text-brand' : 'text-gray-400 group-hover:text-brand' }}"></i>
        <span x-show="!sidebarCollapsed" class="ml-3 flex-1 text-left {{ $isSettingsActive ? 'font-black' : '' }}">Settings</span>
        <i x-show="!sidebarCollapsed" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 {{ $isSettingsActive ? 'text-brand' : 'text-gray-300' }}" :class="open ? 'rotate-180' : ''"></i>
    </button>
    
    <div x-show="open && !sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-1 space-y-1 pl-9">
        <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.index') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">General Settings</a>
        <a href="{{ route('settings.logs') }}" class="{{ request()->routeIs('settings.logs') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Notif Logs</a>
        <a href="{{ route('settings.audit-logs') }}" class="{{ request()->routeIs('settings.audit-logs') ? 'text-brand font-black' : 'text-gray-500 hover:text-gray-900' }} block py-2 text-[11px] font-bold uppercase tracking-widest transition-colors">Audit Logs</a>
    </div>
</div>
@endif
