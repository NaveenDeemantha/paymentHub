<template>
    <Head title="Transactions" />

    <AuthenticatedLayout>
        <template #header>
            <div class="page-header">
                <div>
                    <h1 class="page-title">Transaction History</h1>
                    <p class="page-subtitle">View and manage all payment transactions</p>
                </div>
                <!-- <button @click="exportTransactions" class="btn-export">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export
                </button> -->
            </div>
        </template>



        <div class="content-wrapper">

            <div class="filter-bar">
                <div class="filter-group search-group">
                    <label class="filter-label">Search</label>
                    <div class="search-input-wrapper">
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input
                            v-model="search"
                            @input="debounceSearch"
                            type="text"
                            placeholder="Search by transaction ID, student name, or student ID..."
                            class="filter-input search-input"
                        >
                        <button v-if="search" @click="clearSearch" class="clear-btn">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Status</label>
                    <select v-model="status" @change="applyFilters" class="filter-select">
                        <option value="">All Statuses</option>
                        <option value="completed">Completed</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label class="filter-label">Date From</label>
                    <input
                        v-model="date_from"
                        @change="applyFilters"
                        type="date"
                        class="filter-input"
                    >
                </div>

                <div class="filter-group">
                    <label class="filter-label">Date To</label>
                    <input
                        v-model="date_to"
                        @change="applyFilters"
                        type="date"
                        class="filter-input"
                    >
                </div>

                <div class="filter-actions">
                    <button @click="resetFilters" class="btn-reset">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>

            <div v-if="transactions.data && transactions.data.length > 0" class="table-section">
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Client</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="transaction in transactions.data" :key="transaction.id">
                                <td>
                                    <span class="transaction-id">{{ transaction.transaction_number || transaction.id }}</span>
                                </td>
                                <td>
                                    <span class="student-name">{{ transaction.student_name || 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="student-id">{{ transaction.student_id || 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="client-badge">
                                        {{ transaction.client ? transaction.client.name : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="amount-text">{{ transaction.currency }} {{ formatAmount(transaction.amount) }}</span>
                                </td>
                                <td>
                                    <span :class="['status-badge', getStatusClass(transaction.status)]">
                                        {{ formatStatus(transaction.status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="date-text">{{ formatDate(transaction.created_at) }}</span>
                                </td>
                                <td class="text-right">
                                    <Link :href="route('transactions.show', transaction.id)" class="btn-action">
                                        View Details
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="transactions.links && transactions.links.length > 3" class="pagination">
                    <div class="pagination-info">
                        Showing {{ transactions.from }} to {{ transactions.to }} of {{ transactions.total }} transactions
                    </div>
                    <div class="pagination-links">
                        <Link
                            v-for="link in transactions.links"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="empty-title">No transactions yet</h3>
                <p class="empty-description">Transactions will appear here once your clients start processing payments.</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, onUnmounted } from 'vue';

const props = defineProps({
    transactions: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
});

// Initialize filters with refs
const search = ref(props.filters.search || '');
const status = ref(props.filters.status || '');
const date_from = ref(props.filters.date_from || '');
const date_to = ref(props.filters.date_to || '');

// Watch for prop changes to update refs
watch(() => props.filters, (newFilters) => {
    search.value = newFilters.search || '';
    status.value = newFilters.status || '';
    date_from.value = newFilters.date_from || '';
    date_to.value = newFilters.date_to || '';
}, { deep: true });

let searchTimeout = null;

const applyFilters = () => {
    const filterData = {};

    if (search.value) filterData.search = search.value;
    if (status.value) filterData.status = status.value;
    if (date_from.value) filterData.date_from = date_from.value;
    if (date_to.value) filterData.date_to = date_to.value;

    router.get(route('transactions.index'), filterData, {
        preserveState: false,
        preserveScroll: false,
    });
};

const debounceSearch = () => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
};

const clearSearch = () => {
    search.value = '';
    applyFilters();
};

const resetFilters = () => {
    search.value = '';
    status.value = '';
    date_from.value = '';
    date_to.value = '';
    applyFilters();
};

const exportTransactions = () => {
    const filterData = {};
    if (search.value) filterData.search = search.value;
    if (status.value) filterData.status = status.value;
    if (date_from.value) filterData.date_from = date_from.value;
    if (date_to.value) filterData.date_to = date_to.value;

    const params = new URLSearchParams(filterData);
    const url = route('transactions.index');
    window.open(`${url}?${params.toString()}&export=csv`, '_blank');
};

const formatAmount = (amount) => {
    return Number(amount).toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatStatus = (status) => {
    return status ? status.charAt(0).toUpperCase() + status.slice(1) : 'Unknown';
};

const getStatusClass = (status) => {
    const statusMap = {
        'completed': 'status-completed',
        'pending': 'status-pending',
        'failed': 'status-failed',
        'processing': 'status-processing'
    };
    return statusMap[status] || 'status-pending';
};

// Cleanup timeout on unmount
onUnmounted(() => {
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
});
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
    font-size: 1.5rem;
    font-weight: 600;
    color: #0b1120;
    margin: 0;
}

.page-subtitle {
    color: #64748b;
    margin-top: 0.25rem;
    font-size: 0.8125rem;
}

.btn-export {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: white;
    border: 2px solid #dcb01d;
    color: #dcb01d;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
}

.btn-export:hover {
    background: #dcb01d;
    color: #0b1120;
}

.btn-export .icon {
    width: 1rem;
    height: 1rem;
}

.content-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Filter Bar */
.filter-bar {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr auto;
    gap: 1rem;
    padding: 1.25rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.filter-bar:hover {
    border-color: #dcb01d;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.search-group {
    position: relative;
}

.filter-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #0b1120;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.search-icon {
    position: absolute;
    left: 0.875rem;
    width: 1rem;
    height: 1rem;
    color: #9ca3af;
    pointer-events: none;
}

.search-input {
    padding-left: 2.75rem !important;
    padding-right: 2.5rem !important;
}

.clear-btn {
    position: absolute;
    right: 0.625rem;
    padding: 0.25rem;
    background: #f3f4f6;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.clear-btn:hover {
    background: #e5e7eb;
}

.clear-btn svg {
    width: 1rem;
    height: 1rem;
    color: #6b7280;
}

.filter-select,
.filter-input {
    padding: 0.625rem 0.875rem;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 0.8125rem;
    color: #0b1120;
    background: white;
}

.filter-select:focus,
.filter-input:focus {
    outline: none;
    border-color: #dcb01d;
}

.filter-input::placeholder {
    color: #9ca3af;
}

.filter-actions {
    display: flex;
    align-items: flex-end;
}

.btn-reset {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: white;
    border: 1px solid #e5e7eb;
    color: #6b7280;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.8125rem;
    cursor: pointer;
    white-space: nowrap;
}

.btn-reset:hover {
    border-color: #0b1120;
    color: #0b1120;
}

.btn-reset .icon {
    width: 1rem;
    height: 1rem;
}

/* Table Section */
.table-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
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
    background: #fafafa;
    border-bottom: 2px solid #dcb01d;
}

.data-table th {
    padding: 0.75rem 1.25rem;
    text-align: left;
    font-size: 0.6875rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    white-space: nowrap;
}

.data-table th.text-right {
    text-align: right;
}

.data-table tbody tr {
    border-top: 1px solid #e5e7eb;
}

.data-table tbody tr:hover {
    background: #fafafa;
}

.data-table td {
    padding: 0.875rem 1.25rem;
}

.data-table td.text-right {
    text-align: right;
}

/* Transaction ID */
.transaction-id {
    font-weight: 600;
    color: #0b1120;
    font-size: 0.8125rem;
    font-family: ui-monospace, monospace;
}

/* Student Info */
.student-name {
    font-weight: 500;
    color: #1e293b;
    font-size: 0.8125rem;
}

.student-id {
    font-size: 0.75rem;
    color: #6b7280;
    font-family: ui-monospace, monospace;
}

.client-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #f3f4f6;
    color: #6b7280;
    font-size: 0.75rem;
    border-radius: 4px;
    font-weight: 500;
}

.amount-text {
    font-weight: 700;
    color: #059669;
    font-size: 0.875rem;
}

.date-text {
    font-size: 0.75rem;
    color: #6b7280;
    white-space: nowrap;
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 0.1875rem 0.5rem;
    border-radius: 4px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-completed {
    background: white;
    color: #065f46;
    border: 1.5px solid #10b981;
}

.status-pending {
    background: white;
    color: #92400e;
    border: 1.5px solid #fbbf24;
}

.status-processing {
    background: white;
    color: #1e3a8a;
    border: 1.5px solid #3b82f6;
}

.status-failed {
    background: white;
    color: #991b1b;
    border: 1.5px solid #ef4444;
}

/* Action Buttons */
.btn-action {
    padding: 0.375rem 0.875rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 6px;
    text-decoration: none;
    background: white;
    color: #dcb01d;
    border: 1px solid #dcb01d;
}

.btn-action:hover {
    background: #dcb01d;
    color: #0b1120;
}

/* Pagination */
.pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    border-top: 1px solid #e5e7eb;
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    font-size: 0.8125rem;
    color: #6b7280;
    font-weight: 400;
}

.pagination-links {
    display: flex;
    gap: 0.25rem;
}

.page-link {
    padding: 0.375rem 0.625rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #6b7280;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
}

.page-link:hover:not(.disabled):not(.active) {
    color: #dcb01d;
    border-color: #dcb01d;
}

.page-link.active {
    background: #dcb01d;
    color: #0b1120;
    border-color: #dcb01d;
    font-weight: 600;
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
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.empty-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: rgba(220, 176, 29, 0.1);
    margin-bottom: 1rem;
}

.empty-icon svg {
    width: 1.5rem;
    height: 1.5rem;
    color: #dcb01d;
}

.empty-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #0b1120;
    margin: 0 0 0.5rem;
}

.empty-description {
    color: #6b7280;
    margin: 0;
    font-size: 0.875rem;
}

@media (max-width: 1200px) {
    .filter-bar {
        grid-template-columns: 1fr 1fr;
    }

    .search-group {
        grid-column: span 2;
    }

    .filter-actions {
        grid-column: span 2;
        justify-content: flex-end;
    }
}

@media (max-width: 768px) {
    .filter-bar {
        grid-template-columns: 1fr;
    }

    .search-group {
        grid-column: span 1;
    }

    .filter-actions {
        grid-column: span 1;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
