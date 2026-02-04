<template>
    <Head title="Client Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="header-container">
                <h2 class="page-title">
                    Client Management
                </h2>
                <Link 
                    :href="route('clients.create')" 
                    class="btn-primary"
                >
                    + Add Client
                </Link>
            </div>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-body">
                        <div v-if="clients.data && clients.data.length > 0" class="table-container">
                            <table class="data-table">
                                <thead class="table-head">
                                    <tr>
                                        <th class="table-header">Name</th>
                                        <th class="table-header">Domain</th>
                                        <th class="table-header">Status</th>
                                        <th class="table-header">Transactions</th>
                                        <th class="table-header">Created</th>
                                        <th class="table-header align-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr v-for="client in clients.data" :key="client.id" class="table-row">
                                        <td class="table-cell">
                                            <div class="cell-primary">
                                                {{ client.name }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-secondary">
                                                {{ client.domain }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <span 
                                                :class="client.is_active ? 'status-active' : 'status-inactive'"
                                            >
                                                {{ client.is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="table-cell cell-secondary">
                                            {{ client.transactions_count || 0 }}
                                        </td>
                                        <td class="table-cell cell-secondary">
                                            {{ new Date(client.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="table-cell align-right">
                                            <Link 
                                                :href="route('clients.show', client.id)" 
                                                class="action-link link-view"
                                            >
                                                View
                                            </Link>
                                            <Link 
                                                :href="route('clients.edit', client.id)" 
                                                class="action-link link-edit"
                                            >
                                                Edit
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div v-if="clients.links && clients.links.length > 3" class="pagination-container">
                                <div class="pagination-info">
                                    Showing {{ clients.from }} to {{ clients.to }} of {{ clients.total }} clients
                                </div>
                                <div class="pagination-links">
                                    <Link 
                                        v-for="link in clients.links" 
                                        :key="link.label"
                                        :href="link.url"
                                        :class="[
                                            'pagination-item',
                                            link.active ? 'pagination-active' : 'pagination-inactive',
                                            !link.url && 'pagination-disabled'
                                        ]"
                                        v-html="link.label"
                                    />
                                </div>
                            </div>
                        </div>

                        <div v-else class="empty-state">
                            <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <h3 class="empty-title">No clients</h3>
                            <p class="empty-description">Get started by creating a new client.</p>
                            <div class="empty-action">
                                <Link 
                                    :href="route('clients.create')" 
                                    class="btn-primary"
                                >
                                    + Add Your First Client
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    clients: Object,
});
</script>

<style scoped>
/* Header */
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 1.25rem;
    font-weight: 600;
    line-height: 1.25;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .page-title {
        color: #e5e7eb;
    }
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    background-color: #4f46e5;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.btn-primary:hover {
    background-color: #4338ca;
}

.btn-primary:focus {
    outline: none;
    box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px #4f46e5;
}

/* Content Layout */
.content-container {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
}

@media (min-width: 640px) {
    .content-wrapper {
        padding: 0 2rem;
    }
}

/* Card */
.card {
    background-color: #ffffff;
    overflow: hidden;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-radius: 0.5rem;
}

@media (prefers-color-scheme: dark) {
    .card {
        background-color: #1f2937;
    }
}

.card-body {
    padding: 1.5rem;
}

/* Data Table */
.table-container {
    overflow-x: auto;
}

.data-table {
    min-width: 100%;
    border-collapse: collapse;
}

.table-head {
    background-color: #f9fafb;
}

@media (prefers-color-scheme: dark) {
    .table-head {
        background-color: #111827;
    }
}

.table-header {
    padding: 0.75rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 500;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

@media (prefers-color-scheme: dark) {
    .table-header {
        color: #9ca3af;
    }
}

.align-right {
    text-align: right;
}

.table-body {
    background-color: #ffffff;
}

@media (prefers-color-scheme: dark) {
    .table-body {
        background-color: #1f2937;
    }
}

.table-row {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

@media (prefers-color-scheme: dark) {
    .table-row {
        border-color: #374151;
    }
}

.table-row:hover {
    background-color: #f9fafb;
}

@media (prefers-color-scheme: dark) {
    .table-row:hover {
        background-color: #374151;
    }
}

.table-cell {
    padding: 1rem 1.5rem;
    white-space: nowrap;
}

.cell-primary {
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .cell-primary {
        color: #f3f4f6;
    }
}

.cell-secondary {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .cell-secondary {
        color: #9ca3af;
    }
}

/* Status Badges */
.status-active,
.status-inactive {
    padding: 0.25rem 0.5rem;
    display: inline-flex;
    font-size: 0.75rem;
    line-height: 1;
    font-weight: 600;
    border-radius: 9999px;
}

.status-active {
    background-color: #dcfce7;
    color: #166534;
}

@media (prefers-color-scheme: dark) {
    .status-active {
        background-color: #14532d;
        color: #dcfce7;
    }
}

.status-inactive {
    background-color: #fee2e2;
    color: #991b1b;
}

@media (prefers-color-scheme: dark) {
    .status-inactive {
        background-color: #7f1d1d;
        color: #fee2e2;
    }
}

/* Action Links */
.action-link {
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.15s;
}

.link-view {
    color: #4f46e5;
}

.link-view:hover {
    color: #312e81;
}

@media (prefers-color-scheme: dark) {
    .link-view {
        color: #818cf8;
    }
    .link-view:hover {
        color: #a5b4fc;
    }
}

.link-edit {
    margin-left: 1rem;
    color: #ca8a04;
}

.link-edit:hover {
    color: #854d0e;
}

@media (prefers-color-scheme: dark) {
    .link-edit {
        color: #facc15;
    }
    .link-edit:hover {
        color: #fde047;
    }
}

/* Pagination */
.pagination-container {
    margin-top: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .pagination-info {
        color: #9ca3af;
    }
}

.pagination-links {
    display: flex;
    gap: 0.5rem;
}

.pagination-item {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    text-decoration: none;
    transition: background-color 0.15s;
}

.pagination-active {
    background-color: #4f46e5;
    color: #ffffff;
}

.pagination-inactive {
    background-color: #e5e7eb;
    color: #374151;
}

.pagination-inactive:hover {
    background-color: #d1d5db;
}

@media (prefers-color-scheme: dark) {
    .pagination-inactive {
        background-color: #374151;
        color: #d1d5db;
    }
    .pagination-inactive:hover {
        background-color: #4b5563;
    }
}

.pagination-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 0;
}

.empty-icon {
    margin: 0 auto;
    height: 3rem;
    width: 3rem;
    color: #9ca3af;
}

.empty-title {
    margin-top: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #111827;
}

@media (prefers-color-scheme: dark) {
    .empty-title {
        color: #f3f4f6;
    }
}

.empty-description {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .empty-description {
        color: #9ca3af;
    }
}

.empty-action {
    margin-top: 1.5rem;
}
</style>
