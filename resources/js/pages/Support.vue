<template>
  <div class="support-bg min-h-screen flex flex-col items-center justify-center py-8 px-2">
    <!-- Header Section -->
    <div class="w-full max-w-3xl text-center mb-8">
      <h1 class="text-3xl md:text-4xl font-bold text-indigo-600 mb-2">How Can We Help You?</h1>
      <p class="text-gray-600">We're here to help and answer any question you might have</p>
    </div>

    <!-- Info Cards -->
    <div class="w-full max-w-3xl grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
        <i class="fas fa-phone text-blue-500 text-2xl mb-2"></i>
        <span class="font-semibold text-gray-700 mb-1">Call Us</span>
        <span class="text-sm text-gray-500">+88-01711008229</span>
      </div>
      <div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
        <i class="fas fa-envelope text-purple-500 text-2xl mb-2"></i>
        <span class="font-semibold text-gray-700 mb-1">Email Us</span>
        <span class="text-sm text-gray-500">support@medimart.com</span>
      </div>
      <div class="bg-white rounded-lg shadow p-5 flex flex-col items-center">
        <i class="fas fa-clock text-pink-500 text-2xl mb-2"></i>
        <span class="font-semibold text-gray-700 mb-1">Working Hours</span>
        <span class="text-sm text-gray-500">24/7 Support</span>
      </div>
    </div>

    <!-- Complaint Form -->
    <div class="w-full max-w-3xl bg-white rounded-lg shadow p-8">
      <form @submit.prevent="submitForm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Name</label>
            <input v-model="form.name" id="name" type="text" class="input" placeholder="Your name" required />
            <p v-if="localErrors.name" class="text-xs text-red-500 mt-1">{{ localErrors.name }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
            <input v-model="form.email" id="email" type="email" class="input" placeholder="your.email@example.com" required />
            <p v-if="localErrors.email" class="text-xs text-red-500 mt-1">{{ localErrors.email }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">Phone Number</label>
            <input v-model="form.phone" id="phone" type="text" class="input" placeholder="Your phone number" required />
            <p v-if="localErrors.phone" class="text-xs text-red-500 mt-1">{{ localErrors.phone }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" for="subject">Subject</label>
            <input v-model="form.subject" id="subject" type="text" class="input" placeholder="What is this about?" required @focus="clearFieldError('subject')" />
            <p v-if="localErrors.subject" class="text-xs text-red-500 mt-1">{{ localErrors.subject }}</p>
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1" for="message">Message</label>
          <textarea v-model="form.message" id="message" rows="4" class="input" placeholder="How can we help you?" required></textarea>
          <p v-if="localErrors.message" class="text-xs text-red-500 mt-1">{{ localErrors.message }}</p>
        </div>
        <div class="flex justify-end">
          <button :disabled="processing" type="submit" class="px-6 py-2 rounded bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition disabled:opacity-70">
            <span v-if="processing">Sending...</span>
            <span v-else>Send Message</span>
          </button>
        </div>
        <div v-if="successMessage" class="mt-4 text-green-600 font-medium text-center">{{ successMessage }}</div>
      </form>
    </div>
  </div>
</template>

<script setup>
// Import Vue and Inertia helpers
import { ref, computed, watch } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

// Access Inertia page props for validation errors and flash messages
const page = usePage()
const errors = computed(() => page.props.errors || {})
const successMessage = ref('')

// Form state
const form = useForm({
  name: '',
  email: '',
  phone: '',
  subject: '',
  message: ''
})

// Track submission state
const processing = ref(false)

// Local error state to clear field errors on focus
const localErrors = ref({ ...errors.value })

// Watch for server errors and update localErrors
watch(
  () => errors.value,
  (val) => {
    localErrors.value = { ...val }
  }
)

// Watch for Inertia success flash and show message
watch(
  () => page.props.flash && page.props.flash.success,
  (val) => {
    if (val) {
      successMessage.value = val
      setTimeout(() => {
        successMessage.value = ''
      }, 2500)
    }
  }
)

function clearFieldError(field) {
  if (localErrors.value[field]) {
    localErrors.value[field] = null
  }
}

// Submit handler
function submitForm() {
  processing.value = true
  form.post(route('support.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    },
    onFinish: () => {
      processing.value = false
    }
  })
}
</script>

<style scoped>
.support-bg {
  background: linear-gradient(135deg, #f8fafc 0%, #f3e8ff 100%);
}
.input {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  border: 1px solid #d1d5db;
  box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  transition: border-color 0.2s;
  font-size: 1rem;
  outline: none;
}
.input:focus {
  border-color: #6366f1;
}
</style>
