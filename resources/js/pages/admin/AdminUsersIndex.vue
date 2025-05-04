<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Search Box -->
    <div class="mb-6">
      <form @submit.prevent="fetchUsers" class="flex gap-4">
        <div class="flex-1">
          <input
            v-model="search"
            type="text"
            placeholder="Search by name, email or phone..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
          />
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Search
        </button>
        <button v-if="search" @click.prevent="clearSearch" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
          Clear
        </button>
      </form>
    </div>

    <div v-if="success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
      <span class="block sm:inline">{{ success }}</span>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in users" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900">{{ user.email }}</div>
              <div class="text-sm text-gray-500">{{ user.phone }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900">Gender: {{ capitalize(user.gender) }}</div>
              <div class="text-sm text-gray-500">DOB: {{ formatDate(user.date_of_birth) }}</div>
              <div class="text-sm text-gray-500">{{ truncate(user.address, 30) }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="editUser(user)" class="text-indigo-600 hover:text-indigo-900">Edit</button>
            </td>
          </tr>
          <tr v-if="users.length === 0">
            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found</td>
          </tr>
        </tbody>
      </table>
    </div>

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
</template>

<script>
import axios from 'axios';
import AdminUsersEdit from './AdminUsersEdit.vue';

export default {
  name: 'AdminUsersIndex',
  components: { AdminUsersEdit },
  data() {
    return {
      users: [],
      pagination: { current_page: 1, last_page: 1 },
      search: '',
      success: '',
      editingUser: null,
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers(page = 1) {
      axios
        .get('/admin/users', { params: { search: this.search, page } })
        .then((res) => {
          this.users = res.data.data;
          this.pagination = {
            current_page: res.data.current_page,
            last_page: res.data.last_page,
          };
        });
    },
    clearSearch() {
      this.search = '';
      this.fetchUsers();
    },
    editUser(user) {
      this.editingUser = { ...user };
    },
    onUserUpdated(msg) {
      this.success = msg;
      this.editingUser = null;
      this.fetchUsers();
      setTimeout(() => (this.success = ''), 3000);
    },
    capitalize(val) {
      if (!val) return '';
      return val.charAt(0).toUpperCase() + val.slice(1);
    },
    formatDate(date) {
      if (!date) return 'Not specified';
      const d = new Date(date);
      return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    },
    truncate(str, len) {
      if (!str) return '';
      return str.length > len ? str.slice(0, len) + '...' : str;
    },
  },
};
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
