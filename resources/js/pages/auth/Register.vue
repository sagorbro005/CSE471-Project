<template>
    <GuestLayout>
        <!-- Main container -->
        <div class="w-full">
            <!-- Header section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Create an Account</h2>
                <p class="mt-2 text-sm text-gray-600">Your One-Stop Health and Wellness Shop</p>
            </div>

            <!-- Registration form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Full Name
                    </label>
                    <div class="mt-1">
                        <input 
                            id="name" 
                            v-model="form.name"
                            type="text" 
                            required
                            autofocus
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
                </div>

                <!-- Email field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input 
                            id="email" 
                            v-model="form.email"
                            type="email" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input 
                            id="password" 
                            v-model="form.password"
                            type="password" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
                </div>

                <!-- Confirm Password field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>
                    <div class="mt-1">
                        <input 
                            id="password_confirmation" 
                            v-model="form.password_confirmation"
                            type="password" 
                            required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                    </div>
                    <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ form.errors.password_confirmation }}</div>
                </div>

                <!-- Submit button -->
                <div>
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        <span v-if="form.processing">Processing...</span>
                        <span v-else>Create Account</span>
                    </button>
                </div>
            </form>

            <!-- Login link -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Already have an account?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a 
                        :href="route('login')"
                        class="w-full flex justify-center py-2 px-4 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                    >
                        Sign in instead
                    </a>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
// Import necessary components
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

// Create form data using Inertia's useForm
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
})

// Handle form submission
const submit = () => {
    form.post(route('register'), {
        preserveScroll: true,
        onSuccess: () => {
            // Redirect to login page with success message
            router.visit(route('login'), {
                data: { 
                    registration_success: true,
                    email: form.email // Pass email to pre-fill login form
                }
            })
        },
        onError: () => {
            if (form.errors.email) {
                form.reset('password', 'password_confirmation')
            }
        }
    })
}
</script>
