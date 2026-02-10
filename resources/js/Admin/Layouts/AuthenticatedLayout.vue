<template>
    <div class="app-wrapper">
        <!-- Sidebar -->
        <aside
            class="sidebar"
            :class="{ 'expanded': isSidebarExpanded }"
            @mouseenter="isSidebarExpanded = true"
            @mouseleave="isSidebarExpanded = false"
        >
            <!-- Background Texture -->
            <div class="sidebar-texture"></div>

            <div class="sidebar-content">
                <!-- Logo Section -->
                <div class="sidebar-header">
                    <div class="logo-container">
                        <div class="logo-circle">
                            <svg class="logo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="logo-text">PayCenter</span>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="sidebar-nav">
                    <Link
                        :href="route('dashboard')"
                        class="nav-item"
                        :class="{ 'active': route().current('dashboard') }"
                    >
                        <div class="nav-icon-wrapper">
                            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="nav-label">Dashboard</span>
                    </Link>

                    <!-- <Link
                        :href="route('clients.index')"
                        class="nav-item"
                        :class="{ 'active': route().current('clients.*') }"
                    >
                        <div class="nav-icon-wrapper">
                            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <span class="nav-label">Clients</span>
                    </Link> -->

                    <Link
                        :href="route('transactions.index')"
                        class="nav-item"
                        :class="{ 'active': route().current('transactions.*') }"
                    >
                        <div class="nav-icon-wrapper">
                            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="nav-label">Transactions</span>
                    </Link>

                    <Link
                        :href="route('profile.edit')"
                        class="nav-item"
                        :class="{ 'active': route().current('profile.*') }"
                    >
                        <div class="nav-icon-wrapper">
                            <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="nav-label">Profile</span>
                    </Link>
                </nav>

                <!-- User Section -->
                <div class="sidebar-footer">
                    <div class="user-section" @click="showUserMenu = !showUserMenu">
                        <div class="user-avatar">
                            {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="user-info-text">
                            <div class="user-name-text">{{ $page.props.auth.user.name }}</div>
                            <div class="user-email-text">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-wrapper" :class="{ 'sidebar-expanded': isSidebarExpanded }">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <slot name="header">
                        <h1 class="page-title">Dashboard</h1>
                    </slot>
                </div>

                <div class="top-bar-right">
                    <Link :href="route('logout')" method="post" as="button" class="btn-logout">
                        <svg class="logout-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </Link>

                    <button class="icon-btn mobile-only" @click="showMobileMenu = !showMobileMenu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="main-content">
                <slot />
            </main>
        </div>

        <!-- Mobile Menu -->
        <div v-if="showMobileMenu" class="mobile-overlay" @click="showMobileMenu = false">
            <div class="mobile-menu" @click.stop>
                <div class="mobile-header">
                    <span class="mobile-logo">PayCenter</span>
                    <button @click="showMobileMenu = false" class="close-btn">
                        <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <nav class="mobile-nav">
                    <Link :href="route('dashboard')" class="mobile-nav-item" @click="showMobileMenu = false">Dashboard</Link>
                    <Link :href="route('clients.index')" class="mobile-nav-item" @click="showMobileMenu = false">Clients</Link>
                    <Link :href="route('transactions.index')" class="mobile-nav-item" @click="showMobileMenu = false">Transactions</Link>
                    <Link :href="route('profile.edit')" class="mobile-nav-item" @click="showMobileMenu = false">Profile</Link>
                    <Link :href="route('logout')" method="post" as="button" class="mobile-nav-item" @click="showMobileMenu = false">Logout</Link>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isSidebarExpanded = ref(false);
const showUserMenu = ref(false);
const showMobileMenu = ref(false);
</script>

<style scoped>
.app-wrapper {
    display: flex;
    min-height: 100vh;
    background: #f8f9fa;
}


/* Sidebar */
.sidebar {
    position: fixed;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    height: auto;
    max-height: calc(100vh - 20px);
    width: 65px;
    background: #0b1120;
    transition: width 0.3s ease;
    z-index: 100;
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(11, 17, 32, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.sidebar.expanded {
    width: 260px;
}

/* .sidebar-texture {
    position: absolute;
    inset: 0;
    opacity: 0.15;
    background-image: radial-gradient(rgba(220, 176, 29, 0.15) 1px, transparent 1px);
    background-size: 20px 20px;
    pointer-events: none;
} */

.sidebar-content {
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 10;
}

/* Logo Section */
.sidebar-header {
    height: 72px;
    display: flex;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0 0.75rem;
    width: 100%;
}

.logo-circle {
    width: 40px;
    height: 40px;
    background: #dcb01d;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #0b1120;
    flex-shrink: 0;
    transition: opacity 0.2s;
}

.logo-icon {
    width: 1.25rem;
    height: 1.25rem;
}

.logo-text {
    color: white;
    font-size: 1.25rem;
    font-weight: 700;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 0.3s ease 0.1s;
    letter-spacing: -0.025em;
}

.sidebar.expanded .logo-text {
    opacity: 1;
}

/* Navigation */
.sidebar-nav {
    padding: 1.25rem 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0 0.875rem;
    color: #94a3b8;
    text-decoration: none;
    transition: all 0.2s;
    white-space: nowrap;
    position: relative;
    height: 42px;
}

.nav-icon-wrapper {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
    transition: background 0.2s, color 0.2s;
}

.nav-item:hover .nav-icon-wrapper {
    background: rgba(220, 176, 29, 0.1);
    color: #dcb01d;
}

.nav-item.active .nav-icon-wrapper {
    background: #dcb01d;
    color: #0b1120;
}

.nav-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.nav-item:hover {
    color: #dcb01d;
}

.nav-item.active {
    color: #dcb01d;
}

.nav-label {
    opacity: 0;
    transition: opacity 0.3s ease 0.1s;
    font-size: 0.9375rem;
    font-weight: 500;
}

.sidebar.expanded .nav-label {
    opacity: 1;
}

/* Sidebar Footer */
.sidebar-footer {
    padding: 1.25rem 0.75rem 0.75rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}

.user-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 12px;
}

.user-section:hover {
    background: rgba(220, 176, 29, 0.05);
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #dcb01d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    color: #0b1120;
    flex-shrink: 0;
    font-size: 0.875rem;
}

.user-info-text {
    text-align: left;
    opacity: 0;
    transition: opacity 0.3s ease 0.1s;
    white-space: nowrap;
    overflow: hidden;
}

.sidebar.expanded .user-info-text {
    opacity: 1;
}

.user-name-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: white;
}

.user-email-text {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Main Wrapper */
.main-wrapper {
    flex: 1;
    margin-left: 65px;
    transition: margin-left 0.3s ease;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-wrapper.sidebar-expanded {
    margin-left: 260px;
}

/* Top Bar */
.top-bar {
    background: transparent;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
}

.top-bar-left {
    display: flex;
    align-items: center;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.top-bar-center {
    flex: 1;
    max-width: 480px;
    margin: 0 2rem;
}

.search-wrapper {
    position: relative;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 1.25rem;
    height: 1.25rem;
    color: #94a3b8;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    background: white;
    font-size: 0.9375rem;
    color: #0f172a;
    transition: all 0.2s;
    outline: none;
}

.search-input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.top-bar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.btn-logout {
    background: #ef4444;
    color: white;
    padding: 0.625rem 1.5rem;
    border-radius: 9999px;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-logout:hover {
    background: #dc2626;
    transform: translateY(-1px);
}

.logout-icon {
    width: 1rem;
    height: 1rem;
}

.icon-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
    position: relative;
}

.icon-btn:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
}

.notification-dot {
    position: absolute;
    top: 10px;
    right: 12px;
    width: 8px;
    height: 8px;
    background: #ef4444;
    border-radius: 50%;
    border: 2px solid white;
}

.mobile-menu-btn {
    display: none;
    padding: 0.5rem;
    background: none;
    border: none;
    cursor: pointer;
    color: #6b7280;
}

.menu-icon {
    width: 1.5rem;
    height: 1.5rem;
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 2rem;
}

.mobile-only {
    display: none;
}

/* Mobile Styles */
@media (max-width: 768px) {
    .mobile-only {
        display: flex;
    }

    .btn-logout {
        display: none;
    }

    .sidebar {
        transform: translateX(-100%);
        border-radius: 0;
        top: 0;
        max-height: 100vh;
    }

    .main-wrapper {
        margin-left: 0;
    }

    .main-wrapper.sidebar-expanded {
        margin-left: 0;
    }

    .mobile-menu-btn {
        display: block;
    }

    .mobile-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 200;
    }

    .mobile-menu {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        width: 280px;
        background: white;
        box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
    }

    .mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .mobile-logo {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .close-btn {
        padding: 0.5rem;
        background: none;
        border: none;
        cursor: pointer;
    }

    .close-icon {
        width: 1.5rem;
        height: 1.5rem;
        color: #6b7280;
    }

    .mobile-nav {
        padding: 1rem 0;
    }

    .mobile-nav-item {
        display: block;
        padding: 0.875rem 1.5rem;
        color: #2c3e50;
        text-decoration: none;
        transition: background 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .mobile-nav-item:hover {
        background: #f8f9fa;
    }
}

/* Custom scrollbar for sidebar */
.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}
</style>
