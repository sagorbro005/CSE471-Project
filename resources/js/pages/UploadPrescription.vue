<template>
  <NavBar />
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Side - Upload Section -->
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Upload Prescription</h2>
                    <p class="text-gray-600 mb-8">Please upload images of valid prescriptions from your doctor (up to 5 files).</p>

                    <!-- Success Message -->
                    <div v-if="successMessage" class="bg-green-50 text-green-700 p-4 rounded-lg mb-6">
                        {{ successMessage }}
                    </div>

                    <!-- Error Message -->
                    <div v-if="errorMessage" class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                        {{ errorMessage }}
                    </div>

                    <form @submit.prevent="handleSubmit">
                        <!-- Upload Section -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Prescription Images (Max 5)
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors cursor-pointer"
                                 @click="$refs.fileInput.click()"
                                 @dragover.prevent
                                 @drop.prevent="handleDrop">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <span class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            Upload files
                                        </span>
                                        <input ref="fileInput"
                                               type="file"
                                               class="sr-only"
                                               accept="image/*"
                                               multiple
                                               @change="handleFiles">
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB each</p>
                                </div>
                            </div>
                            <p v-if="fileError" class="mt-2 text-sm text-red-600">{{ fileError }}</p>
                        </div>

                        <!-- Preview Grid -->
                        <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 mt-4">
                            <div v-for="(preview, index) in previews"
                                 :key="index"
                                 class="relative group">
                                <img :src="preview"
                                     class="h-24 w-24 object-cover rounded-lg border border-gray-200"
                                     alt="Preview">
                                <button @click="removeFile(index)"
                                        type="button"
                                        class="absolute -top-2 -right-2 hidden group-hover:block bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Additional Notes (Optional)
                            </label>
                            <textarea id="notes"
                                      v-model="notes"
                                      rows="3"
                                      class="shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md"
                                      placeholder="Any special instructions or notes for your order..."></textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit"
                                    :disabled="isSubmitting"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                {{ isSubmitting ? 'Uploading...' : 'Place Order' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Side - Guidelines -->
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Guide for a valid prescription</h2>
                    <ul class="space-y-4">
                        <li class="flex items-center text-gray-700">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Clear and readable image of the prescription
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Doctor's signature must be visible
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Prescription date should be visible
                        </li>
                        <li class="flex items-center text-gray-700">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Patient details must be clearly mentioned
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<script setup>
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

// Reactive state
const files = ref([]);
const previews = ref([]);
const notes = ref('');
const successMessage = ref('');
const errorMessage = ref('');
const fileError = ref('');
const isSubmitting = ref(false);
const fileInput = ref(null);

// Handle file selection from input
const handleFiles = (event) => {
    const newFiles = event.target?.files || event;
    addFiles(newFiles);
};

// Handle drag and drop
const handleDrop = (event) => {
    addFiles(event.dataTransfer.files);
};

// Add files to the list
const addFiles = (newFiles) => {
    if (files.value.length + newFiles.length > 5) {
        fileError.value = 'You can only upload up to 5 files';
        return;
    }

    Array.from(newFiles).forEach(file => {
        // Check file type
        if (!file.type.startsWith('image/')) {
            fileError.value = 'Only image files are allowed';
            return;
        }

        // Check file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            fileError.value = 'Files must be less than 2MB';
            return;
        }

        // Create preview URL
        const reader = new FileReader();
        reader.onload = (e) => {
            previews.value.push(e.target.result);
        };
        reader.readAsDataURL(file);

        // Add file to files array
        files.value.push(file);
    });

    // Clear any previous error if successful
    if (files.value.length <= 5) {
        fileError.value = '';
    }
};

// Remove file from the list
const removeFile = (index) => {
    files.value.splice(index, 1);
    previews.value.splice(index, 1);
    fileError.value = '';
};

// Handle form submission
const handleSubmit = async () => {
    if (files.value.length === 0) {
        errorMessage.value = 'Please upload at least one prescription';
        return;
    }

    isSubmitting.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    try {
        const formData = new FormData();
        files.value.forEach(file => {
            formData.append('prescriptions[]', file);
        });
        formData.append('notes', notes.value);

        router.post(route('prescription.upload'), formData, {
            onSuccess: () => {
                successMessage.value = 'Prescription uploaded successfully! One Medimart representative will call you shortly for confirming this order.';
                files.value = [];
                previews.value = [];
                notes.value = '';
            },
            onError: (errors) => {
                errorMessage.value = Object.values(errors)[0] || 'Failed to upload prescription. Please try again.';
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    } catch (error) {
        errorMessage.value = 'Failed to upload prescription. Please try again.';
        isSubmitting.value = false;
    }
};
</script>
