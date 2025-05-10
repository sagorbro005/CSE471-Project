<template>
  <NavBar />
  <!-- Success Message -->
  <div v-if="showSuccess" class="max-w-xl mx-auto mb-4">
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md flex items-center justify-between shadow transition-all duration-300">
      <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ successMessage }}
      </div>
      <button @click="showSuccess = false" class="text-green-600 hover:text-green-800">
        <i class="fas fa-times"></i>
      </button>
    </div>
  </div>
  <div class="container mx-auto px-4 py-8">
    <!-- Shopping Cart Header -->
    <h1 class="text-2xl font-bold mb-6">Shopping Cart</h1>

    <!-- If cart is empty -->
    <div v-if="items.length === 0" class="bg-white rounded-lg shadow p-6 text-center">
      <div class="text-gray-500 mb-4">Your cart is empty</div>
      <Link :href="route('products.index')" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
        Continue Shopping
      </Link>
    </div>

    <!-- If cart has items -->
    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <!-- Cart Items Table -->
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-gray-50 border-b">
              <th class="py-4 px-2 text-left w-12"></th>
              <th class="py-4 px-4 text-left">Product</th>
              <th class="py-4 px-4 text-right">Price</th>
              <th class="py-4 px-4 text-center">Quantity</th>
              <th class="py-4 px-4 text-right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <!-- Cart Item Row -->
            <tr v-for="item in items" :key="item.id" class="border-b hover:bg-gray-50 transition-colors">
              <!-- Remove Button -->
              <td class="py-4 px-2">
                <button @click="removeItem(item.id)" class="text-red-400 hover:text-red-600 transition-colors">
                  <i class="fas fa-times"></i>
                </button>
              </td>
              
              <!-- Product Info -->
              <td class="py-4 px-4">
                <div class="flex items-center space-x-4">
                  <!-- Product Image -->
                  <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                    <Link :href="route('products.show', item.product.slug)">
                      <img 
                        :src="item.product.image ? `/storage/${item.product.image}` : '/images/placeholder.png'" 
                        :alt="item.product.name"
                        class="w-full h-full object-cover hover:opacity-75 transition-opacity"
                      >
                    </Link>
                  </div>
                  
                  <!-- Product Details -->
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">
                      <Link :href="route('products.show', item.product.slug)" class="hover:text-blue-600">
                        {{ item.product.name }}
                      </Link>
                    </h3>
                    <p class="text-sm text-gray-500">{{ truncate(item.product.description, 50) }}</p>
                  </div>
                </div>
              </td>
              
              <!-- Price -->
              <td class="py-4 px-4 text-right">
                <span class="text-gray-900 font-medium">৳{{ formatPrice(item.product.price) }}</span>
              </td>
              
              <!-- Quantity Controls -->
              <td class="py-4 px-4">
                <div class="flex justify-center">
                  <div class="inline-flex items-center border border-gray-300 rounded-lg">
                    <!-- Decrement Button -->
                    <button 
                      type="button"
                      class="w-10 h-10 leading-10 text-gray-600 transition hover:opacity-75"
                      @click="decrementQuantity(item)"
                    >
                      −
                    </button>

                    <!-- Quantity Input -->
                    <input 
                      type="number"
                      v-model.number="item.quantity"
                      class="h-10 w-16 border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none"
                      min="1" 
                    />

                    <!-- Increment Button -->
                    <button 
                      type="button"
                      class="w-10 h-10 leading-10 text-gray-600 transition hover:opacity-75"
                      @click="incrementQuantity(item)"
                    >
                      +
                    </button>
                  </div>
                </div>
              </td>
              
              <!-- Subtotal -->
              <td class="py-4 px-4 text-right">
                <span class="text-lg font-medium text-gray-900">
                  ৳{{ formatPrice(item.product.price * item.quantity) }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Cart Actions -->
      <div class="p-6 bg-white border-t border-gray-100">
        <div class="flex justify-end space-x-4">
          <button 
            type="button"
            @click="emptyCart"
            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-300"
          >
            Empty Cart
          </button>
          <button 
            type="button"
            @click="updateCart"
            class="px-6 py-3 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300"
          >
            Update Cart
          </button>
        </div>

        <!-- Cart Summary -->
        <div class="mt-8 max-w-md ml-auto">
          <div class="bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Cart Summary</h2>
            <div class="space-y-4">
              <!-- Subtotal -->
              <div class="flex justify-between py-3 border-b border-gray-200">
                <span class="text-gray-600">Subtotal</span>
                <span class="text-gray-900 font-medium">৳{{ formatPrice(subtotal) }}</span>
              </div>
              
              <!-- Total -->
              <div class="flex justify-between py-3">
                <span class="text-gray-900 font-bold">Total</span>
                <span class="text-gray-900 font-bold">৳{{ formatPrice(subtotal) }}</span>
              </div>
              
              <!-- Checkout Button -->
              <Link 
                :href="route('cart.checkout')"
                class="block w-full px-6 py-4 text-center text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
              >
                Proceed to Checkout
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

// Flash success message logic
const page = usePage();
const successMessage = ref(page.props.flash?.success || '');
const showSuccess = ref(!!successMessage.value);

watch(() => page.props.flash?.success, (newVal) => {
  successMessage.value = newVal;
  showSuccess.value = !!newVal;
  if (showSuccess.value) {
    setTimeout(() => showSuccess.value = false, 5000);
  }
});

onMounted(() => {
  if (showSuccess.value) {
    setTimeout(() => showSuccess.value = false, 5000);
  }
});

// Props from controller
const props = defineProps({
  cartItems: Array,
  subtotal: Number
});

// Local reactive state for cart items
const items = ref(props.cartItems.map(item => ({ ...item })));

// Sync items with props.cartItems after every Inertia reload
watch(
  () => props.cartItems,
  (newCartItems) => {
    items.value = newCartItems.map(item => ({ ...item }));
  },
  { immediate: true }
);

// Helper function to truncate text
function truncate(text, length) {
  if (!text) return '';
  return text.length > length ? text.substring(0, length) + '...' : text;
}

// Format price to display with 2 decimal places
function formatPrice(price) {
  return Number(price).toFixed(2);
}

// Increment item quantity
function incrementQuantity(item) {
  item.quantity++;
}

// Decrement item quantity (minimum 1)
function decrementQuantity(item) {
  if (item.quantity > 1) {
    item.quantity--;
  }
}

// Remove item from cart
function removeItem(cartId) {
  if (confirm('Are you sure you want to remove this item from your cart?')) {
    router.delete(route('cart.remove', cartId), {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = 'Item removed from cart successfully!';
        showSuccess.value = true;
        setTimeout(() => {
          showSuccess.value = false;
        }, 5000);
      }
    });
  }
}

// Empty entire cart
function emptyCart() {
  if (confirm('Are you sure you want to empty your cart?')) {
    router.post(route('cart.clear'), {}, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = 'Cart emptied successfully!';
        showSuccess.value = true;
        setTimeout(() => {
          showSuccess.value = false;
        }, 5000);
      }
    });
  }
}

// Update cart quantities
function updateCart() {
  // Only update items where quantity has changed
  const changedItems = items.value.filter((item, idx) => item.quantity !== props.cartItems[idx].quantity);

  if (changedItems.length === 0) {
    // No changes, show a message
    successMessage.value = 'No changes to update';
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 5000);
    return;
  }

  // Sequentially update each changed cart item
  let promise = Promise.resolve();
  changedItems.forEach((item) => {
    promise = promise.then(() => {
      return new Promise((resolve, reject) => {
        router.post(route('cart.update', item.id), { quantity: item.quantity }, {
          preserveScroll: true,
          onSuccess: resolve,
          onError: reject,
        });
      });
    });
  });

  // After all updates, show success message
  promise.then(() => {
    successMessage.value = 'Cart updated successfully!';
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 5000);
    
    // Reload to get fresh data
    router.visit(route('cart.index'), {
      preserveScroll: true,
    });
  });
}

</script>
