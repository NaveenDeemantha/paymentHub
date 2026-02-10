<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Client Management</h1>
                    <p class="page-subtitle">Manage websites using your payment gateway</p>
                </div>
                <Link :href="route('clients.create')" class="btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Client
                </Link>
            </div>
        </template>

        <div class="content-wrapper">
            <div v-if="clients.data && clients.data.length > 0" class="table-section">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Domain</th>
                                <th>Status</th>
                                <th>Transactions</th>
                                <th>Created</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="client in clients.data" :key="client.id">
                                <td>
                                    <div class="client-info">
                                        <div class="client-avatar">
                                            {{ client.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <div class="client-name">{{ client.name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="domain-badge">{{ client.domain }}</span>
                                </td>
                                <td>
                                    <span :class="['status-badge', client.is_active ? 'status-active' : 'status-inactive']">
                                        {{ client.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="transaction-count">{{ client.transactions_count || 0 }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ formatDate(client.created_at) }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="action-buttons">
                                        <Link :href="route('clients.show', client.id)" class="btn-action btn-view">
                                            View
                                        </Link>
                                        <Link :href="route('clients.edit', client.id)" class="btn-action btn-edit">
                                            Edit
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="clients.links && clients.links.length > 3" class="pagination">
                    <div class="pagination-info">
                        Showing {{ clients.from }} to {{ clients.to }} of {{ clients.total }} clients
                    </div>
                    <div class="pagination-links">
                        <Link
                            v-for="link in clients.links"
                            :key="link.label"
                            :href="link.url"
                            :class="[
                                'page-link',
                                link.active && 'active',
                                !link.url && 'disabled'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <div class="empty-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="empty-title">No clients yet</h3>
                <p class="empty-description">Get started by creating your first client website.</p>
                <Link :href="route('clients.create')" class="btn-primary">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Your First Client
                </Link>
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

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
};
</script>

<style scoped>
.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.page-subtitle {
    color: #6b7280;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: #2c3e50;
    color: white;
    font-weight: 500;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 0.875rem;
}

.btn-primary:hover {
    background: #34495e;
}

.btn-icon {
    width: 1.125rem;
    height: 1.125rem;
}

.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Table Section */
.table-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f9fafb;
}

.data-table th {
    padding: 0.875rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table th.text-right {
    text-align: right;
}

.data-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: background-color 0.15s;
}

.data-table tbody tr:hover {
    background: #f9fafb;
}

.data-table td {
    padding: 1rem 1.5rem;
}

.data-table td.text-right {
    text-align: right;
}

/* Client Info */
.client-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.client-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 8px;
    background: #2c3e50;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9375rem;
    flex-shrink: 0;
}

.client-name {
    font-weight: 500;
    color: #1f2937;
    font-size: 0.875rem;
}

.domain-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 0.8125rem;
    border-radius: 4px;
    font-family: monospace;
}

.transaction-count {
    font-weight: 600;
    color: #1f2937;
    font-size: 0.875rem;
}

.date-text {
    font-size: 0.8125rem;
    color: #6b7280;
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-active {
    background: #d1fae5;
    color: #065f46;
}

.status-inactive {
    background: #e5e7eb;
    color: #4b5563;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.5rem 1rem;
    font-size: 0.8125rem;
    font-weight: 500;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-view {
    background: #f3f4f6;
    color: #6b7280;
}

.btn-view:hover {
    background: #e5e7eb;
    color: #374151;
}

.btn-edit {
    background: #dbeafe;
    color: #2563eb;
}

.btn-edit:hover {
    background: #bfdbfe;
    color: #1e40af;
}

/* Pagination */
.pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6b7280;
}

.pagination-links {
    display: flex;
    gap: 0.25rem;
}

.page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #6b7280;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.2s;
}

.page-link:hover:not(.disabled):not(.active) {
    background: #f9fafb;
    color: #374151;
}

.page-link.active {
    background: #2c3e50;
    color: white;
    border-color: #2c3e50;
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 12px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 4rem;
    height: 4rem;
    border-radius: 50%;
    background: #f3f4f6;
    margin-bottom: 1.5rem;
}

.empty-icon svg {
    width: 2rem;
    height: 2rem;
    color: #9ca3af;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.5rem;
}

.empty-description {
    color: #6b7280;
    margin: 0 0 2rem;
    font-size: 0.9375rem;
}

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-action {
        text-align: center;
    }
}
</style>
