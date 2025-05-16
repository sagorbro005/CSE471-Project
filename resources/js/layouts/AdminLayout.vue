<template>
  <div class="admin-layout">
    <!-- Mobile Header with Toggle Button -->
    <div class="mobile-header">
      <button @click="toggleSidebar" class="sidebar-toggle">
        <i class="fas fa-bars"></i>
      </button>
      <img src="/logo/Medimart.png" alt="Medimart" class="mobile-logo">
    </div>

    <!-- Sidebar that can be toggled on mobile -->
    <AdminSidebar :class="{'sidebar-open': sidebarOpen}" @close-sidebar="sidebarOpen = false" />

    <!-- Overlay to close sidebar when clicked outside -->
    <div v-if="sidebarOpen" class="sidebar-overlay" @click="sidebarOpen = false"></div>
    
    <div class="admin-main-content">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import AdminSidebar from '../pages/admin/AdminSidebar.vue';
import { ref } from 'vue';

const sidebarOpen = ref(false);

function toggleSidebar() {
  sidebarOpen.value = !sidebarOpen.value;
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background: #f8fafc;
  position: relative;
}

.mobile-header {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 60px;
  background: #fff;
  border-bottom: 1px solid #eee;
  padding: 0 15px;
  align-items: center;
  justify-content: space-between;
  z-index: 20;
}

.sidebar-toggle {
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

.mobile-logo {
  height: 40px;
}

.sidebar-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  z-index: 20;
}

.admin-main-content {
  flex: 1;
  padding: 32px 40px;
  background: #f8fafc;
}

/* Responsive styles */
@media (max-width: 768px) {
  .admin-layout {
    flex-direction: column;
  }
  
  .mobile-header {
    display: flex;
  }
  
  .sidebar-overlay {
    display: block;
  }
  
  .admin-main-content {
    padding: 20px 15px;
    margin-top: 60px; /* Space for fixed header */
  }
}
</style>
