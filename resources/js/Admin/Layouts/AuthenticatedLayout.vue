<template>
    <div>
        <div class="layout-container">
            <nav class="navigation-bar">
                <!-- Primary Navigation Menu -->
                <div class="nav-wrapper">
                    <div class="nav-content">
                        <div class="nav-left">
                            <!-- Logo -->
                            <div class="logo-container">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo class="nav-logo" />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="nav-links">
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    :href="route('clients.index')"
                                    :active="route().current('clients.*')"
                                >
                                    Clients
                                </NavLink>
                                <NavLink
                                    :href="route('transactions.index')"
                                    :active="route().current('transactions.*')"
                                >
                                    Transactions
                                </NavLink>
                                <NavLink
                                    :href="route('pay.index')"
                                    :active="route().current('pay.*')"
                                >
                                    Make Payment
                                </NavLink>
                            </div>
                        </div>

                        <div class="nav-right">
                            <!-- Settings Dropdown -->
                            <div class="settings-dropdown">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="trigger-span">
                                            <button
                                                type="button"
                                                class="trigger-button"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="trigger-icon"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="hamburger-container">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="hamburger-button"
                            >
                                <svg
                                    class="hamburger-icon"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="responsive-menu"
                >
                    <div class="responsive-links">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('clients.index')"
                            :active="route().current('clients.*')"
                        >
                            Clients
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('transactions.index')"
                            :active="route().current('transactions.*')"
                        >
                            Transactions
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('pay.index')"
                            :active="route().current('pay.*')"
                        >
                            Make Payment
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="responsive-settings">
                        <div class="user-info">
                            <div class="user-name">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="user-email">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="settings-links">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="page-header" v-if="$slots.header">
                <div class="header-content">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Admin/Components/ApplicationLogo.vue';
import Dropdown from '@/Admin/Components/Dropdown.vue';
import DropdownLink from '@/Admin/Components/DropdownLink.vue';
import NavLink from '@/Admin/Components/NavLink.vue';
import ResponsiveNavLink from '@/Admin/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<style scoped>
.layout-container {
    min-height: 100vh;
    background-color: #f3f4f6;
}

@media (prefers-color-scheme: dark) {
    .layout-container {
        background-color: #111827;
    }
}

.navigation-bar {
    background-color: #ffffff;
    border-bottom: 1px solid #f3f4f6;
}

@media (prefers-color-scheme: dark) {
    .navigation-bar {
        background-color: #1f2937;
        border-color: #374151;
    }
}

.nav-wrapper {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1rem;
}

@media (min-width: 640px) {
    .nav-wrapper {
        padding: 0 1.5rem;
    }
}

@media (min-width: 1024px) {
    .nav-wrapper {
        padding: 0 2rem;
    }
}

.nav-content {
    display: flex;
    justify-content: space-between;
    height: 4rem;
}

.nav-left {
    display: flex;
}

.logo-container {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.nav-logo {
    display: block;
    height: 2.25rem;
    width: auto;
    fill: currentColor;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .nav-logo {
        color: #e5e7eb;
    }
}

.nav-links {
    display: none;
    margin-left: 2.5rem;
}

@media (min-width: 640px) {
    .nav-links {
        display: flex;
        gap: 2rem;
        margin-top: -1px;
        margin-bottom: -1px;
    }
}

.nav-right {
    display: none;
}

@media (min-width: 640px) {
    .nav-right {
        display: flex;
        align-items: center;
        margin-left: 1.5rem;
    }
}

.settings-dropdown {
    position: relative;
    margin-left: 0.75rem;
}

.trigger-span {
    display: inline-flex;
    border-radius: 0.375rem;
}

.trigger-button {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border: 1px solid transparent;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25rem;
    color: #6b7280;
    background-color: #ffffff;
    border-radius: 0.375rem;
    transition: color 0.15s ease-in-out;
    cursor: pointer;
}

.trigger-button:hover {
    color: #374151;
}

.trigger-button:focus {
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .trigger-button {
        background-color: #1f2937;
        color: #9ca3af;
        border-color: transparent;
    }
    .trigger-button:hover {
        color: #d1d5db;
    }
}

.trigger-icon {
    margin-left: 0.5rem;
    margin-right: -0.125rem;
    height: 1rem;
    width: 1rem;
}

.hamburger-container {
    display: flex;
    align-items: center;
    margin-right: -0.5rem;
}

@media (min-width: 640px) {
    .hamburger-container {
        display: none;
    }
}

.hamburger-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    border-radius: 0.375rem;
    color: #9ca3af;
    transition: background-color 0.15s, color 0.15s;
    background: none;
    border: none;
    cursor: pointer;
}

.hamburger-button:hover {
    background-color: #f3f4f6;
    color: #6b7280;
}

.hamburger-button:focus {
    background-color: #f3f4f6;
    color: #6b7280;
    outline: none;
}

@media (prefers-color-scheme: dark) {
    .hamburger-button {
        color: #6b7280;
    }
    .hamburger-button:hover {
        background-color: #111827;
        color: #9ca3af;
    }
    .hamburger-button:focus {
        background-color: #111827;
        color: #9ca3af;
    }
}

.hamburger-icon {
    height: 1.5rem;
    width: 1.5rem;
}

.hidden {
    display: none;
}

.inline-flex {
    display: inline-flex;
}

.block {
    display: block;
}

.responsive-menu {
    display: none; /* Controlled by v-bind class in template but need baseline */
}

@media (max-width: 639px) {
    .responsive-menu.block {
        display: block;
    }
}

.responsive-links {
    padding-top: 0.5rem;
    padding-bottom: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.responsive-settings {
    padding-top: 1rem;
    padding-bottom: 0.25rem;
    border-top: 1px solid #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    .responsive-settings {
        border-color: #4b5563;
    }
}

.user-info {
    padding: 0 1rem;
}

.user-name {
    font-size: 1rem;
    font-weight: 500;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .user-name {
        color: #e5e7eb;
    }
}

.user-email {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
}

.settings-links {
    margin-top: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.page-header {
    background-color: #ffffff;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

@media (prefers-color-scheme: dark) {
    .page-header {
        background-color: #1f2937;
    }
}

.header-content {
    max-width: 80rem;
    margin: 0 auto;
    padding: 1.5rem 1rem;
}

@media (min-width: 640px) {
    .header-content {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
}

@media (min-width: 1024px) {
    .header-content {
        padding-left: 2rem;
        padding-right: 2rem;
    }
}
</style>
