<template>
  <NavBar />
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Back Button -->
      <div class="mb-6">
        <Link :href="route('orders.index')" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
          <i class="fas fa-arrow-left mr-2"></i>
          Back to Orders
        </Link>
      </div>
      <!-- Order Header -->
      <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-t-xl p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
          <div class="text-white">
            <h1 class="text-3xl font-bold">Order #{{ order.id }}</h1>
            <div class="mt-2 space-y-1">
              <p class="text-blue-100">
                <i class="far fa-clock mr-2"></i>
                Placed on {{ formatBangladeshDate(order.placed_at) }}
              </p>
              <p class="text-blue-100">
                <i :class="order.payment_icon + ' mr-2'"></i>
                <span>{{ order.payment_text }}</span>
                <span v-if="paymentStatusDisplay" class="ml-4"><i class="fas fa-check-circle text-pink-200 mr-1"></i>{{ paymentStatusDisplay }}</span>
                <span v-else class="ml-4 text-gray-300"><i class="fas fa-ban mr-1"></i>N/A</span>
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
              <img :src="item.image ? `/storage/${item.image}` : '/images/placeholder.png'" alt="Product Image" class="w-16 h-16 rounded object-cover" />
              <div class="flex-1 min-w-0">
                <div class="font-medium text-gray-900">{{ item.name }}</div>
                <div class="text-sm text-gray-500">Quantity: {{ item.quantity }}</div>
              </div>
              <div class="text-lg font-medium text-gray-900">৳{{ formatPrice(item.price * item.quantity) }}</div>
              <button
                v-if="order.status === 'Delivered' && item.slug"
                @click="goToProductReview({ product: item.slug })"
                class="ml-4 inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-xs font-medium text-white bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
              >
                <i class="fas fa-star mr-1"></i>
                Review Product
              </button>
              <span v-else-if="order.status === 'Delivered' && !item.slug" class="ml-4 text-xs text-red-500">No product review available (missing slug)</span>
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
              <div v-if="order.discount && order.discount > 0" class="flex justify-between text-green-600 font-medium">
                <span>Discount <span v-if="order.coupon_code">({{ order.coupon_code }})</span></span>
                <span>-৳{{ formatPrice(order.discount) }}</span>
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
                <div class="text-sm text-gray-900">
                  <p v-if="order.address">{{ order.address }}</p>
                  <p v-if="order.city">{{ order.city }}{{ order.zip_code ? ', ' + order.zip_code : '' }}</p>
                  <p>{{ order.delivery_address || '-' }}</p>
                </div>
              </div>
              <div class="bg-pink-50 p-4 rounded-xl">
                <h3 class="text-sm font-medium text-pink-600 mb-2">
                  <i class="fas fa-user mr-2"></i>
                  Customer Name
                </h3>
                <p class="text-sm text-gray-900">{{ order.customer_name || '-' }}</p>
                <h3 class="text-sm font-medium text-pink-600 mb-2 mt-3">
                  <i class="fas fa-envelope mr-2"></i>
                  Email
                </h3>
                <p class="text-sm text-gray-900">{{ order.contact_email || '-' }}</p>
                <h3 class="text-sm font-medium text-pink-600 mb-2 mt-3">
                  <i class="fas fa-phone-alt mr-2"></i>
                  Phone
                </h3>
                <p class="text-sm text-gray-900">{{ order.contact_phone || '-' }}</p>
              </div>
            </div>
          </div>
        </div>
        <!-- Actions -->
        <div class="p-6 md:p-8 border-t border-gray-100 bg-gradient-to-br from-gray-50 to-blue-50 rounded-b-xl">
          <div class="flex flex-col sm:flex-row sm:justify-end space-y-4 sm:space-y-0 sm:space-x-4">
            <button @click="downloadInvoice" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <i class="fas fa-download mr-2"></i>
              Download Invoice
            </button>

            <Link :href="route('support.index')" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
              <i class="fas fa-question-circle mr-2"></i>
              Need Help?
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>
<script setup>
import { Inertia } from '@inertiajs/inertia';
import NavBar from '@/components/NavBar.vue'
import Footer from '@/components/Footer.vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { computed } from 'vue';
// Order details page for a single order
// Expects a prop `order` with all order info (see below)
const props = defineProps({
  order: Object
});

function downloadInvoice() {
  // Download as file (open in new tab to trigger browser download)
  window.open(route('orders.invoice', props.order.id), '_blank');
}

function goToProductReview(productParam) {
  Inertia.visit(route('products.show', productParam));
}
const paymentStatusDisplay = computed(() => {
  const paymentMethod = (props.order.payment_method || '').toLowerCase();
  const status = props.order.status;
  if (["mobile payment", "card payment", "mobile", "card"].includes(paymentMethod)) {
    if (status === 'Cancelled') return '';
    return 'Paid';
  } else {
    if (status === 'Delivered') return 'Paid';
    if (status === 'Pending' || status === 'Processing') return 'Pending';
    if (status === 'Cancelled') return '';
  }
  return '';
});
function formatPrice(price) {
  return Number(price).toFixed(2);
}
// Format date/time as Bangladesh Standard Time
function formatBangladeshDate(dateString) {
  // The backend now sends the date in Bangladesh Standard Time (UTC+6)
  // We just need to format it properly
  const date = new Date(dateString);
  // Format: 06 May 2025, 02:57 AM
  return date.toLocaleString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  });
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
  background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>
