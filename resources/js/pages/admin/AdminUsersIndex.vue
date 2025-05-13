<template>
  <div class="admin-users">
    <AdminSidebar />
    <div class="users-content">
      <!-- Header and search section -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manage Users</h2>
        
        <div class="flex gap-4">
          <form @submit.prevent="fetchUsers" class="flex gap-4">
            <input
              v-model="search"
              type="text"
              placeholder="Search by name, email or phone..."
              class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
            />
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
              Search
            </button>
            <button v-if="search" @click.prevent="clearSearch" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
              Clear
            </button>
          </form>
        </div>
      </div>

      <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ success }}</span>
      </div>
      
      <!-- Users Table -->
      <table class="users-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Details</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>
              <div class="font-medium text-blue-600">{{ user.name }}</div>
            </td>
            <td>
              <div class="text-blue-600">{{ user.email }}</div>
              <div class="text-sm text-blue-600">{{ user.phone }}</div>
            </td>
            <td>
              <div class="text-blue-600">Gender: {{ capitalize(user.gender) }}</div>
              <div class="text-sm text-blue-600">DOB: {{ formatDate(user.date_of_birth) }}</div>
              <div class="text-sm text-blue-600">{{ truncate(user.address, 30) }}</div>
            </td>
            <td>
              <a href="#" class="edit-link text-blue-600" @click.prevent="editUser(user)">Edit</a>
            </td>
          </tr>
          <tr v-if="users.length === 0">
            <td colspan="4" class="text-center text-gray-500">No users found</td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="mt-4 flex justify-center" v-if="pagination.last_page > 1">
        <button
          v-for="page in pagination.last_page"
          :key="page"
          @click="fetchUsers(page)"
          :class="['mx-1 px-3 py-1 rounded', page === pagination.current_page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700']"
        >
          {{ page }}
        </button>
      </div>

      <!-- Edit Modal -->
      <AdminUsersEdit
        v-if="editingUser"
        :user="editingUser"
        @close="editingUser = null"
        @updated="onUserUpdated"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminUsersEdit from './AdminUsersEdit.vue';
import AdminSidebar from './AdminSidebar.vue';

const page = usePage();
const users = ref(page.props.users?.data || []);
const pagination = ref({
  current_page: page.props.users?.current_page || 1,
  last_page: page.props.users?.last_page || 1,
});

// Initialize search from URL params if available
const search = ref(page.props.filters?.search || '');
const success = ref('');
const editingUser = ref(null);

// Watch for page props changes and update local state
watch(() => page.props.users, (newUsers) => {
  if (newUsers) {
    users.value = newUsers.data || [];
    pagination.value = {
      current_page: newUsers.current_page || 1,
      last_page: newUsers.last_page || 1,
    };
  }
}, { deep: true });

// Also update search when filters change
watch(() => page.props.filters, (newFilters) => {
  if (newFilters) {
    search.value = newFilters.search || '';
  }
}, { deep: true });

function fetchUsers(page = 1) {
  router.get('/admin/users', { 
    search: search.value, 
    page 
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['users', 'filters']
  });
}
function clearSearch() {
  search.value = '';
  fetchUsers();
}

function editUser(user) {
  editingUser.value = { ...user };
}

function onUserUpdated(msg) {
  success.value = msg;
  editingUser.value = null;
  fetchUsers();
  setTimeout(() => (success.value = ''), 3000);
}

function capitalize(val) {
  if (!val) return '';
  return val.charAt(0).toUpperCase() + val.slice(1);
}

function formatDate(date) {
  if (!date) return 'Not specified';
  const d = new Date(date);
  return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function truncate(str, len) {
  if (!str) return '';
  return str.length > len ? str.slice(0, len) + '...' : str;
}
</script>

<style scoped>
.admin-users {
  display: flex;
  min-height: 100vh;
}

.users-content {
  flex: 1;
  padding: 48px 40px 40px 40px;
  background: #f4f7fb;
}

.container {
  width: 100%;
  padding: 0 15px;
  margin: 0 auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(30,41,59,0.08);
}

th, td {
  border: 1px solid #e5e7eb;
  padding: 12px 14px;
  text-align: left;
}

th {
  background: #f1f5f9;
  color: #222e3c;
  font-weight: 700;
}
</style>
