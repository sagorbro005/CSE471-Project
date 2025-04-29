<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

const props = defineProps({
    status: {
        type: String,
        default: ''
    }
})

const form = useForm({
    email: ''
})

const submit = () => {
    form.post(route('password.email'))
}
</script>

<template>
    <GuestLayout>
        <div class="w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Forgot Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
            </div>

            <div v-if="status" class="mb-4 text-sm text-center font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        v-model="form.email"
                        type="email" 
                        required
                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">
                        {{ form.errors.email }}
                    </div>
                </div>

                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <span v-if="form.processing">Processing...</span>
                    <span v-else>Send Reset Link</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a 
                    href="/login"
                    class="text-sm text-blue-600 hover:text-blue-500"
                >
                    Back to Login
                </a>
            </div>
        </div>
    </GuestLayout>
</template>
