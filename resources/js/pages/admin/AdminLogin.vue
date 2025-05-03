<template>
  <div class="admin-login-bg">
    <div class="admin-login-card">
      <div class="admin-login-header">
        <img src="/logo/Medimart.png" alt="MediMart Logo" class="admin-logo" />
        <h2>Admin Login</h2>
      </div>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input id="email" v-model="email" type="email" placeholder="admin@medimart.com" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input id="password" v-model="password" type="password" placeholder="Password" required />
        </div>
        <div v-if="error" class="error-msg">{{ error }}</div>
        <button class="login-btn" type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const email = ref('');
const password = ref('');
const error = ref('');

onMounted(() => {
  if (localStorage.getItem('admin_logged_in') === 'true') {
    router.visit('/admin/dashboard');
  }
});

function handleLogin() {
  if (email.value.trim().toLowerCase() === 'admin@medimart.com' && password.value === 'Medimart098') {
    localStorage.setItem('admin_logged_in', 'true');
    error.value = '';
    router.visit('/admin/dashboard');
  } else {
    error.value = 'Invalid email or password!';
  }
}
</script>

<style scoped>
.admin-login-bg {
  min-height: 100vh;
  background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
  display: flex;
  align-items: center;
  justify-content: center;
}
.admin-login-card {
  background: #fff;
  padding: 2.5rem 2rem 2rem 2rem;
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0,0,0,0.12);
  max-width: 370px;
  width: 100%;
}
.admin-login-header {
  text-align: center;
  margin-bottom: 1.5rem;
}
.admin-logo {
  width: 56px;
  margin-bottom: 0.5rem;
}
.form-group {
  margin-bottom: 1.1rem;
}
label {
  display: block;
  font-weight: 600;
  margin-bottom: 0.3rem;
  color: #2c3e50;
}
input[type="email"], input[type="password"] {
  width: 100%;
  padding: 0.65rem 0.9rem;
  border: 1px solid #dbeafe;
  border-radius: 6px;
  font-size: 1rem;
  outline: none;
  transition: border 0.2s;
}
input:focus {
  border-color: #2563eb;
}
.error-msg {
  color: #e53e3e;
  margin-bottom: 0.9rem;
  font-size: 0.98rem;
  text-align: center;
}
.login-btn {
  width: 100%;
  padding: 0.7rem;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}
.login-btn:hover {
  background: #1d4ed8;
}
</style>
