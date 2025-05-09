<template>
  <AdminLayout>
    <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-2xl font-bold text-gray-800">Add New Product</h2>
      <Link :href="route('admin.products')" class="text-gray-600 hover:text-gray-900">
        <i class="fas fa-arrow-left mr-2"></i>Back to Products
      </Link>
    </div>

    <!-- Form -->
    <form @submit.prevent="submitForm" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6 space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Product Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
          <input v-model="form.name" type="text" name="name" id="name" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <ErrorMsg :error="errors.name" />
        </div>

        <!-- Category -->
        <div>
          <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
          <select v-model="form.category_id" name="category_id" id="category_id" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="">Select Category</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select>
          <ErrorMsg :error="errors.category_id" />
        </div>

        <!-- Manufacturer -->
        <div>
          <label for="manufacturer" class="block text-sm font-medium text-gray-700">Manufacturer</label>
          <input v-model="form.manufacturer" type="text" name="manufacturer" id="manufacturer"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <ErrorMsg :error="errors.manufacturer" />
        </div>

        <!-- Price -->
        <div>
          <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500 sm:text-sm">৳</span>
            </div>
            <input v-model="form.price" type="number" name="price" id="price" step="0.01" required
              class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          </div>
          <ErrorMsg :error="errors.price" />
        </div>

        <!-- Stock -->
        <div>
          <label for="stock" class="block text-sm font-medium text-gray-700">Stock</label>
          <input v-model="form.stock" type="number" name="stock" id="stock" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
          <ErrorMsg :error="errors.stock" />
        </div>

        <!-- Status -->
        <div>
          <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
          <select v-model="form.status" name="status" id="status" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
          <ErrorMsg :error="errors.status" />
        </div>

        <!-- Image -->
        <div>
          <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
          <input @change="handleImage" type="file" name="image" id="image" accept="image/*"
            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
          <ErrorMsg :error="errors.image" />
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea v-model="form.description" name="description" id="description" rows="4"
          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
        <ErrorMsg :error="errors.description" />
      </div>

      <div class="flex justify-end">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition">Create Product</button>
      </div>
    </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, Link, useForm, usePage } from '@inertiajs/vue3';
import ErrorMsg from '@/components/ErrorMsg.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

// Props from controller
const props = defineProps({
  categories: Array,
  errors: Object
});

// Form state
const form = ref({
  name: '',
  category_id: '',
  manufacturer: '',
  price: '',
  stock: '',
  status: 'active',
  description: '',
  image: null
});

// Handle image file selection
function handleImage(e) {
  form.value.image = e.target.files[0];
}

// Submit form to backend
function submitForm() {
  const payload = new FormData();
  Object.entries(form.value).forEach(([key, value]) => {
    if (value !== null) payload.append(key, value);
  });
  router.post(route('admin.products.store'), payload, {
    forceFormData: true
  });
}
</script>

<!-- ErrorMsg is a simple component that displays a validation error for a field -->
<!-- Example: <ErrorMsg :error="errors.name" /> -->

<style scoped>
</style>
