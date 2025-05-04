<template>
  <div class="admin-sidebar">
    <nav>
      <ul>
        <li><router-link to="/admin/dashboard" active-class="active-link">Dashboard</router-link></li>
        <li><router-link to="/admin/blogs" active-class="active-link">Blogs</router-link></li>
        <li><router-link to="/admin/users" active-class="active-link">Users</router-link></li>
        <li><router-link to="/admin/support" active-class="active-link">Support</router-link></li>
        <li><a href="#" @click.prevent="logout">Logout</a></li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
const router = useRouter();
function logout() {
  localStorage.removeItem('admin_logged_in');
  router.push('/admin/login');
}
</script>

<style scoped>
.admin-sidebar {
  width: 220px;
  background: linear-gradient(120deg, #2563eb 0%, #1e293b 100%);
  color: #fff;
  min-height: 100vh;
  padding-top: 40px;
  box-shadow: 2px 0 12px rgba(30,41,59,0.09);
}
nav ul {
  list-style: none;
  padding: 0;
}
nav ul li {
  margin: 24px 0;
  padding-left: 30px;
}
nav ul li a {
  color: #fff;
  text-decoration: none;
  font-size: 18px;
  opacity: 0.85;
  transition: color 0.2s, opacity 0.2s;
}
nav ul li a:hover, nav ul li a.active-link {
  font-weight: bold;
  text-decoration: underline;
  color: #fff;
  opacity: 1;
}
</style>
