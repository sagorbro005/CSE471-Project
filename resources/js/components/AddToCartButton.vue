<template>
  <div>
    <!-- Quantity Controls -->
    <div class="flex items-center mb-4">
      <label for="quantity" class="block text-sm font-medium text-gray-700 mr-3">Quantity:</label>
      <div class="inline-flex items-center border border-gray-300 rounded-lg">
        <!-- Decrement Button -->
        <button 
          type="button"
          class="w-8 h-8 leading-8 text-gray-600 transition hover:opacity-75"
          @click="decrementQuantity"
        >
          −
        </button>

        <!-- Quantity Input -->
        <input 
          type="number"
          v-model.number="quantity"
          class="h-8 w-12 border-transparent text-center [-moz-appearance:_textfield] sm:text-sm [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none"
          min="1" 
        />

        <!-- Increment Button -->
        <button 
          type="button"
          class="w-8 h-8 leading-8 text-gray-600 transition hover:opacity-75"
          @click="incrementQuantity"
        >
          +
        </button>
      </div>
    </div>

    <!-- Add to Cart Button -->
    <button 
      @click="addToCart"
      class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors"
      :disabled="isLoading"
    >
      <i class="fas fa-shopping-cart mr-2"></i>
      <span v-if="isLoading">Adding...</span>
      <span v-else>Add to Cart</span>
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

// Props
const props = defineProps({
  productId: {
    type: [Number, String],
    required: true
  },
  productSlug: {
    type: String,
    required: true
  }
});

// Reactive state
const quantity = ref(1);
const isLoading = ref(false);

// Increment quantity
function incrementQuantity() {
  quantity.value++;
}

// Decrement quantity (minimum 1)
function decrementQuantity() {
  if (quantity.value > 1) {
    quantity.value--;
  }
}

// Add to cart function
function addToCart() {
  isLoading.value = true;
  
  // Submit to the add to cart endpoint
  router.post(route('cart.add', props.productSlug), {
    quantity: quantity.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Reset quantity after successful add
      quantity.value = 1;
      isLoading.value = false;
    },
    onError: () => {
      isLoading.value = false;
    }
  });
}
</script>
