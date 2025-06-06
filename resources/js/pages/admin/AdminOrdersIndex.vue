<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-6">
    <!-- Search and Filter -->
    <div class="mb-6 flex gap-4">
      <input
        v-model="search"
        @keyup.enter="fetchOrders"
        type="text"
        placeholder="Search by Customer Name, Email or Phone..."
        class="flex-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
      />
      <select v-model="status" @change="fetchOrders" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
        <option value="">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Processing">Processing</option>
        <option value="Delivered">Delivered</option>
        <option value="Cancelled">Cancelled</option>
      </select>
      <button @click="fetchOrders" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
      <button v-if="search || status" @click="clearFilters" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Clear</button>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
      <span>{{ successMessage }}</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="order in orders.data" :key="order.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                <a :href="`/admin/orders/${order.id}`" class="text-blue-600 hover:text-blue-800">
                  {{ order.user.name }}
                </a>
              </div>
              <div class="text-sm text-gray-500">Order #{{ order.id }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-900">{{ order.user.email }}</div>
              <div class="text-sm text-gray-500">{{ order.user.phone }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-wrap gap-2">
                <div v-for="item in order.items" :key="item.id" class="block w-24 h-24 relative">
                  <img :src="item.image ? `/storage/${item.image}` : '/images/placeholder.png'" alt="Product" class="w-full h-full object-cover rounded" />
                  <div class="absolute bottom-1 right-1 bg-white bg-opacity-80 px-2 py-1 rounded text-xs text-gray-800">
                    x{{ item.quantity }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ order.date }}</div>
              <div class="text-sm text-gray-500">{{ order.time }}</div>
            </td>
            <td class="px-6 py-4">
              <span :class="statusBadgeClass(order.status)">
                {{ order.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <select v-model="order.status" @change="updateStatus(order)" class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="Pending">Pending</option>
                <option value="Processing">Processing</option>
                <option value="Delivered">Delivered</option>
                <option value="Cancelled">Cancelled</option>
              </select>
            </td>
          </tr>
          <tr v-if="orders.data.length === 0">
            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No orders found matching your search criteria</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <div class="mt-4 flex justify-center">
      <button v-if="orders.prev_page_url" @click="fetchOrdersByUrl(orders.prev_page_url)" class="mx-1 px-3 py-1 bg-gray-200 rounded">Prev</button>
      <template v-for="link in orders.links" :key="link.label">
        <button
          v-if="link.url"
          :class="['mx-1 px-3 py-1 rounded', link.active ? 'bg-blue-600 text-white' : 'bg-gray-200']"
          @click="fetchOrdersByUrl(link.url)"
          v-html="link.label"
        />
      </template>
      <button v-if="orders.next_page_url" @click="fetchOrdersByUrl(orders.next_page_url)" class="mx-1 px-3 py-1 bg-gray-200 rounded">Next</button>
    </div>
  </div>
</AdminLayout>
</template>

<script>
// AdminOrdersIndex.vue
// Shows all product orders for admin with search, filter, status update, and pagination
import AdminLayout from '@/layouts/AdminLayout.vue';

export default {
  name: 'AdminOrdersIndex',
  components: {
    AdminLayout
  },
  props: {
    orders: Object
  },
  data() {
    return {
      search: '',
      status: '',
      successMessage: '',
      page: this.orders?.current_page || 1
    }
  },
  mounted() {
    // Initialize search and status from props if available (once, on mount)
    if (this.$props && this.$props.search) this.search = this.$props.search
    if (this.$props && this.$props.status) this.status = this.$props.status
  },
  watch: {
    // Watch for prop changes (when Inertia reloads the page)
    orders: {
      handler(newVal) {
        this.page = newVal.current_page || 1
      },
      deep: true
    }
  },
  methods: {
    fetchOrders(page = 1) {
      const params = {}
      if (this.search) params.search = this.search
      if (this.status) params.status = this.status
      if (page) params.page = page
      this.$inertia.get('/admin/orders', params, { preserveState: true, replace: true })
    },
    fetchOrdersByUrl(url) {
      // Extract page from URL for pagination
      const urlObj = new URL(url, window.location.origin)
      const params = Object.fromEntries(urlObj.searchParams.entries())
      this.$inertia.get('/admin/orders', params, { preserveState: true, replace: true })
    },
    clearFilters() {
      this.search = ''
      this.status = ''
      this.fetchOrders(1)
    },
    goToPage(page) {
      this.fetchOrders(page)
    },
    updateStatus(order) {
      this.successMessage = '';
      this.$inertia.patch(`/admin/orders/${order.id}/status`, { status: order.status }, {
        preserveState: true,
        onSuccess: () => {
          this.successMessage = 'Order status updated successfully.';
        },
        onError: () => {
          this.successMessage = 'Failed to update status.';
        }
      });
    },
    statusBadgeClass(status) {
      return {
        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
        'bg-yellow-100 text-yellow-800': status === 'Pending',
        'bg-blue-100 text-blue-800': status === 'Processing',
        'bg-green-100 text-green-800': status === 'Delivered',
        'bg-red-100 text-red-800': status === 'Cancelled',
        'bg-gray-200 text-gray-800': !['Pending','Processing','Delivered','Cancelled'].includes(status)
      }
    }
  }
}
</script>

<style scoped>
/* Add any custom styles here if needed */
</style>
