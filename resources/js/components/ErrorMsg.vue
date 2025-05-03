<template>
  <div v-if="msg" class="error-msg">
    <slot>{{ msg }}</slot>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';
const props = defineProps({
  msg: {
    type: String,
    default: ''
  }
});
</script>

<style scoped>
.error-msg {
  color: #fff;
  background: #e74c3c;
  padding: 0.75rem 1.25rem;
  border-radius: 5px;
  margin-bottom: 1rem;
  font-size: 1rem;
  box-shadow: 0 2px 8px rgba(231, 76, 60, 0.1);
}
</style>
