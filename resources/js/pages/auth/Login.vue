<script setup>
// Import necessary components
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

// Define props to receive registration success message and email
const props = defineProps({
    registration_success: {
        type: Boolean,
        default: false
    },
    email: {
        type: String,
        default: ''
    }
})

// Create form data using Inertia's useForm
const form = useForm({
    email: props.email, // Pre-fill email if coming from registration
    password: '',
    remember: false
})

// Handle form submission
const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        preserveScroll: true
    })
}
</script>

<template>
    <GuestLayout>
        <!-- Main container -->
        <div class="w-full">

            <!-- Header section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Welcome Back!</h2>
                <p class="mt-2 text-sm text-gray-600">Sign in to your account to continue</p>
            </div>

            <!-- Login form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        :autofocus="!registration_success"
                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        :autofocus="registration_success"
                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                </div>

                <!-- Remember me and Forgot password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a :href="route('password.request')" class="text-sm text-blue-600 hover:text-blue-500">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <span v-if="form.processing">Processing...</span>
                    <span v-else>Sign in</span>
                </button>
            </form>

            <!-- Register link -->
            <div class="mt-6 text-center">
                <div class="text-sm text-gray-500">Not registered?</div>
                <a
                    :href="route('register')"
                    class="mt-3 block w-full text-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    Create an account
                </a>
            </div>
        </div>
    </GuestLayout>
</template>
