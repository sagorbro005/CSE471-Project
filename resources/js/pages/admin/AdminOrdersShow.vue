<template>
  <div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold">Order Details</h2>
      <span class="text-gray-600">Welcome, Admin</span>
    </div>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h3 class="text-xl font-semibold mb-1">Order #{{ order.id }}</h3>
          <div class="flex items-center gap-2">
            <label class="font-semibold">Order Status:</label>
            <select v-model="orderStatus" class="border rounded px-2 py-1">
              <option value="Pending">Pending</option>
              <option value="Processing">Processing</option>
              <option value="Delivered">Delivered</option>
              <option value="Cancelled">Cancelled</option>
            </select>
            <button @click="updateOrderStatus" class="ml-2 px-4 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Update Status</button>
          </div>
        </div>
        <Link href="/admin/orders" class="text-blue-600 hover:underline">&larr; Back to Orders</Link>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 rounded p-4">
          <h4 class="font-semibold mb-2">Customer Information</h4>
          <div><b>Name:</b> {{ order.user.name }}</div>
          <div><b>Email:</b> {{ order.user.email }}</div>
          <div><b>Phone:</b> {{ order.user.phone }}</div>
          <div v-if="order.user.gender"><b>Gender:</b> {{ order.user.gender }}</div>
          <div v-if="order.user.date_of_birth"><b>Date of Birth:</b> {{ order.user.date_of_birth }}</div>
          <div v-if="order.user.age"><b>Age:</b> {{ order.user.age }} years</div>
          <div><b>Address:</b> {{ order.user.address }}</div>
          <div><b>Order Date:</b> {{ order.date }} {{ order.time }}</div>
        </div>
        <div class="bg-gray-50 rounded p-4">
          <h4 class="font-semibold mb-2">Order Summary</h4>
          <div><b>Subtotal:</b> ৳{{ safeToFixed(order.subtotal) }}</div>
          <div><b>Delivery Charge:</b> ৳{{ safeToFixed(order.delivery_charge) }}</div>
          <div><b>Total:</b> ৳{{ safeToFixed(order.total) }}</div>
          <div><b>Payment Method:</b> {{ order.payment_method ?? 'Cash on Delivery' }}</div>
          <div><b>Payment Status:</b> <span :class="statusBadgeClass(paymentStatusDisplay)" v-if="paymentStatusDisplay">{{ paymentStatusDisplay }}</span><span v-else>N/A</span></div>
        </div>
      </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold mb-2">Order Items</h3>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="item in order.items" :key="item.id">
            <td class="px-6 py-4">
              <Link v-if="item.slug" :href="route('products.show', { product: item.slug })" class="text-blue-700 hover:underline">
  {{ item.name }}
</Link>
<span v-else class="text-gray-400">{{ item.name }} (no slug)</span>
            </td>
            <td class="px-6 py-4">৳{{ safeToFixed(item.price) }}</td>
            <td class="px-6 py-4">{{ item.quantity }}</td>
            <td class="px-6 py-4">৳{{ safeToFixed(item.price * item.quantity) }}</td>
            <td class="px-6 py-4">
              <img :src="item.image" alt="Product" class="w-16 h-16 object-cover rounded" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router, Link } from '@inertiajs/vue3';
const props = defineProps({ order: Object });
const orderStatus = ref(props.order.status);
const updating = ref(false);

const paymentStatusDisplay = computed(() => {
  const paymentMethod = (props.order.payment_method || '').toLowerCase();
  const status = orderStatus.value;
  if (['mobile payment', 'card payment'].includes(paymentMethod)) {
    if (status === 'Cancelled') return '';
    return 'Paid';
  } else {
    if (status === 'Delivered') return 'Paid';
    if (status === 'Pending' || status === 'Processing') return 'Pending';
    if (status === 'Cancelled') return '';
  }
  return '';
});

function safeToFixed(val) {
  const num = Number(val);
  return isNaN(num) ? '0.00' : num.toFixed(2);
}

function updateOrderStatus() {
  if (updating.value) return;
  updating.value = true;
  router.patch(`/admin/orders/${props.order.id}/status`, { status: orderStatus.value }, {
    onSuccess: () => {
      updating.value = false;
    },
    onError: () => {
      updating.value = false;
    }
  });
}

function statusBadgeClass(status) {
  return {
    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
    'bg-yellow-100 text-yellow-800': status === 'Pending',
    'bg-blue-100 text-blue-800': status === 'Processing',
    'bg-green-100 text-green-800': status === 'Delivered',
    'bg-red-100 text-red-800': status === 'Cancelled',
    'bg-gray-200 text-gray-800': !['Pending','Processing','Delivered','Cancelled'].includes(status)
  }
}
</script>

<style scoped>
</style>
