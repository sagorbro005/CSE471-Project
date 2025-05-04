<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Back Button -->
      <div class="mb-6">
        <router-link to="/orders" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
          <i class="fas fa-arrow-left mr-2"></i>
          Back to Orders
        </router-link>
      </div>
      <!-- Order Header -->
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-t-xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div class="text-white">
            <h1 class="text-3xl font-bold">Order #{{ order.id }}</h1>
            <div class="mt-2 space-y-1">
              <p class="text-blue-100">
                <i class="far fa-clock mr-2"></i>
                Placed on {{ order.placed_at }}
              </p>
              <p class="text-blue-100">
                <i :class="order.payment_icon + ' mr-2'"></i>
                Payment via {{ order.payment_text }}
                <span v-if="order.payment_status" class="ml-2 px-2 py-1 bg-green-500 bg-opacity-25 rounded-full text-xs border border-green-400">
                  {{ order.payment_status }}
                </span>
              </p>
            </div>
          </div>
          <div class="mt-4 md:mt-0">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white shadow-md" :class="statusClass(order.status)">
              <i class="fas fa-circle text-xs mr-2"></i>
              {{ order.status }}
            </span>
          </div>
        </div>
      </div>
      <!-- Order Details -->
      <div class="bg-white shadow-xl rounded-b-xl">
        <!-- Products List -->
        <div class="p-6 md:p-8 border-b">
          <h2 class="text-xl font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent mb-4">
            Order Items
          </h2>
          <div class="space-y-4">
            <div v-for="item in order.items" :key="item.id" class="flex items-center space-x-4 p-4 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
              <img :src="item.image" alt="Product Image" class="w-16 h-16 rounded object-cover" />
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ item.name }}</div>
                <div class="text-sm text-gray-500">Quantity: {{ item.quantity }}</div>
              </div>
              <div class="text-lg font-medium text-gray-900">৳{{ formatPrice(item.price * item.quantity) }}</div>
            </div>
          </div>
        </div>
        <!-- Totals -->
        <div class="p-6 md:p-8 border-b bg-gradient-to-br from-blue-50 to-purple-50">
          <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-8">
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span>৳{{ formatPrice(order.subtotal) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Delivery Fee</span>
                <span>৳{{ formatPrice(order.delivery_charge) }}</span>
              </div>
              <div class="pt-3 border-t border-gray-200 flex justify-between text-lg font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                <span>Total</span>
                <span>৳{{ formatPrice(order.total) }}</span>
              </div>
            </div>
            <!-- Delivery Info -->
            <div class="space-y-2">
              <div class="bg-purple-50 p-4 rounded-xl mb-2">
                <h3 class="text-sm font-medium text-purple-600 mb-2">
                  <i class="fas fa-map-marker-alt mr-2"></i>
                  Delivery Address
                </h3>
                <p class="text-sm text-gray-900">{{ order.delivery_address }}</p>
              </div>
              <div class="bg-pink-50 p-4 rounded-xl">
                <h3 class="text-sm font-medium text-pink-600 mb-2">
                  <i class="fas fa-phone-alt mr-2"></i>
                  Contact Information
                </h3>
                <p class="text-sm text-gray-900">{{ order.contact_phone }}</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Actions -->
        <div class="p-6 md:p-8 border-t border-gray-100 bg-gradient-to-br from-gray-50 to-blue-50 rounded-b-xl">
          <div class="flex flex-col sm:flex-row sm:justify-end space-y-4 sm:space-y-0 sm:space-x-4">
            <button class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <i class="fas fa-download mr-2"></i>
              Download Invoice
            </button>
            <button v-if="order.status === 'Delivered'" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
              <i class="fas fa-star mr-2"></i>
              Rate Products
            </button>
            <button class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <i class="fas fa-question-circle mr-2"></i>
              Need Help?
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
// Order details page for a single order
// Expects a prop `order` with all order info (see below)
const props = defineProps({
  order: Object
});
function formatPrice(price) {
  return Number(price).toFixed(2);
}
function statusClass(status) {
  switch (status) {
    case 'Pending': return 'text-yellow-600';
    case 'Processing': return 'text-blue-600';
    case 'Shipped': return 'text-purple-600';
    case 'Delivered': return 'text-green-600';
    default: return 'text-gray-600';
  }
}
</script>
<style scoped>
.bg-clip-text {
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
