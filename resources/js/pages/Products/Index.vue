<template>
  <NavBar />
  <!-- Success Message -->
  <div v-if="showSuccess" class="max-w-xl mx-auto mt-4 mb-4">
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
    <!-- Search and Category Navigation -->
    <div class="mb-8 space-y-6">
      <!-- Search Box -->
      <div class="max-w-2xl mx-auto">
        <form @submit.prevent="submitSearch" class="relative">
          <div class="relative">
            <input type="text" v-model="searchQuery" placeholder="Search for products..."
              class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
              <i class="fas fa-search"></i>
            </div>
            <button v-if="searchQuery" @click="clearSearch" type="button"
              class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- Category Navigation -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h2 class="text-2xl font-bold text-gray-800">
          {{ selectedCategoryName || 'All Products' }}
        </h2>
        <div class="flex flex-wrap gap-2">
          <button @click="selectCategory(null)"
            class="px-4 py-2 rounded-full"
            :class="!selectedCategory ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
            All
          </button>
          <button v-for="category in categories" :key="category.id" @click="selectCategory(category.slug)"
            class="px-4 py-2 rounded-full"
            :class="selectedCategory === category.slug ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
            {{ category.name }}
          </button>
        </div>
      </div>

      <!-- Search Results Info -->
      <div v-if="searchQuery" class="text-sm text-gray-600">
        Showing results for "{{ searchQuery }}" {{ selectedCategory ? 'in ' + selectedCategoryName : '' }}
        ({{ totalResults }} {{ totalResults === 1 ? 'result' : 'results' }})
      </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
      <div v-for="product in products.data" :key="product.id"
        class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <Link :href="route('products.show', product.slug)" class="block">
          <div v-if="product.image" class="w-full h-32 bg-gray-100">
            <img :src="`/storage/${product.image}`" :alt="product.name" class="w-full h-32 object-cover">
          </div>
          <div v-else class="w-full h-32 bg-gray-100 flex items-center justify-center">
            <i class="fas fa-image text-gray-400 text-4xl"></i>
          </div>

          <div class="p-4">
            <!-- Stock Status -->
            <div class="mb-2">
              <span v-if="product.stock > 0"
                class="inline-block px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                In Stock
              </span>
              <span v-else class="inline-block px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                Out of Stock
              </span>
            </div>

            <!-- Category -->
            <div class="text-sm text-gray-500 mb-1">
              {{ product.category?.name || 'Uncategorized' }}
            </div>

            <!-- Product Name -->
            <h3 class="text-lg font-semibold text-gray-800 mb-2 line-clamp-2">{{ product.name }}</h3>

            <!-- Price -->
            <div class="flex items-center justify-between">
              <span class="text-xl font-bold text-red-600">৳{{ formatPrice(product.price) }}</span>

              <button v-if="product.stock > 0" @click.prevent="addToCart(product)"
                class="p-2 bg-green-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                <i class="fas fa-shopping-cart text-sm"></i>
              </button>
            </div>
          </div>
        </Link>
      </div>

      <!-- Empty State -->
      <div v-if="products.data.length === 0" class="col-span-full text-center py-12">
        <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
        <p class="text-gray-500">No products found{{ searchQuery ? ' matching "' + searchQuery + '"' : '' }}.</p>
        <button v-if="searchQuery" @click="clearSearch" class="inline-block mt-4 text-blue-500 hover:text-blue-600">
          Clear search
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
      <Pagination :links="products.links" />
    </div>
  </div>
  <Footer />
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

// Props from controller
const props = defineProps({
  products: Object,
  categories: Array,
  filters: Object,
  totalResults: Number
});

// Local state
const searchQuery = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || null);

// Computed properties
const selectedCategoryName = computed(() => {
  if (!selectedCategory.value) return '';
  const category = props.categories.find(c => c.slug === selectedCategory.value);
  return category ? category.name : '';
});

// Methods
const formatPrice = (price) => {
  return parseFloat(price).toFixed(2);
};

const submitSearch = () => {
  router.get(route('products.index'), {
    search: searchQuery.value || null,
    category: selectedCategory.value || null
  }, {
    preserveState: true,
    replace: true
  });
};

const clearSearch = () => {
  searchQuery.value = '';
  submitSearch();
};

const selectCategory = (categorySlug) => {
  selectedCategory.value = categorySlug;
  submitSearch();
};

// Success message handling
const successMessage = ref('');
const showSuccess = ref(false);

// Watch for flash messages from server
watch(() => usePage().props.flash?.success, (message) => {
  if (message) {
    successMessage.value = message;
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 5000);
  }
}, { immediate: true });

const addToCart = (product) => {
  // Send request to backend to add to cart
  router.post(route('cart.add', product.id), {
    quantity: 1 // Default to 1, or allow user to select
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Now using the success message banner instead of alert
      successMessage.value = `Added ${product.name} to cart successfully!`;
      showSuccess.value = true;
      setTimeout(() => {
        showSuccess.value = false;
      }, 5000);
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
};

// Watch for changes in search query (for immediate search)
watch(searchQuery, (newValue, oldValue) => {
  if (!newValue && oldValue) {
    submitSearch();
  }
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
