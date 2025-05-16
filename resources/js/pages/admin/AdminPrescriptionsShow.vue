<template>
  <AdminLayout>
  <div class="container mx-auto px-4 py-6">
    <a href="/admin/prescriptions" class="text-blue-600 hover:underline mb-4 inline-block">&lt; Back to Prescriptions</a>
    <div v-if="prescription" class="bg-white rounded-lg shadow p-8">
      <h2 class="text-2xl font-bold mb-4">Customer Information</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
          <div><strong>Name</strong>: {{ prescription.user.name }}</div>
          <div><strong>Phone</strong>: {{ prescription.user.phone }}</div>
          <div><strong>Date of Birth</strong>: {{ prescription.user.date_of_birth }}</div>
          <div><strong>Address</strong>: {{ prescription.user.address }}</div>
        </div>
        <div>
          <div><strong>Email</strong>: {{ prescription.user.email }}</div>
          <div><strong>Gender</strong>: {{ prescription.user.gender }}</div>
          <div><strong>Age</strong>: {{ prescription.user.age ?? 'N/A' }}</div>
        </div>
      </div>
      <div class="mb-6">
        <h3 class="font-semibold mb-2">Order Status</h3>
        <select v-model="selectedStatus" class="px-4 py-2 border rounded mr-2">
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
        <button @click="updateStatus(selectedStatus)" class="px-4 py-2 bg-blue-600 text-white rounded">Update Status</button>
      </div>
      <div class="mb-6">
        <h3 class="font-semibold mb-2">Prescription Images</h3>
        <div class="flex flex-wrap gap-4">
          <a v-for="(img, idx) in prescription.images" :key="idx" :href="getImageUrl(img.path)" target="_blank">
            <img :src="getImageUrl(img.path)" class="w-32 h-32 object-cover rounded border" alt="Prescription Image" />
          </a>
        </div>
      </div>
      <div v-if="prescription.notes && prescription.notes.length > 0" class="mb-4">
        <strong>Notes:</strong>
        <ul class="list-disc ml-6">
          <li v-for="(note, idx) in prescription.notes" :key="idx">{{ note }}</li>
        </ul>
      </div>
      <div class="text-gray-500 text-sm">Order placed at: {{ prescription.date }} {{ prescription.time }}</div>
    </div>
    <div v-else class="text-center text-gray-500">Loading...</div>
  </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/layouts/AdminLayout.vue';
import { imageHelper } from '@/mixins/ImageHelper.js';

export default {
  name: 'AdminPrescriptionsShow',
  components: {
    AdminLayout
  },
  mixins: [imageHelper],
  props: {
    prescription: Object
  },
  data() {
    return {
      selectedStatus: this.prescription.status
    }
  },
  watch: {
    'prescription.status'(val) {
      this.selectedStatus = val
    }
  },
  methods: {
    updateStatus(newStatus) {
      this.$inertia.patch(`/admin/prescriptions/${this.prescription.id}/status`, {
        status: newStatus
      })
    }
  }
}
</script>

<style scoped>
/* Add custom styles if needed */
</style>
