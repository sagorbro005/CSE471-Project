<template>
    <!-- Main container with full height and centered content -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- Logo section -->
        <div>
            <img src="/logo/Medimart.png" alt="Medimart" width="170">
        </div>

        <!-- Main content section -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot></slot>
        </div>
    </div>
    <Footer />
</template>

<script setup>
// This is a simple layout component that provides the guest layout structure
// Used by all authentication pages (login, register, forgot password, reset password)
// import Footer from '@/components/Footer.vue';

// Set favicon
const link = document.createElement('link')
link.rel = 'icon'
link.type = 'image/png'
link.href = '/logo/tablet.png'
document.head.appendChild(link)
</script>
