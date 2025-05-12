<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import NavBar from '@/components/NavBar.vue';
import HeroSlider from '@/components/HeroSlider.vue';
import CategoryGrid from '@/components/CategoryGrid.vue';
import PromoSection from '@/components/PromoSection.vue';
import Footer from '@/components/Footer.vue';
</script>

<template>
  <Head title="Welcome" />
  <div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <NavBar />
    <main class="flex flex-col items-center w-full">
      <section class="w-full">
        <HeroSlider />
      </section>
      <section class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Shop by Category</h2>
        <CategoryGrid />
      </section>
      <PromoSection />
    </main>
    <Footer />
  </div>
</template>
