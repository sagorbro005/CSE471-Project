<template>
  <div class="py-12 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
          Our Services
        </h1>
        <p class="mt-2 text-gray-600">Comprehensive healthcare solutions at your fingertips</p>
      </div>

      <!-- Emergency Ambulance Section -->
      <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-xl shadow-lg overflow-hidden mb-8">
        <div class="p-8 text-center">
          <div class="text-white mb-4">
            <i class="fas fa-ambulance text-5xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-white mb-4">Emergency Ambulance Service</h2>
          <div class="inline-flex items-center space-x-2 border-2 border-white rounded-full px-6 py-3">
            <i class="fas fa-phone-alt text-white"></i>
            <span class="text-xl font-bold text-white">{{ ambulanceHotline }}</span>
          </div>
        </div>
      </div>

      <!-- Services Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Hospital Information Card -->
        <Link :href="route('services.hospital')" class="block group">
          <div
            class="bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 group-hover:scale-105">
            <div class="p-8 text-white">
              <div class="text-white mb-4 transform transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-hospital text-4xl"></i>
              </div>
              <h3 class="text-xl font-semibold mb-2">Hospital Information</h3>
              <p class="text-emerald-100">Explore hospitals, find doctors, and get detailed information about healthcare
                facilities.</p>
              <div class="mt-6 flex items-center text-white">
                <span class="text-sm font-medium">Learn More</span>
                <i class="fas fa-arrow-right ml-2 transform transition-transform duration-300 group-hover:translate-x-2"></i>
              </div>
            </div>
          </div>
        </Link>

        <!-- Telemedicine Card -->
        <Link :href="route('services.telemedicine')" class="block group">
          <div
            class="bg-gradient-to-br from-indigo-400 via-purple-500 to-violet-600 rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 group-hover:scale-105">
            <div class="p-8 text-white">
              <div class="text-white mb-4 transform transition-transform duration-300 group-hover:scale-110">
                <i class="fas fa-video text-4xl"></i>
              </div>
              <h3 class="text-xl font-semibold mb-2">Telemedicine</h3>
              <p class="text-indigo-100">Connect with healthcare professionals from the comfort of your home through video
                consultations.</p>
              <div class="mt-6 flex items-center text-white">
                <span class="text-sm font-medium">Learn More</span>
                <i class="fas fa-arrow-right ml-2 transform transition-transform duration-300 group-hover:translate-x-2"></i>
              </div>
            </div>
          </div>
        </Link>
      </div>

      <!-- Contact Information -->
      <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center text-blue-500 mb-4">
            <i class="fas fa-phone-alt mr-3"></i>
            <h4 class="text-lg font-semibold text-gray-900">24/7 Support</h4>
          </div>
          <p class="text-gray-600">{{ supportPhone }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6">
          <div class="flex items-center text-purple-500 mb-4">
            <i class="fas fa-envelope mr-3"></i>
            <h4 class="text-lg font-semibold text-gray-900">Email Support</h4>
          </div>
          <p class="text-gray-600">{{ supportEmail }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

// Props received from the controller
defineProps<{
  ambulanceHotline: string;
  supportPhone: string;
  supportEmail: string;
}>();
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
