<template>
  <div class="py-12 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
          Telemedicine Service
        </h1>
        <p class="mt-2 text-gray-600">Connect with healthcare professionals from the comfort of your home</p>
      </div>

      <!-- Telemedicine Hotline -->
      <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg overflow-hidden mb-12">
        <div class="p-8 text-center">
          <div class="text-white mb-4">
            <i class="fas fa-video text-5xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-white mb-4">24/7 Online Consultation</h2>
          <div class="inline-flex items-center space-x-2 border-2 border-white rounded-full px-6 py-3">
            <i class="fas fa-phone-alt text-white"></i>
            <span class="text-xl font-bold text-white">{{ telemedicineHotline }}</span>
          </div>
        </div>
      </div>

      <!-- Benefits Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
          <div class="text-purple-500 mb-4">
            <i class="fas fa-home text-2xl"></i>
          </div>
          <h3 class="font-semibold text-gray-800 mb-2">Consult from Home</h3>
          <p class="text-gray-600">Get medical advice without leaving your home</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
          <div class="text-blue-500 mb-4">
            <i class="fas fa-clock text-2xl"></i>
          </div>
          <h3 class="font-semibold text-gray-800 mb-2">24/7 Availability</h3>
          <p class="text-gray-600">Access healthcare services any time</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300">
          <div class="text-pink-500 mb-4">
            <i class="fas fa-user-md text-2xl"></i>
          </div>
          <h3 class="font-semibold text-gray-800 mb-2">Expert Doctors</h3>
          <p class="text-gray-600">Connect with qualified healthcare professionals</p>
        </div>
      </div>

      <!-- Available Doctors -->
      <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-500">
          <h2 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-user-md mr-3"></i>
            Available Doctors
          </h2>
        </div>

        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="doctor in telemedicineDoctors" :key="doctor.id"
                 class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition-all duration-300 transform hover:scale-105">
              <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                  <i class="fas fa-user-md text-white text-xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="font-semibold text-gray-900">{{ doctor.name }}</h3>
                  <p class="text-sm text-purple-600">{{ doctor.designation }}</p>
                </div>
              </div>
              <div class="space-y-2 text-sm text-gray-600">
                <p class="flex items-center">
                  <i class="fas fa-stethoscope w-5 text-gray-400"></i>
                  <span class="font-medium">Department:</span>
                  <span class="ml-1">{{ doctor.department }}</span>
                </p>
                <p class="flex items-center">
                  <i class="fas fa-clock w-5 text-gray-400"></i>
                  <span class="font-medium">Consultation Time:</span>
                  <span class="ml-1">{{ doctor.time }}</span>
                </p>
                <p class="flex items-center">
                  <i class="fas fa-tags w-5 text-gray-400"></i>
                  <span class="font-medium">Consultation Fee:</span>
                  <span class="ml-1 text-green-600 font-medium">৳{{ doctor.fee }}</span>
                </p>
              </div>
              <a :href="doctor.profileLink" target="_blank" class="block">
                <button class="mt-4 w-full bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg py-2 px-4 hover:from-purple-600 hover:to-pink-600 transition-colors duration-300">
                  Schedule Consultation
                </button>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- How It Works -->
      <div class="mt-12">
        <h2 class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
          How It Works
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="bg-white p-6 rounded-xl text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-blue-600 font-bold">1</span>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Book Appointment</h3>
            <p class="text-sm text-gray-600">Select your preferred doctor and time slot</p>
          </div>
          <div class="bg-white p-6 rounded-xl text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-purple-600 font-bold">2</span>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Make Payment</h3>
            <p class="text-sm text-gray-600">Complete the payment process</p>
          </div>
          <div class="bg-white p-6 rounded-xl text-center">
            <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-pink-600 font-bold">3</span>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Get Confirmation</h3>
            <p class="text-sm text-gray-600">Receive appointment details via email/SMS</p>
          </div>
          <div class="bg-white p-6 rounded-xl text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <span class="text-blue-600 font-bold">4</span>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Join Consultation</h3>
            <p class="text-sm text-gray-600">Connect with your doctor via video call</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Define types for our data
interface TelemedicineDoctor {
  id: number;
  name: string;
  department: string;
  time: string;
  fee: number;
  profileLink: string;
  designation: string;
}

// Props from controller
defineProps<{
  telemedicineDoctors: TelemedicineDoctor[];
  telemedicineHotline: string;
}>();
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
