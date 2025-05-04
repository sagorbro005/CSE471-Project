<template>
  <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-12">
    <div class="text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 text-purple-500 mb-6">
        <i class="fas fa-box-open text-3xl"></i>
      </div>
      <h3 class="text-2xl font-semibold text-gray-900 mb-3">No Orders Yet</h3>
      <p class="text-gray-600 mb-8 max-w-md mx-auto">Your order history is currently empty. Start shopping to see your orders appear here!</p>
      <div class="space-y-4">
        <Link :href="route('products.index')" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 hover:from-blue-600 hover:via-purple-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-300">
          <i class="fas fa-shopping-cart mr-2"></i>
          Browse Products
        </Link>
        <div class="mt-6 text-sm text-gray-500">
          <p>Need help with your order? <Link :href="route('support.index')" class="text-blue-600 hover:underline">Contact Support</Link></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
</script>
