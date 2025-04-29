<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/layouts/GuestLayout.vue'

interface Props {
    token: string;
    email: string;
}

const props = defineProps<Props>()

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: ''
})

const submit = () => {
    form.post(route('password.store'), {
        onSuccess: () => {
            form.reset('password', 'password_confirmation')
        }
    })
}
</script>

<template>
    <GuestLayout>
        <div class="w-full">
            <!-- Header section -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Please enter your new password
                </p>
            </div>

            <!-- Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Email field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        v-model="form.email"
                        type="email" 
                        required
                        readonly
                        class="mt-1 block w-full px-3 py-2 bg-gray-50 border border-gray-300 rounded-md text-sm shadow-sm"
                    />
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        New Password
                    </label>
                    <input 
                        id="password" 
                        v-model="form.password"
                        type="password" 
                        required
                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">
                        {{ form.errors.password }}
                    </div>
                </div>

                <!-- Confirm Password field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm New Password
                    </label>
                    <input 
                        id="password_confirmation" 
                        v-model="form.password_confirmation"
                        type="password" 
                        required
                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                        {{ form.errors.password_confirmation }}
                    </div>
                </div>

                <!-- Submit button -->
                <button 
                    type="submit"
                    :disabled="form.processing"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <span v-if="form.processing">Processing...</span>
                    <span v-else>Reset Password</span>
                </button>
            </form>

            <!-- Back to login -->
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
