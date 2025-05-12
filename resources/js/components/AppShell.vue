<script setup lang="ts">
interface Props {
    class?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <div class="flex min-h-screen w-full flex-col" :class="props.class">
        <slot />
    </div>
</template>
