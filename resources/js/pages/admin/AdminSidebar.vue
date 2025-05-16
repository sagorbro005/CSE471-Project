<template>
  <div class="admin-sidebar">
    <!-- Logo and Header Section -->
    <div class="sidebar-header">
      <img src="/logo/Medimart.png" alt="Medimart Logo" class="sidebar-logo">
      <h3 class="sidebar-title">Admin Panel</h3>
      <button @click="$emit('close-sidebar')" class="close-sidebar">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <nav>
      <ul>
        <li>
          <Link href="/admin/dashboard" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/dashboard') }">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/orders" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/orders') }">
            <i class="fas fa-shopping-cart"></i>
            <span>Orders</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/users" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/users') }">
            <i class="fas fa-users"></i>
            <span>Users</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/prescriptions" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/prescriptions') }">
            <i class="fas fa-file-prescription"></i>
            <span>Prescriptions</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/products" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/products') }">
            <i class="fas fa-box"></i>
            <span>Products</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/blogs" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/blogs') }">
            <i class="fas fa-blog"></i>
            <span>Blogs</span>
          </Link>
        </li>
        <li>
          <Link href="/admin/support" class="sidebar-link" :class="{ 'active-link': $page.url.startsWith('/admin/support') }">
            <i class="fas fa-headset"></i>
            <span>Support</span>
          </Link>
        </li>
        <li class="separator"></li>
        <li>
          <a href="#" @click.prevent="logout" class="sidebar-link">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { router, Link } from '@inertiajs/vue3';
function logout() {
  localStorage.removeItem('admin_logged_in');
  router.visit('/admin/login');
}

defineEmits(['close-sidebar']);
</script>

<style scoped>
.admin-sidebar {
  width: 250px;
  background: #1e1e1e;
  color: #fff;
  min-height: 100vh;
  box-shadow: 2px 0 12px rgba(0,0,0,0.15);
  display: flex;
  flex-direction: column;
  z-index: 30;
  transition: transform 0.3s ease;
}

/* Close button for mobile */
.close-sidebar {
  display: none;
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  position: absolute;
  top: 20px;
  right: 20px;
  cursor: pointer;
}

.sidebar-header {
  text-align: center;
  padding: 20px 0;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  margin-bottom: 20px;
}

.sidebar-logo {
  height: 150px;
  width: auto;
  margin: 0 auto 10px;
  display: block;
}

.sidebar-title {
  font-size: 18px; /* Increased from 16px */
  font-weight: 600; /* Increased from 500 */
  color: #e5e5e5;
  margin: 0;
  padding: 0;
  letter-spacing: 0.5px;
}

nav {
  flex: 1;
}

nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

nav ul li {
  margin: 5px 0;
}

.sidebar-link {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: #fff;
  text-decoration: none;
  font-size: 16px;
  opacity: 0.8;
  transition: all 0.2s ease;
}

.sidebar-link i {
  margin-right: 12px;
  width: 20px;
  text-align: center;
  font-size: 15px;
}

.sidebar-link:hover, .sidebar-link.active-link {
  background: rgba(255,255,255,0.1);
  opacity: 1;
  text-decoration: none;
  border-left: 3px solid #4ade80; /* Green accent as shown in image */
}

.separator {
  height: 1px;
  background: rgba(255,255,255,0.1);
  margin: 15px 20px;
}

/* Responsive styles */
@media (max-width: 768px) {
  .admin-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    transform: translateX(-100%);
  }
  
  .admin-sidebar.sidebar-open {
    transform: translateX(0);
  }
  
  .close-sidebar {
    display: block;
  }
}
</style>
