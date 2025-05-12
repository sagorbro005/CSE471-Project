<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import WelcomePage from './Welcome.vue';
</script>

<template>
  <!-- Reuses Welcome page but changes the title -->
  <Head title="Dashboard" />
  <WelcomePage />
</template>
