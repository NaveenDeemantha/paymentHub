<template>
    <Head title="Transactions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="page-title">
                All Transactions
            </h2>
        </template>

        <div class="content-container">
            <div class="content-wrapper">
                <!-- Filters -->
                <div class="card">
                    <div class="card-body">
                        <form @submit.prevent="applyFilters" class="filter-form">
                            <div class="filter-row">
                                <div class="filter-field">
                                    <label class="filter-label">Search</label>
                                    <input
                                        type="text"
                                        v-model="filterForm.search"
                                        class="filter-input"
                                        placeholder="Student name, email, phone, reference..."
                                    />
                                </div>
                                <div class="filter-field">
                                    <label class="filter-label">Status</label>
                                    <select v-model="filterForm.status" class="filter-select">
                                        <option value="">All Statuses</option>
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="failed">Failed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="filter-actions">
                                    <button type="submit" class="btn-primary">
                                        Apply Filters
                                    </button>
                                    <button type="button" @click="clearFilters" class="btn-secondary">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-container">
                            <table class="data-table">
                                <thead class="table-head">
                                    <tr>
                                        <th class="table-header">Student Info</th>
                                        <th class="table-header">Contact</th>
                                        <th class="table-header">Program</th>
                                        <th class="table-header">Amount</th>
                                        <th class="table-header">Status</th>
                                        <th class="table-header">Date</th>
                                        <th class="table-header">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-body">
                                    <tr v-if="transactions.data.length === 0">
                                        <td colspan="7" class="table-cell text-center py-8 text-gray-500">
                                            No transactions found
                                        </td>
                                    </tr>
                                    <tr v-for="transaction in transactions.data" :key="transaction.id" class="table-row">
                                        <td class="table-cell">
                                            <div class="cell-primary">{{ transaction.student_name || 'N/A' }}</div>
                                            <div class="cell-secondary">ID: {{ transaction.student_id || 'N/A' }}</div>
                                            <div class="cell-secondary">NIC: {{ transaction.nic_passport || 'N/A' }}</div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-primary">{{ transaction.customer_email || 'N/A' }}</div>
                                            <div class="cell-secondary">{{ transaction.customer_phone || 'N/A' }}</div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-primary">{{ transaction.program || 'N/A' }}</div>
                                            <div class="cell-secondary" v-if="transaction.reference">
                                                Ref: {{ transaction.reference }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <div class="cell-primary">
                                                {{ transaction.currency }} {{ transaction.amount }}
                                            </div>
                                            <div class="cell-secondary">{{ transaction.client_ref }}</div>
                                        </td>
                                        <td class="table-cell">
                                            <span
                                                :class="[
                                                    'status-badge',
                                                    transaction.status === 'completed' ? 'status-green' :
                                                    transaction.status === 'failed' ? 'status-red' :
                                                    transaction.status === 'processing' ? 'status-blue' :
                                                    'status-yellow'
                                                ]"
                                            >
                                                {{ transaction.status }}
                                            </span>
                                        </td>
                                        <td class="table-cell cell-secondary">
                                            <div>{{ formatDate(transaction.created_at) }}</div>
                                            <div v-if="transaction.completed_at" class="text-xs">
                                                Completed: {{ formatDate(transaction.completed_at) }}
                                            </div>
                                        </td>
                                        <td class="table-cell">
                                            <Link
                                                :href="route('transactions.show', transaction.id)"
                                                class="btn-view"
                                            >
                                                View
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="transactions.data.length > 0" class="pagination">
                            <div class="pagination-info">
                                Showing {{ transactions.from }} to {{ transactions.to }} of {{ transactions.total }} transactions
                            </div>
                            <div class="pagination-links">
                                <Link
                                    v-for="link in transactions.links"
                                    :key="link.label"
                                    :href="link.url"
                                    :class="[
                                        'pagination-link',
                                        { 'pagination-active': link.active },
                                        { 'pagination-disabled': !link.url }
                                    ]"
                                    v-html="link.label"
                                />
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
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    transactions: Object,
    filters: Object,
});

const filterForm = reactive({
    search: props.filters?.search || '',
    status: props.filters?.status || '',
});

function applyFilters() {
    router.get(route('transactions.index'), {
        search: filterForm.search,
        status: filterForm.status,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function clearFilters() {
    filterForm.search = '';
    filterForm.status = '';
    router.get(route('transactions.index'));
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleString();
}
</script>

<style scoped>
/* Page Layout */
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

.content-container {
    padding: 3rem 0;
}

.content-wrapper {
    max-width: 80rem;
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Card Styles */
.card {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    overflow: hidden;
}

@media (prefers-color-scheme: dark) {
    .card {
        background-color: #1f2937;
    }
}

.card-body {
    padding: 1.5rem;
}

/* Filter Form */
.filter-form {
    width: 100%;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 200px auto;
    gap: 1rem;
    align-items: end;
}

@media (max-width: 768px) {
    .filter-row {
        grid-template-columns: 1fr;
    }
}

.filter-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}

@media (prefers-color-scheme: dark) {
    .filter-label {
        color: #e5e7eb;
    }
}

.filter-input,
.filter-select {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    background-color: white;
    color: #1f2937;
}

@media (prefers-color-scheme: dark) {
    .filter-input,
    .filter-select {
        background-color: #374151;
        border-color: #4b5563;
        color: #e5e7eb;
    }
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: #f97316;
    ring: 2px;
    ring-color: rgba(249, 115, 22, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-primary,
.btn-secondary {
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-primary {
    background-color: #f97316;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #ea580c;
}

.btn-secondary {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}

@media (prefers-color-scheme: dark) {
    .btn-secondary {
        background-color: #374151;
        color: #e5e7eb;
        border-color: #4b5563;
    }
}

.btn-secondary:hover {
    background-color: #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    .btn-secondary:hover {
        background-color: #4b5563;
    }
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.table-head {
    background-color: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

@media (prefers-color-scheme: dark) {
    .table-head {
        background-color: #374151;
        border-color: #4b5563;
    }
}

.table-header {
    padding: 0.75rem 1rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
}

@media (prefers-color-scheme: dark) {
    .table-header {
        color: #9ca3af;
    }
}

.table-body {
    background-color: white;
}

@media (prefers-color-scheme: dark) {
    .table-body {
        background-color: #1f2937;
    }
}

.table-row {
    border-bottom: 1px solid #f3f4f6;
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
    padding: 1rem;
    font-size: 0.875rem;
}

.cell-primary {
    color: #1f2937;
    font-weight: 500;
}

@media (prefers-color-scheme: dark) {
    .cell-primary {
        color: #e5e7eb;
    }
}

.cell-secondary {
    color: #6b7280;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

@media (prefers-color-scheme: dark) {
    .cell-secondary {
        color: #9ca3af;
    }
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-green {
    background-color: #d1fae5;
    color: #065f46;
}

@media (prefers-color-scheme: dark) {
    .status-green {
        background-color: #064e3b;
        color: #6ee7b7;
    }
}

.status-yellow {
    background-color: #fef3c7;
    color: #92400e;
}

@media (prefers-color-scheme: dark) {
    .status-yellow {
        background-color: #78350f;
        color: #fde68a;
    }
}

.status-red {
    background-color: #fee2e2;
    color: #991b1b;
}

@media (prefers-color-scheme: dark) {
    .status-red {
        background-color: #7f1d1d;
        color: #fca5a5;
    }
}

.status-blue {
    background-color: #dbeafe;
    color: #1e40af;
}

@media (prefers-color-scheme: dark) {
    .status-blue {
        background-color: #1e3a8a;
        color: #93c5fd;
    }
}

/* Action Button */
.btn-view {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background-color: #f97316;
    color: white;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
}

.btn-view:hover {
    background-color: #ea580c;
}

/* Pagination */
.pagination {
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

@media (max-width: 768px) {
    .pagination {
        flex-direction: column;
        gap: 1rem;
    }
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
    gap: 0.25rem;
}

.pagination-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #374151;
    text-decoration: none;
    transition: all 0.15s;
}

@media (prefers-color-scheme: dark) {
    .pagination-link {
        border-color: #4b5563;
        color: #e5e7eb;
    }
}

.pagination-link:hover {
    background-color: #f3f4f6;
}

@media (prefers-color-scheme: dark) {
    .pagination-link:hover {
        background-color: #374151;
    }
}

.pagination-active {
    background-color: #f97316;
    color: white;
    border-color: #f97316;
}

.pagination-active:hover {
    background-color: #ea580c;
}

.pagination-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.text-center {
    text-align: center;
}

.py-8 {
    padding-top: 2rem;
    padding-bottom: 2rem;
}

.text-gray-500 {
    color: #6b7280;
}

.text-xs {
    font-size: 0.75rem;
}
</style>
