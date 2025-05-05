<template>
  <div class="admin-support">
    <AdminSidebar />
    <div class="support-content">
      <h2>Customer Support Issues</h2>
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
            <td>
              <span :class="{'solved': issue.status === 'Solved', 'not-solved': issue.status !== 'Solved'}">
                {{ issue.status ?? 'Not Solved' }}
              </span>
            </td>
            <td>
              <a href="#" v-if="issue.status !== 'Solved'" @click.prevent="markSolved(issue.id)">Mark as Solved</a>
              <span v-else>—</span>
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

function markSolved(id) {
  const issue = issues.value.find(i => i.id === id);
  if (issue) {
    issue.status = 'Solved';
    // Optionally: send an API request to update status in DB
    // router.put(`/admin/support/${id}/solve`)
  }
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
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 24px;
}
th, td {
  border: 1px solid #eee;
  padding: 10px;
  text-align: left;
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
