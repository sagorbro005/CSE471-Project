<template>
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <div v-for="cat in categories" :key="cat.title" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
      <div class="aspect-w-1 aspect-h-1 bg-gray-200 overflow-hidden">
        <img :src="cat.image" :alt="cat.title" class="object-cover object-center group-hover:scale-110 transition-transform duration-500">
      </div>
      <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ cat.title }}</h3>
        <p class="text-sm text-gray-600 mb-2">{{ cat.desc }}</p>
        <Link :href="cat.link" class="inline-flex items-center text-green-600 hover:text-green-700">
          Shop Now
          <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
          </svg>
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const categories = [
  { title: 'Medicine', desc: 'All types of medicine', image: '/images/categories/medicine.jpg', link: '/products?category=medicine' },
  { title: 'Healthcare', desc: 'Healthcare essentials', image: '/images/categories/healthcare.jpg', link: '/products?category=healthcare' },
  { title: 'Lab Test', desc: 'Test at home', image: '/images/categories/lab-test.jpg', link: '/products?category=lab-test' },
  { title: 'Wellness', desc: 'Vitamins & supplements', image: '/images/categories/wellness.jpg', link: '/products?category=wellness' },
];
</script>
