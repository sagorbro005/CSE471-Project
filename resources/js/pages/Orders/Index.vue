<template>
  <NavBar />
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
          My Orders
        </h1>
        <p class="mt-2 text-gray-600">Track and manage your orders</p>
      </div>
      <!-- Orders List -->
      <div class="space-y-6">
        <div v-if="page.props.flash && page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
          <span class="block sm:inline">{{ page.props.flash.success }}</span>
          <span v-if="page.props.flash.order_id" class="block sm:inline ml-2">
            Order #{{ page.props.flash.order_id }} has been placed.
          </span>
        </div>
        <template v-if="orders.length">
          <OrderCard v-for="order in formattedOrders" :key="order.id" :order="order" @view-details="viewDetails" />
        </template>
        <template v-else>
          <EmptyState />
        </template>
        <!-- Pagination Placeholder -->
        <!-- <div class="mt-6">Pagination Here</div> -->
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
// Orders page for customers
// This component expects a prop `orders` which is an array of order objects from the backend
// Each order object should have: id, status, placed_at, total, items (array), payment_icon, payment_text, payment_status, progress
import OrderCard from './OrderCard.vue'
import EmptyState from './EmptyState.vue'
import NavBar from '@/components/NavBar.vue'
import Footer from '@/components/Footer.vue'
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { route } from 'ziggy-js'

// Define props with default value
const props = defineProps({
  orders: {
    type: Array,
    default: () => []
  }
});

// Access page props for flash messages
const page = usePage();

// Format orders for display (add items_count, formatted_date, progress)
const formattedOrders = computed(() => {
  return props.orders.map(order => ({
    ...order,
    items_count: order.items ? order.items.length : (order.products ? order.products.length : 0),
    formatted_date: order.placed_at || order.created_at,
    progress: getOrderProgress(order.status)
  }));
});

// View order details
function viewDetails(orderId) {
  // Use Inertia navigation for order details
  // Fallback to Inertia's global router object
  window.location.href = route('orders.show', orderId);
}

// Format price
function formatPrice(price) {
  // Return price with two decimal places
  return Number(price).toFixed(2);
}

// Status badge class
function statusClass(status) {
  // Return class based on order status
  switch (status) {
    case 'Pending': return 'bg-yellow-100 text-yellow-800';
    case 'Processing': return 'bg-blue-100 text-blue-800';
    case 'Shipped': return 'bg-purple-100 text-purple-800';
    case 'Delivered': return 'bg-green-100 text-green-800';
    default: return 'bg-gray-100 text-gray-800';
  }
}

// Helper for progress bar
function getOrderProgress(status) {
  switch (status) {
    case 'Pending': return 10;
    case 'Processing': return 40;
    case 'Shipped': return 70;
    case 'Delivered': return 100;
    default: return 0;
  }
}
</script>

<style scoped>
.bg-clip-text {
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
</style>
