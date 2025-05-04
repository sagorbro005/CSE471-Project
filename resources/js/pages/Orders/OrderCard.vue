<template>
  <!-- Order Card Component -->
  <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl hover:shadow-xl transition-all duration-300 border border-gray-100">
    <div class="p-6">
      <!-- Order Info and Action Buttons Container -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <!-- Order Info -->
        <div class="flex-1">
          <!-- Order ID and Status -->
          <div class="flex items-center">
            <h3 class="text-lg font-semibold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
              Order #{{ order.id }}
            </h3>
            <span class="ml-4 px-3 py-1 rounded-full text-sm font-medium" :class="statusClass(order.status)">
              {{ order.status }}
            </span>
          </div>
          <!-- Order Date and Total -->
          <div class="mt-2 flex flex-col md:flex-row md:items-center text-sm text-gray-500">
            <div class="flex items-center">
              <i class="far fa-calendar-alt mr-2"></i>
              <span>{{ order.formatted_date }}</span>
            </div>
            <span class="hidden md:inline mx-2">•</span>
            <div class="flex items-center mt-1 md:mt-0">
              <i class="fas fa-money-bill-wave mr-2"></i>
              <span>৳{{ formatPrice(order.total) }}</span>
            </div>
          </div>
          <!-- Order Items, Payment Method, and Payment Status -->
          <div class="mt-4 flex flex-wrap items-center gap-4">
            <div class="flex items-center text-gray-700 bg-blue-50 px-3 py-1 rounded-lg">
              <i class="fas fa-shopping-bag text-blue-500 mr-2"></i>
              <span>{{ order.items_count }} items</span>
            </div>
            <div class="flex items-center text-gray-700 bg-purple-50 px-3 py-1 rounded-lg">
              <i :class="order.payment_icon + ' mr-2'"></i>
              <span>{{ order.payment_text }}</span>
            </div>
            <div v-if="order.payment_status" class="flex items-center text-gray-700 bg-pink-50 px-3 py-1 rounded-lg">
              <i class="fas fa-check-circle text-pink-400 mr-2"></i>
              <span>{{ order.payment_status }}</span>
            </div>
          </div>
        </div>
        <!-- Action Button -->
        <div class="mt-6 md:mt-0 flex flex-col gap-2 items-end">
          <button @click="$emit('view-details', order.id)" class="inline-flex items-center px-5 py-2 rounded-lg text-white bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-300">
            <i class="fas fa-eye mr-2"></i> View Details
          </button>
          <button v-if="order.status === 'Delivered'" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <i class="fas fa-star mr-2"></i>
            Rate Order
          </button>
        </div>
      </div>
      <!-- Progress Bar for Processing/Shipped Orders -->
      <div v-if="['Processing', 'Shipped'].includes(order.status)" class="mt-6">
        <div class="relative">
          <div class="overflow-hidden h-2 text-xs flex rounded-full bg-blue-100">
            <div :style="{ width: order.progress + '%' }"
                 class="animate-pulse shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500">
            </div>
          </div>
          <div class="flex justify-between text-xs text-gray-600 mt-1">
            <span>Order Placed</span>
            <span>Processing</span>
            <span>Shipped</span>
            <span>Delivered</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
// Define props for the component
const props = defineProps({ order: Object });

// Function to format price
function formatPrice(price) {
  return Number(price).toFixed(2);
}

// Function to determine status class
function statusClass(status) {
  switch (status) {
    case 'Pending': return 'bg-yellow-100 text-yellow-800';
    case 'Processing': return 'bg-blue-100 text-blue-800';
    case 'Shipped': return 'bg-purple-100 text-purple-800';
    case 'Delivered': return 'bg-green-100 text-green-800';
    default: return 'bg-gray-100 text-gray-800';
  }
}
</script>
<style scoped>
/* Style for background clip text */
.bg-clip-text {
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text; /* Standard property for compatibility */
}
</style>
