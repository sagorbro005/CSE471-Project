<template>
  <div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
        <!-- Product Image -->
        <div>
          <div v-if="product.image" class="w-full h-96 bg-gray-100 rounded-lg">
            <img :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-96 object-cover rounded-lg">
          </div>
          <div v-else class="w-full h-96 bg-gray-100 flex items-center justify-center rounded-lg">
            <i class="fas fa-image text-gray-400 text-6xl"></i>
          </div>
        </div>

        <!-- Product Details -->
        <div>
          <nav class="text-sm mb-4">
            <Link :href="route('products.index')" class="text-gray-500 hover:text-gray-700">Products</Link>
            <template v-if="product.category">
              <span class="mx-2 text-gray-400">/</span>
              <Link :href="route('products.index', { category: product.category.slug })" class="text-gray-500 hover:text-gray-700">
                {{ product.category.name }}
              </Link>
            </template>
          </nav>

          <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ product.name }}</h1>

          <!-- Price and Stock Status -->
          <div class="flex items-center space-x-4 mb-6">
            <span class="text-3xl font-bold text-red-600">৳{{ formatPrice(product.price) }}</span>
            <span v-if="product.stock > 0"
              class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 rounded-full">
              In Stock
            </span>
            <span v-else class="px-3 py-1 text-sm font-semibold text-red-800 bg-red-100 rounded-full">
              Out of Stock
            </span>
          </div>

          <!-- Product Info -->
          <div class="mb-6">
            <div class="space-y-2 text-sm">
              <div class="flex items-center">
                <span class="text-gray-600 w-24">Category:</span>
                <span class="text-gray-800">{{ product.category?.name || 'Uncategorized' }}</span>
              </div>

              <div class="flex items-center">
                <span class="text-gray-600 w-24">Manufacturer:</span>
                <span class="text-gray-800">{{ product.manufacturer || 'Not specified' }}</span>
              </div>
            </div>
          </div>

          <!-- Add to Cart -->
          <div v-if="product.stock > 0" class="mb-6">
            <div class="flex items-center space-x-4">
              <div class="w-24">
                <label for="quantity" class="sr-only">Quantity</label>
                <input type="number" id="quantity" v-model="quantity" :min="1" :max="product.stock"
                  class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
              </div>
              <button @click="addToCart"
                class="flex-1 bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                Add to Cart
              </button>
            </div>
          </div>

          <!-- Description -->
          <div class="prose max-w-none">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Product Description</h3>
            <div class="text-gray-600">
              {{ product.description || 'No description available.' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Product Reviews -->
      <div class="border-t border-gray-200 mt-8 pt-8 px-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Product Reviews</h2>

        <!-- Review Form -->
        <div v-if="userCanReview" class="mb-8">
          <form @submit.prevent="submitReview">
            <div class="space-y-4">
              <div>
                <label for="review" class="block text-sm font-medium text-gray-700">Write a Review</label>
                <textarea v-model="reviewText" id="review" rows="3" maxlength="100" required
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                  placeholder="Share your thoughts about this product (max 100 words)"></textarea>
                <p class="mt-1 text-sm text-gray-500">Maximum 100 words</p>
                <p v-if="reviewError" class="mt-1 text-sm text-red-600">{{ reviewError }}</p>
              </div>
              <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                  Submit Review
                </button>
              </div>
            </div>
          </form>
        </div>
        <div v-else class="bg-gray-50 rounded-lg p-4 mb-8">
          <p class="text-gray-600">Please <Link :href="route('login')" class="text-blue-500 hover:text-blue-600">login</Link> to write a review.</p>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6">
          <div v-for="review in sortedReviews" :key="review.id" class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-between items-start mb-2">
              <div class="font-medium text-gray-900">{{ review.user.name }}</div>
              <div class="text-sm text-gray-500">{{ formatDate(review.created_at) }}</div>
            </div>
            <p class="text-gray-600">{{ review.review }}</p>
          </div>

          <div v-if="!product.reviews || product.reviews.length === 0" class="text-center py-6 text-gray-500">
            No reviews yet. Be the first to review this product!
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div v-if="relatedProducts && relatedProducts.length > 0" class="border-t border-gray-200 mt-8 pt-8 px-6 pb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Related Products</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <Link v-for="relatedProduct in relatedProducts" :key="relatedProduct.id" 
                :href="route('products.show', relatedProduct.slug)" 
                class="block group">
            <div v-if="relatedProduct.image" class="w-full h-48 bg-gray-100 rounded-lg mb-2">
              <img :src="`/storage/${relatedProduct.image}`" :alt="relatedProduct.name" class="w-full h-48 object-cover rounded-lg mb-2">
            </div>
            <div v-else class="w-full h-48 bg-gray-100 flex items-center justify-center rounded-lg mb-2">
              <i class="fas fa-image text-gray-400 text-4xl"></i>
            </div>
            <h3 class="text-sm font-medium text-gray-800 group-hover:text-blue-500">{{ relatedProduct.name }}</h3>
            <p class="text-sm font-bold text-blue-600">৳{{ formatPrice(relatedProduct.price) }}</p>
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';

// Props from controller
const props = defineProps({
  product: Object,
  relatedProducts: Array,
  userCanReview: Boolean
});

// Local state
const quantity = ref(1);
const reviewText = ref('');
const reviewError = ref('');

// Computed properties
const sortedReviews = computed(() => {
  if (!props.product.reviews) return [];
  return [...props.product.reviews].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
});

// Methods
const formatPrice = (price) => {
  return parseFloat(price).toFixed(2);
};

const formatDate = (dateString) => {
  const date = new Date(dateString);
  const now = new Date();
  
  // Calculate time difference in milliseconds
  const diffMs = now - date;
  const diffMinutes = Math.floor(diffMs / (1000 * 60));
  const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
  const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));
  
  if (diffMinutes < 60) {
    return diffMinutes === 1 ? '1 minute ago' : `${diffMinutes} minutes ago`;
  } else if (diffHours < 24) {
    return diffHours === 1 ? '1 hour ago' : `${diffHours} hours ago`;
  } else if (diffDays < 7) {
    return diffDays === 1 ? '1 day ago' : `${diffDays} days ago`;
  } else {
    return date.toLocaleDateString('en-US', { 
      year: 'numeric', 
      month: 'short', 
      day: 'numeric' 
    });
  }
};

const addToCart = () => {
  // This is a placeholder for the cart functionality
  // In a real application, you would call an API to add the product to the cart
  alert(`Added ${quantity.value} ${props.product.name}(s) to cart`);
};

const submitReview = () => {
  reviewError.value = '';
  
  if (!reviewText.value.trim()) {
    reviewError.value = 'Review cannot be empty';
    return;
  }
  
  router.post(route('products.review', props.product.slug), {
    review: reviewText.value
  }, {
    onSuccess: () => {
      reviewText.value = '';
    },
    onError: (errors) => {
      if (errors.review) {
        reviewError.value = errors.review;
      }
    }
  });
};
</script>

<style scoped>
.prose {
  max-width: 65ch;
}
</style>
