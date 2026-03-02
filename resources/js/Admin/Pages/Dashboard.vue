<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="dashboard-container">
            <!-- Header Section -->
            <div class="dashboard-header">
                <div class="header-left">
                    <h1 class="welcome-text">Welcome , {{ $page.props.auth.user.name }}</h1>
                    <p class="date-text">{{ currentDate }} <span class="separator"></span> Payment Gateway Operations</p>
                </div>
                <!-- <div class="header-actions">
                    <Link :href="route('clients.index')" class="btn-reports">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Reports
                    </Link>
                    <Link :href="route('pay.index')" class="btn-stock-check">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>New Payment</span>
                    </Link>
                </div> -->
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Card 1: Total Clients (Blue) -->
                <!-- <div class="stat-card card-blue">
                    <div class="stat-content">
                        <div class="stat-info">
                            <span class="stat-label">Total Clients</span>
                            <span class="stat-value">{{ stats?.total_clients || 0 }}</span>
                        </div>
                        <div class="stat-icon-box">
                            <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                </div> -->

                <!-- Card 1: Today's Transaction Amount (Gold) -->
                <div class="stat-card card-gold">
                    <div class="stat-header-split">
                        <span class="stat-label">Today's Total</span>
                        <div class="stat-icon-box-small">
                            <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-split-content">
                        <div class="stat-split-item">
                            <span class="stat-currency-label">LKR</span>
                            <span class="stat-value-split">{{ stats?.today_total_amount_lkr || 0 }}</span>
                        </div>
                        <div class="stat-split-divider"></div>
                        <div class="stat-split-item">
                            <span class="stat-currency-label">USD</span>
                            <span class="stat-value-split">{{ stats?.today_total_amount_usd || 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Today's Receipts (Slate) -->
                <div class="stat-card card-slate">
                    <div class="stat-content">
                        <div class="stat-info">
                            <span class="stat-label">Today's Receipts</span>
                            <span class="stat-value">{{ stats?.total_transactions || 0 }}</span>
                        </div>
                        <div class="stat-icon-box">
                            <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Completed Payments (Teal) -->
                <div class="stat-card card-teal">
                    <div class="stat-content">
                        <div class="stat-info">
                            <span class="stat-label">Completed Payments</span>
                            <span class="stat-value">{{ stats?.completed_transactions || 0 }}</span>
                        </div>
                        <div class="stat-icon-box">
                            <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4: Total Received (Dark) -->
                <div class="stat-card card-dark">
                    <div class="stat-header-split">
                        <span class="stat-label">Total Received</span>
                        <div class="stat-icon-box-small">
                            <svg class="stat-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-split-content">
                        <div class="stat-split-item">
                            <span class="stat-currency-label">LKR</span>
                            <span class="stat-value-split">{{ stats?.total_amount_lkr || 0 }}</span>
                        </div>
                        <div class="stat-split-divider"></div>
                        <div class="stat-split-item">
                            <span class="stat-currency-label">USD</span>
                            <span class="stat-value-split">{{ stats?.total_amount_usd || 0 }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Dashboard Grid: Chart and Recent Transactions -->
            <div class="dashboard-grid">
                <!-- Payment Status Overview -->
                <div class="status-overview-card">
                    <div class="chart-header">
                        <h3 class="chart-title">Payment Status Distribution</h3>
                        <p class="chart-subtitle">Transaction status overview</p>
                    </div>
                    <div class="status-stats-grid">
                        <div class="status-stat-item status-completed">
                            <div class="status-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="status-stat-content">
                                <span class="status-stat-value">{{ chartData.completed }}</span>
                                <span class="status-stat-label">Completed</span>
                            </div>
                        </div>

                        <div class="status-stat-item status-pending">
                            <div class="status-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="status-stat-content">
                                <span class="status-stat-value">{{ chartData.pending }}</span>
                                <span class="status-stat-label">Pending</span>
                            </div>
                        </div>

                        <div class="status-stat-item status-processing">
                            <div class="status-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                            <div class="status-stat-content">
                                <span class="status-stat-value">{{ chartData.processing }}</span>
                                <span class="status-stat-label">Processing</span>
                            </div>
                        </div>

                        <div class="status-stat-item status-failed">
                            <div class="status-stat-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="status-stat-content">
                                <span class="status-stat-value">{{ chartData.failed }}</span>
                                <span class="status-stat-label">Failed</span>
                            </div>
                        </div>
                    </div>

                    <div class="status-total-section">
                        <div class="status-total-card">
                            <div class="status-total-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <div class="status-total-content">
                                <span class="status-total-value">{{ chartData.total.toLocaleString() }}</span>
                                <span class="status-total-label">Total Transactions</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="transactions-card" v-if="recentTransactions && recentTransactions.length > 0">
                    <div class="chart-header">
                        <h3 class="chart-title">Recent Transactions</h3>
                        <p class="chart-subtitle">Latest payment activity</p>
                    </div>
                    <div class="transactions-list">
                        <div v-for="transaction in recentTransactions.slice(0, 6)" :key="transaction.id" class="transaction-item">
                            <div class="transaction-details">
                                <div class="transaction-name">{{ transaction.student_name || 'N/A' }}</div>
                                <div class="transaction-ref">{{ transaction.client_ref || transaction.reqid }}</div>
                            </div>
                            <div class="transaction-right">
                                <div class="transaction-amount">{{ transaction.currency }} {{ transaction.amount }}</div>
                                <span :class="['status-badge-sm', getStatusClass(transaction.status)]">
                                    {{ transaction.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <Link :href="route('transactions.index')" class="view-all-btn">
                        View All Transactions
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </Link>
                </div>
            </div>

            <!-- Quick Actions -->
            <!-- <div class="section-container">
                <h3 class="section-title">Quick Actions</h3>
                <div class="actions-grid">
                    <Link :href="route('clients.create')" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="action-label">Add Client</span>
                    </Link>

                    <Link :href="route('clients.index')" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/>
                            </svg>
                        </div>
                        <span class="action-label">Manage Clients</span>
                    </Link>

                    <Link :href="route('transactions.index')" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="action-label">View Transactions</span>
                    </Link>

                    <Link :href="route('pay.index')" class="action-card">
                        <div class="action-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <span class="action-label">Make Payment</span>
                    </Link>
                </div>
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    stats: Object,
    recentTransactions: Array,
});

const currentDate = computed(() => {
    return new Date().toLocaleDateString('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
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

// Compute chart data from props
const chartData = computed(() => {
    const completed = props.stats?.completed_transactions || 0;
    const pending = props.stats?.pending_transactions || 0;
    const processing = props.stats?.processing_transactions || 0;
    const failed = props.stats?.failed_transactions || 0;

    return {
        completed,
        pending,
        processing,
        failed,
        total: completed + pending + processing + failed
    };
});

// Payment Status Donut Chart
const statusChartOptions = computed(() => ({
    chart: {
        type: 'donut',
        height: 320,
        fontFamily: 'Inter, sans-serif',
    },
    colors: ['#10b981', '#fbbf24', '#3b82f6', '#ef4444'],
    labels: ['Completed', 'Pending', 'Processing', 'Failed'],
    dataLabels: {
        enabled: true,
        style: {
            fontSize: '12px',
            fontWeight: 600,
        }
    },
    legend: {
        position: 'bottom',
        fontSize: '13px',
        fontWeight: 500,
        labels: {
            colors: '#64748b',
        },
        markers: {
            width: 10,
            height: 10,
            radius: 3,
        }
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%',
                labels: {
                    show: true,
                    name: {
                        show: true,
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#1e293b',
                    },
                    value: {
                        show: true,
                        fontSize: '24px',
                        fontWeight: 700,
                        color: '#0b1120',
                    },
                    total: {
                        show: true,
                        label: 'Total',
                        fontSize: '14px',
                        fontWeight: 600,
                        color: '#64748b',
                        formatter: () => chartData.value.total.toLocaleString()
                    }
                }
            }
        }
    },
    stroke: {
        width: 2,
        colors: ['#fff']
    }
}));

const statusChartSeries = computed(() => [
    chartData.value.completed,
    chartData.value.pending,
    chartData.value.processing,
    chartData.value.failed
]);
</script>

<style scoped>
.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0;
}

/* Header */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 2rem;
}

.welcome-text {
    font-size: 1.5rem;
    font-weight: 600;
    color: #0b1120;
    margin-bottom: 0.375rem;
}

.date-text {
    color: #64748b;
    font-size: 0.8125rem;
    font-weight: 400;
}

.separator {
    margin: 0 0.5rem;
    color: #94a3b8;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-reports, .btn-stock-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    cursor: pointer;
    background: white;
    text-decoration: none;
}

.btn-reports {
    border: 1px solid #e5e7eb;
    color: #1e293b;
}

.btn-reports:hover {
    border-color: #0b1120;
}

.btn-stock-check {
    border: 2px solid #dcb01d;
    color: #dcb01d;
}

.btn-stock-check:hover {
    background: #dcb01d;
    color: #0b1120;
}

.btn-icon {
    width: 1rem;
    height: 1rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    border-radius: 12px;
    padding: 1.25rem;
    background: white;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.card-blue {
    border-left: 3px solid #0b1120;
}

.card-blue .stat-label {
    color: #64748b;
}

.card-blue .stat-icon {
    color: #0b1120;
}

.card-gold {
    border-left: 3px solid #dcb01d;
}

.card-gold .stat-label {
    color: #64748b;
}

.card-gold .stat-icon {
    color: #dcb01d;
}

.card-slate {
    border-left: 3px solid #dcb01d;
}

.card-slate .stat-label {
    color: #64748b;
}

.card-slate .stat-icon {
    color: #dcb01d;
}

.card-teal {
    border-left: 3px solid #059669;
}

.card-teal .stat-label {
    color: #64748b;
}

.card-teal .stat-icon {
    color: #059669;
}

.card-dark {
    border-left: 3px solid #0b1120;
}

.card-dark .stat-label {
    color: #64748b;
}

.card-dark .stat-icon {
    color: #dcb01d;
}

.stat-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    height: 100%;
}

.stat-info {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #0b1120;
    line-height: 1;
}

.stat-value-lg {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #0b1120;
}

.stat-icon-box {
    width: 40px;
    height: 40px;
    background: #f9fafb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon {
    width: 1.25rem;
    height: 1.25rem;
}

/* Split Card Styles */
.stat-header-split {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-icon-box-small {
    width: 32px;
    height: 32px;
    background: #f9fafb;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stat-icon-box-small .stat-icon {
    width: 1rem;
    height: 1rem;
}

.stat-split-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-split-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.375rem;
}

.stat-currency-label {
    font-size: 0.6875rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value-split {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0b1120;
    line-height: 1;
}

.stat-split-divider {
    width: 1px;
    height: 40px;
    background: #e5e7eb;
}

/* Dashboard Grid */
.dashboard-grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 1024px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Status Overview Card */
.status-overview-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.status-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 640px) {
    .status-stats-grid {
        grid-template-columns: 1fr;
    }
}

.status-stat-item {
    background: #fafafa;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid #f1f5f9;
    transition: all 0.2s ease;
}

.status-stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.status-stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.status-stat-icon svg {
    width: 20px;
    height: 20px;
}

.status-stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.status-stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #0b1120;
}

.status-stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* Status Colors */
.status-completed {
    border-left: 3px solid #10b981;
}

.status-completed .status-stat-icon {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.status-pending {
    border-left: 3px solid #fbbf24;
}

.status-pending .status-stat-icon {
    background: rgba(251, 191, 36, 0.1);
    color: #fbbf24;
}

.status-processing {
    border-left: 3px solid #3b82f6;
}

.status-processing .status-stat-icon {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.status-failed {
    border-left: 3px solid #ef4444;
}

.status-failed .status-stat-icon {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

/* Status Total Section */
.status-total-section {
    border-top: 1px solid #e5e7eb;
    padding-top: 1rem;
}

.status-total-card {
    background: linear-gradient(135deg, #0b1120 0%, #1e293b 100%);
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    position: relative;
    overflow: hidden;
}

.status-total-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: rgba(220, 176, 29, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.status-total-icon {
    width: 40px;
    height: 40px;
    background: rgba(220, 176, 29, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #dcb01d;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}

.status-total-icon svg {
    width: 20px;
    height: 20px;
}

.status-total-content {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    position: relative;
    z-index: 1;
}

.status-total-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.status-total-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.chart-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.chart-header {
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0b1120;
    margin: 0 0 0.25rem 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.chart-subtitle {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0;
}

/* Transactions Card */
.transactions-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
}

.transactions-list {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: #fafafa;
    border-radius: 6px;
    border: 1px solid #f1f5f9;
}

.transaction-item:hover {
    border-color: #e5e7eb;
}

.transaction-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.transaction-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.875rem;
}

.transaction-ref {
    font-size: 0.75rem;
    color: #64748b;
    font-family: ui-monospace, monospace;
}

.transaction-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.375rem;
}

.transaction-amount {
    font-weight: 700;
    color: #059669;
    font-size: 0.875rem;
}

.status-badge-sm {
    display: inline-block;
    padding: 0.125rem 0.5rem;
    border-radius: 4px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: capitalize;
}

.view-all-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.625rem;
    border: 1px solid #dcb01d;
    border-radius: 6px;
    color: #dcb01d;
    font-weight: 500;
    font-size: 0.8125rem;
    text-decoration: none;
    margin-top: auto;
}

.view-all-btn:hover {
    background: #dcb01d;
    color: #0b1120;
}

/* Quick Actions */
.section-container {
    margin-bottom: 2rem;
}

.section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #0b1120;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.action-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    text-decoration: none;
}

.action-card:hover {
    border-color: #dcb01d;
}

.action-icon {
    width: 40px;
    height: 40px;
    color: #0b1120;
}

.action-card:hover .action-icon {
    color: #dcb01d;
}

.action-label {
    font-weight: 500;
    color: #1e293b;
    font-size: 0.875rem;
}

.action-card:hover .action-label {
    color: #dcb01d;
}

/* Bottom Grid */
.bottom-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
}

.list-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    min-height: 200px;
}

.list-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}

.list-title {
    font-size: 1rem;
    font-weight: 600;
    color: #0b1120;
    margin: 0;
}

.list-subtitle {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.view-all {
    color: #dcb01d;
    font-size: 0.8125rem;
    font-weight: 500;
    text-decoration: none;
}

.view-all:hover {
    color: #b8941a;
}

.dot-indicator {
    width: 8px;
    height: 8px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.5rem;
}

.list-title-group {
    display: flex;
    flex-direction: column;
}

/* Table Styles */
.table-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(11, 17, 32, 0.05);
    margin-top: 1.25rem;
    border: 2px solid #e5e7eb;
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
    padding: 0.875rem 1.5rem;
    text-align: left;
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table tbody tr {
    border-top: 1px solid #e5e7eb;
    transition: all 0.2s;
}

.data-table tbody tr:hover {
    background: #fafafa;
}

.data-table td {
    padding: 1rem 1.5rem;
}

.cell-primary {
    font-weight: 500;
    color: #1e293b;
    font-size: 0.875rem;
}

.cell-secondary {
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.125rem;
}

.cell-mono {
    font-family: ui-monospace, monospace;
    font-size: 0.8125rem;
    color: #475569;
}

.cell-amount {
    font-weight: 700;
    color: #059669;
    font-size: 0.9375rem;
    font-variant-numeric: tabular-nums;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
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

/* Responsive */
@media (max-width: 1024px) {
    .stats-grid, .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
    }

    .stats-grid, .actions-grid {
        grid-template-columns: 1fr;
    }

    .welcome-text {
        font-size: 1.75rem;
    }
}

/* Chart Loading Styles */
.chart-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    padding: 2rem;
    color: #64748b;
}

.loading-spinner {
    width: 32px;
    height: 32px;
    border: 2px solid #e2e8f0;
    border-top: 2px solid #4f46e5;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.chart-loading p {
    font-size: 0.875rem;
    font-weight: 500;
    margin: 0;
}
</style>
