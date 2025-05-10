<template>
  <div class="admin-support">
    <AdminSidebar />
    <div class="support-content">
      <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
      <h2 class="page-title">Customer Support Issues</h2>
      <div class="mb-8 flex items-center w-full max-w-5xl mx-auto">
        <input
          v-model="search"
          @keyup.enter="applySearch"
          type="text"
          class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-base bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 shadow-none"
          placeholder="Search by name, email or phone..."
        />
        <button @click="applySearch" class="ml-3 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-base font-medium transition">Search</button>
        <button v-if="search" @click="clearSearch" class="ml-2 text-gray-600 underline text-base">Clear</button>
      </div>
      <table>
        <thead>
          <tr>
            <th>Customer</th>
            <th>Contact</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date & Time</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="issue in issues" :key="issue.id">
            <td>{{ issue.name }}</td>
            <td>{{ issue.email }}<br><small>{{ issue.phone }}</small></td>
            <td>{{ issue.subject }}</td>
            <td>{{ issue.message }}</td>
            <td>{{ formatDate(issue.created_at) }}</td>
            <td>
              <span :class="{'solved': issue.status === 'Solved', 'not-solved': issue.status !== 'Solved'}">
                {{ issue.status ?? 'Not Solved' }}
              </span>
            </td>
            <td>
              <button class="status-btn" v-if="issue.status !== 'Solved'" @click="updateStatus(issue.id, 'Solved')">Mark as Solved</button>
              <button class="status-btn revert" v-else @click="updateStatus(issue.id, 'Not Solved')">Mark as Not Solved</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import AdminSidebar from './AdminSidebar.vue';

const page = usePage();
const issues = ref(page.props.issues.data ?? page.props.issues ?? []);
const search = ref(page.props.filters?.search ?? '');

function applySearch() {
  router.get(route('admin.support'), { search: search.value }, { preserveState: true, preserveScroll: true });
}
function clearSearch() {
  search.value = '';
  applySearch();
}

watch(
  () => page.props.issues,
  (val) => {
    issues.value = val.data ?? val;
  }
);

const successMessage = ref('');

function formatDate(dateString) {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  // Format for Bangladesh Standard Time (UTC+6)
  const options = {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
    timeZone: 'Asia/Dhaka'
  };
  return date.toLocaleString('en-US', options);
}

// Watch for flash messages from the server
watch(
  () => page.props.flash?.success,
  (message) => {
    if (message) {
      successMessage.value = message;
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
    }
  },
  { immediate: true }
);

function updateStatus(id, newStatus) {
  // Send API request to update status in DB
  router.post(`/admin/support/${id}/status`, {
    status: newStatus
  }, {
    preserveScroll: true,
    preserveState: false
  });
}
</script>

<style scoped>
.admin-support {
  display: flex;
}
.support-content {
  flex: 1;
  padding: 40px;
  background: #f9f9f9;
}
.page-title {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #2d3748;
}
.success-message {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: #10b981;
  color: white;
  padding: 12px 20px;
  border-radius: 6px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  animation: fadeIn 0.3s, fadeOut 0.3s 2.7s;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 24px;
}
th, td {
  border: 1px solid #eee;
  padding: 12px 15px;
  text-align: left;
}
th {
  background-color: #f1f5f9;
  font-weight: 600;
}
.status-btn {
  background-color: #3b82f6;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 0.9rem;
  transition: background-color 0.2s;
}
.status-btn:hover {
  background-color: #2563eb;
}
.status-btn.revert {
  background-color: #6b7280;
}
.status-btn.revert:hover {
  background-color: #4b5563;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeOut {
  from { opacity: 1; transform: translateY(0); }
  to { opacity: 0; transform: translateY(-20px); }
}
.solved {
  background: #e6ffe6;
  color: #22bb33;
  padding: 3px 8px;
  border-radius: 5px;
}
.not-solved {
  background: #fffbe6;
  color: #bb8800;
  padding: 3px 8px;
  border-radius: 5px;
}
</style>
