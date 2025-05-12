<script setup lang="ts">
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import AppContent from '@/components/AppContent.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <NavBar />
        <div class="container mx-auto px-4 py-8">
            <Breadcrumbs v-if="props.breadcrumbs.length" :items="props.breadcrumbs" class="mb-4" />
            <AppContent>
                <slot />
            </AppContent>
        </div>
        <Footer class="mt-auto" />
    </div>
</template>
