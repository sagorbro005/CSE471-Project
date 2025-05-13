<template>
  <div class="admin-dashboard">
    <AdminSidebar />
    <div class="dashboard-content">
      <div class="dashboard-header">
        <h2>Dashboard Overview</h2>
        <div class="welcome">Welcome, Admin</div>
      </div>
      <div class="stats-row">
        <div class="stat-card blue">
          <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
          <div class="stat-label">Total Orders</div>
          <div class="stat-value">{{ stats.totalOrders }}</div>
        </div>
        <div class="stat-card green">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div class="stat-label">Total Users</div>
          <div class="stat-value">{{ stats.totalUsers }}</div>
        </div>
        <div class="stat-card yellow">
          <div class="stat-icon"><i class="fas fa-prescription-bottle-alt"></i></div>
          <div class="stat-label">Pending Prescriptions</div>
          <div class="stat-value">{{ stats.pendingPrescriptions }}</div>
        </div>
        <div class="stat-card purple">
          <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
          <div class="stat-label">Total Revenue</div>
          <div class="stat-value">৳{{ stats.totalRevenue }}</div>
        </div>
      </div>
      <div class="recent-orders">
        <div class="recent-header">Recent Orders</div>
        <table class="orders-table">
          <thead>
            <tr>
              <th>ORDER ID</th>
              <th>CUSTOMER</th>
              <th>STATUS</th>
              <th>TOTAL</th>
              <th>DATE</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in stats.recentOrders" :key="order.id">
              <td class="text-blue-600">#{{ order.id }}</td>
              <td>
                <div class="text-blue-600">{{ order.user.name }}</div>
                <div class="email text-blue-600">{{ order.user.email }}</div>
              </td>
              <td>
                <span :class="['status-badge', order.status.toLowerCase()]">
                  {{ order.status }}
                </span>
              </td>
              <td class="text-blue-600">৳{{ order.total }}</td>
              <td class="text-blue-600">{{ order.date }}</td>
            </tr>
            <tr v-if="stats.recentOrders.length === 0">
              <td colspan="5" class="text-center text-gray-500 py-4">No recent orders found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminSidebar from './AdminSidebar.vue';

const stats = ref({
  totalOrders: 0,
  totalUsers: 0,
  pendingPrescriptions: 0,
  totalRevenue: 0,
  recentOrders: []
});

onMounted(async () => {
  try {
    const res = await axios.get('/admin/dashboard-stats');
    stats.value = res.data;
  } catch (e) {
    // fallback or error display
  }
});
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
.admin-dashboard {
  display: flex;
  min-height: 100vh;
}
.dashboard-content {
  flex: 1;
  padding: 48px 40px 40px 40px;
  background: #f4f7fb;
}
.dashboard-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 28px;
}
.dashboard-header h2 {
  font-size: 2.1rem;
  font-weight: 700;
  color: #222e3c;
}
.welcome {
  color: #64748b;
  font-size: 1.1rem;
}
.stats-row {
  display: flex;
  gap: 24px;
  margin-bottom: 32px;
}
.stat-card {
  flex: 1;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(30,41,59,0.08);
  padding: 28px 0 22px 0;
  text-align: center;
  min-width: 180px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.stat-icon {
  font-size: 2.1em;
  margin-bottom: 10px;
  margin-top: 2px;
}
.stat-label {
  font-size: 1.07em;
  color: #64748b;
  margin-bottom: 4px;
}
.stat-value {
  font-size: 1.3em;
  font-weight: 700;
  color: #222e3c;
}
.stat-card.blue {
  border-top: 4px solid #2563eb;
}
.stat-card.green {
  border-top: 4px solid #22c55e;
}
.stat-card.yellow {
  border-top: 4px solid #fbbf24;
}
.stat-card.purple {
  border-top: 4px solid #a78bfa;
}
.recent-orders {
  margin-top: 32px;
}
.recent-header {
  font-size: 1.13em;
  font-weight: 600;
  margin-bottom: 10px;
  color: #222e3c;
}
.orders-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(30,41,59,0.08);
  margin-top: 24px;
  overflow: hidden;
}
.orders-table th, .orders-table td {
  padding: 16px 12px;
  text-align: left;
}
.orders-table th {
  background: #f4f7fb;
  color: #222e3c;
  font-weight: 700;
  font-size: 1rem;
}
.orders-table tbody tr:not(:last-child) {
  border-bottom: 1px solid #e5e7eb;
}
.email {
  color: #64748b;
  font-size: 0.95em;
}
.status-badge {
  display: inline-block;
  padding: 4px 16px;
  border-radius: 16px;
  font-size: 0.97em;
  font-weight: 600;
  color: #fff;
  text-transform: capitalize;
}
.status-badge.completed, .status-badge.delivered {
  background: #22c55e;
}
.status-badge.cancelled {
  background: #64748b;
}
.status-badge.processing {
  background: #2563eb;
}
.status-badge.pending {
  background: #fbbf24;
  color: #222e3c;
}
</style>
