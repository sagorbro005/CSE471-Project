<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

const props = defineProps<Props>();

// Define breadcrumbs for navigation
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

// Get current user data from the page props
const page = usePage<SharedData>();
const user = page.props.auth.user as User;

// Initialize form with user data
const form = useForm({
    name: user.name,
    email: user.email,
    gender: user.gender || '',
    date_of_birth: user.date_of_birth || '',
    phone: user.phone || '',
    address: user.address || ''
});

// Handle form submission
const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            // Form submitted successfully
            form.reset('password');
        },
        onError: (errors) => {
            // Handle any errors
            console.error('Profile update failed:', errors);
        }
    });
};
</script>

<template>
    <div>
        <AppLayout :breadcrumbs="breadcrumbs">
            <Head title="Profile settings" />

            <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your profile information" />

                <!-- Success Message -->
                <div v-if="props.status" class="mb-4 bg-green-50 text-green-600 px-4 py-2 rounded-md text-sm">
                    {{ props.status }}
                </div>

                <!-- Error Message -->
                <div v-if="form.errors.error" class="mb-4 bg-red-50 text-red-600 px-4 py-2 rounded-md text-sm">
                    {{ form.errors.error }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name field -->
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            required
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Email field -->
                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Gender field -->
                    <div class="grid gap-2">
                        <Label for="gender">Gender</Label>
                        <select
                            id="gender"
                            v-model="form.gender"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        <InputError :message="form.errors.gender" />
                    </div>

                    <!-- Date of Birth field -->
                    <div class="grid gap-2">
                        <Label for="date_of_birth">Date of Birth</Label>
                        <Input
                            id="date_of_birth"
                            type="date"
                            v-model="form.date_of_birth"
                        />
                        <InputError :message="form.errors.date_of_birth" />
                    </div>

                    <!-- Phone field -->
                    <div class="grid gap-2">
                        <Label for="phone">Phone Number</Label>
                        <Input
                            id="phone"
                            type="tel"
                            v-model="form.phone"
                            placeholder="+880 1XXX-XXXXXX"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <!-- Address field -->
                    <div class="grid gap-2">
                        <Label for="address">Address</Label>
                        <textarea
                            id="address"
                            v-model="form.address"
                            rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Enter your full address"
                        ></textarea>
                        <InputError :message="form.errors.address" />
                    </div>

                    <!-- Email verification section -->
                    <div v-if="props.mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="props.status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <!-- Submit button and success message -->
                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save Changes</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-green-600">Saved successfully.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <DeleteUser />
            </SettingsLayout>
        </AppLayout>
    </div>
</template>
