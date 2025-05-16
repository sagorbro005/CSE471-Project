<template>
  <div class="admin-layout">
    <AdminSidebar />
    <div class="admin-main-content">
      <slot></slot>
    </div>
  </div>
</template>

<script setup>
import AdminSidebar from '../pages/admin/AdminSidebar.vue';
</script>

<style scoped>
.admin-layout {
  display: flex;
  min-height: 100vh;
  background: #f8fafc;
}
.admin-main-content {
  flex: 1;
  padding: 32px 40px;
  background: #f8fafc;
}
</style>
