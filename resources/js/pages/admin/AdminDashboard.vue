<template>
  <div class="admin-dashboard">
    <AdminSidebar />
    <div class="dashboard-content">
      <div class="dashboard-header">
        <h1>Welcome, Admin!</h1>
        <p class="subtitle">Manage blogs and support tickets from the menu.</p>
      </div>
      <div class="dashboard-cards">
        <div class="dash-card">
          <div class="dash-card-title">Blogs</div>
          <div class="dash-card-value">Manage, Edit, Delete</div>
        </div>
        <div class="dash-card">
          <div class="dash-card-title">Support</div>
          <div class="dash-card-value">View, Resolve Issues</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import AdminSidebar from './AdminSidebar.vue';
</script>

<style scoped>
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
  margin-bottom: 28px;
}
.dashboard-header h1 {
  font-size: 2.1rem;
  font-weight: 700;
  color: #222e3c;
}
.subtitle {
  color: #64748b;
  font-size: 1.1rem;
  margin-top: 0.5rem;
}
.dashboard-cards {
  display: flex;
  gap: 32px;
}
.dash-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(30,41,59,0.08);
  padding: 36px 32px;
  min-width: 210px;
  text-align: center;
}
.dash-card-title {
  font-size: 1.2rem;
  font-weight: 600;
  color: #2563eb;
  margin-bottom: 0.7rem;
}
.dash-card-value {
  font-size: 1.1rem;
  color: #222e3c;
}
</style>
