<template>
  <!-- Footer -->
  <footer class="bg-gray-900 text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Company Info -->
        <div>
          <div class="mb-4">
            <img src="/logo/Medimart.png" alt="MediMart Logo" class="h-40">
          </div>
          <p class="text-gray-400 mb-4">Your trusted healthcare partner, providing quality medical products and services.</p>
          <div class="space-y-2">
            <div class="flex items-center">
              <i class="fas fa-phone text-green-500 mr-2"></i>
              <span>Hotline: +88-01711008229</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-envelope text-green-500 mr-2"></i>
              <span>Email: support@medimart.com</span>
            </div>
            <div class="flex items-center">
              <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
              <span>Kha 224 Pragati Sarani, Merul Badda, Dhaka 1212,</span>
            </div>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li><Link href="/about" class="text-gray-400 hover:text-white transition-colors">About Us</Link></li>
            <li><Link href="/services" class="text-gray-400 hover:text-white transition-colors">Our Services</Link></li>
            <li><Link href="/products" class="text-gray-400 hover:text-white transition-colors">Shop</Link></li>
            <li><Link href="/upload-prescription" class="text-gray-400 hover:text-white transition-colors">Upload Prescription</Link></li>
            <li><Link href="/faq" class="text-gray-400 hover:text-white transition-colors">FAQs</Link></li>
            <li><Link href="/blogs" class="text-gray-400 hover:text-white transition-colors">Read Blogs</Link></li>
          </ul>
        </div>

        <!-- Categories -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Categories</h3>
          <ul class="space-y-2">
            <li><Link href="/products?category=medicine" class="text-gray-400 hover:text-white transition-colors">Medicine</Link></li>
            <li><Link href="/products?category=healthcare" class="text-gray-400 hover:text-white transition-colors">Healthcare</Link></li>
            <li><Link href="/products?category=lab-test" class="text-gray-400 hover:text-white transition-colors">Lab Test</Link></li>
            <li><Link href="/products?category=baby-mom-care" class="text-gray-400 hover:text-white transition-colors">Baby & Mom Care</Link></li>
            <li><Link href="/products?category=homecare" class="text-gray-400 hover:text-white transition-colors">Homecare</Link></li>
            <li><Link href="/products?category=food-nutrition" class="text-gray-400 hover:text-white transition-colors">Food & Nutrition</Link></li>
            <li><Link href="/products?category=supplement" class="text-gray-400 hover:text-white transition-colors">Supplement</Link></li>
            <li><Link href="/products?category=beauty" class="text-gray-400 hover:text-white transition-colors">Beauty</Link></li>
          </ul>
        </div>

        <!-- Newsletter -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
          <p class="text-gray-400 mb-4">Subscribe to our newsletter for updates and exclusive offers.</p>
          <form class="space-y-2" @submit.prevent="subscribeNewsletter">
            <input
              v-model="email"
              type="email"
              placeholder="Your email address"
              class="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 focus:outline-none focus:border-green-500"
            >
            <button
              type="submit"
              class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
            >
              Subscribe
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="md:flex md:items-center md:justify-between">
          <div class="text-sm text-gray-400">
            © {{ currentYear }} MediMart. All rights reserved.
          </div>
          <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">Facebook</span>
              <i class="fab fa-facebook-f h-6 w-6"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">Instagram</span>
              <i class="fab fa-instagram h-6 w-6"></i>
            </a>
            <a href="#" class="text-gray-400 hover:text-white">
              <span class="sr-only">Twitter</span>
              <i class="fab fa-twitter h-6 w-6"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const email = ref('');
const currentYear = computed(() => new Date().getFullYear());

const subscribeNewsletter = () => {
  // Handle newsletter subscription
  console.log('Subscribing email:', email.value);
  // Here you would typically make an API call to your backend
  // axios.post('/api/subscribe', { email: email.value })
  //   .then(response => {
  //     alert('Thank you for subscribing!');
  //     email.value = '';
  //   })
  //   .catch(error => {
  //     alert('There was an error. Please try again.');
  //   });

  // For now, just reset the form
  alert('Thank you for subscribing!');
  email.value = '';
};
</script>
