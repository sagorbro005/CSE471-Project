<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
    <!-- Header and Add Product Button -->
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-2xl font-bold text-gray-800">Manage Products</h2>
      <Link :href="route('admin.products.create')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">
        <i class="fas fa-plus mr-2"></i> Add Product
      </Link>
    </div>

    <!-- Success Message -->
    <div v-if="flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ flash.success }}
    </div>

    <!-- Product Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
      <table class="min-w-full table-auto">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Manufacturer</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
            <td class="px-4 py-3">
              <img v-if="product.image" :src="getImageUrl(product.image)" :alt="product.name" class="h-10 w-10 object-cover rounded" />
              <div v-else class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center">
                <i class="fas fa-image text-gray-400"></i>
              </div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ product.name }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ product.category?.name || 'Uncategorized' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ product.manufacturer || 'Not specified' }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">৳{{ formatPrice(product.price) }}</td>
            <td class="px-4 py-3 text-sm text-gray-900">{{ product.stock }}</td>
            <td class="px-4 py-3">
              <span :class="product.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                {{ product.status.charAt(0).toUpperCase() + product.status.slice(1) }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm">
              <div class="flex space-x-2">
                <Link :href="route('admin.products.edit', product.slug)" class="text-blue-600 hover:text-blue-900">
                  <i class="fas fa-edit"></i>
                </Link>
                <button @click="deleteProduct(product.slug)" class="text-red-600 hover:text-red-900">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="products.data.length === 0">
            <td colspan="8" class="px-4 py-3 text-center text-gray-500">No products found</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
      <Pagination :links="products.links" />
    </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { router, Link, usePage } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { imageHelper } from '@/mixins/ImageHelper.js';

// Props from controller
const props = defineProps({
  products: Object,
});

// Flash messages
const flash = usePage().props.flash || {};

// Import ImageHelper methods
const { getImageUrl } = imageHelper.methods;

// Format price helper
function formatPrice(price) {
  return parseFloat(price).toFixed(2);
}

// Delete product handler
function deleteProduct(id) {
  if (confirm('Are you sure you want to delete this product?')) {
    router.delete(route('admin.products.delete', id));
  }
}
</script>

<style scoped>
.container {
  background: #f4f7fb;
  padding: 20px;
  border-radius: 8px;
}
</style>
