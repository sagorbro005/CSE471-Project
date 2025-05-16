<template>
  <div>
    <!-- Slider Section -->
    <div class="relative overflow-hidden">
      <!-- Slides -->
      <div class="relative h-[200px] sm:h-[250px] md:h-[300px] lg:h-[450px] w-full overflow-hidden">
        <transition-group name="fade" tag="div">
          <div v-for="(slide, idx) in slides" :key="slide.id" v-show="activeSlide === idx"
            class="absolute inset-0 transition-opacity duration-700">
            <img :src="getImageUrl(slide.image)" :alt="`Slide ${slide.id}`" class="w-full h-full object-cover object-center">
          </div>
        </transition-group>
      </div>
      <!-- Arrow Navigation -->
      <div class="absolute inset-y-0 left-0 z-10 flex items-center">
        <button @click="prev" class="bg-black/30 hover:bg-black/50 text-white w-10 h-10 md:w-14 md:h-14 flex items-center justify-center rounded-r-xl transition-all duration-300 hover:w-12 md:hover:w-16">
          <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
          </svg>
        </button>
      </div>
      <div class="absolute inset-y-0 right-0 z-10 flex items-center">
        <button @click="next" class="bg-black/30 hover:bg-black/50 text-white w-10 h-10 md:w-14 md:h-14 flex items-center justify-center rounded-l-xl transition-all duration-300 hover:w-12 md:hover:w-16">
          <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
          </svg>
        </button>
      </div>
      <!-- Dot Navigation -->
      <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-4 z-10">
        <button v-for="(slide, idx) in slides" :key="slide.id"
          @click="activeSlide = idx"
          class="group relative p-2">
          <span :class="{'bg-white scale-100': activeSlide === idx, 'bg-white/50 scale-75': activeSlide !== idx}"
            class="block w-3 h-3 rounded-full transition-all duration-300 transform group-hover:scale-100"></span>
        </button>
      </div>
    </div>

    <!-- Welcome Banner Section -->
    <div class="welcome-banner w-full py-12 md:py-16 lg:py-24 px-4 text-center text-white">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4">
        Welcome to <span class="text-yellow-400">MediMart</span>
      </h1>
      <p class="text-base md:text-lg lg:text-xl max-w-2xl mx-auto mb-6 md:mb-8">
        Your One-Stop Health and Wellness Shop. We provide quality medicines and
        healthcare products right at your doorstep.
      </p>
      <Link :href="'/products'" class="inline-block bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-6 md:py-3 md:px-8 rounded-full transition-colors duration-300">
        Browse Products <span class="ml-2">→</span>
      </Link>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { imageHelper } from '@/mixins/ImageHelper.js';

// Add image helper methods
const { getImageUrl } = imageHelper.methods;

const slides = [
  { id: 1, image: '/images/slider/slide1.JPG' },
  { id: 2, image: '/images/slider/slide2.JPG' },
  { id: 3, image: '/images/slider/slide3.JPG' },
];
const activeSlide = ref(0);
let interval: number | undefined;

function prev() {
  activeSlide.value = activeSlide.value === 0 ? slides.length - 1 : activeSlide.value - 1;
  resetInterval();
}
function next() {
  activeSlide.value = activeSlide.value === slides.length - 1 ? 0 : activeSlide.value + 1;
  resetInterval();
}

function resetInterval() {
  if (interval) window.clearInterval(interval);
  interval = window.setInterval(next, 5000);
}

onMounted(() => {
  // Start auto-sliding
  interval = window.setInterval(next, 5000);
});

onUnmounted(() => {
  if (interval) window.clearInterval(interval);
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.7s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.welcome-banner {
  background: linear-gradient(90deg, #4338ca, #a855f7, #ec4899);
  position: relative;
  overflow: hidden;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
</style>
