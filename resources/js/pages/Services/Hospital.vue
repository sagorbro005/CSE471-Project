<template>
  <NavBar />
  <div class="py-12 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
          Hospital Information
        </h1>
        <p class="mt-2 text-gray-600">Find the right healthcare facility and medical professionals</p>
      </div>

      <!-- Search Box -->
      <div class="max-w-2xl mx-auto mb-12">
        <div class="relative">
          <input type="text" v-model="searchQuery"
                 class="w-full pl-12 pr-4 py-3 rounded-full border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                 placeholder="Search for a hospital, doctor, or department..."
                 @input="handleSearch">
          <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
            <i class="fas fa-search"></i>
          </div>
        </div>
        <div v-if="searchQuery && !hasResults" class="mt-4 text-center text-gray-600">
          No results found for "{{ searchQuery }}"
        </div>
      </div>

      <!-- Hospital List -->
      <div class="space-y-8">
        <div v-for="hospital in filteredHospitals" :key="hospital.id" class="bg-white rounded-xl shadow-lg overflow-hidden">
          <!-- Hospital Header -->
          <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-500">
            <h2 class="text-2xl font-bold text-white">{{ hospital.name }}</h2>
            <div class="mt-4 space-y-2 text-blue-50">
              <p class="flex items-center">
                <i class="fas fa-map-marker-alt w-6"></i>
                {{ hospital.address }}
              </p>
              <p class="flex items-center">
                <i class="fas fa-phone-alt w-6"></i>
                <span class="px-3 py-1 border border-white rounded-full text-sm text-white">
                  {{ hospital.hotline }}
                </span>
              </p>
            </div>
          </div>

          <!-- Doctors List for this Hospital -->
          <div class="p-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-lg overflow-hidden">
              <div class="p-6 bg-gradient-to-r from-blue-500 to-indigo-500">
                <h2 class="text-2xl font-bold text-white flex items-center">
                  <i class="fas fa-user-md mr-3"></i>
                  Dedicated Doctors Team
                </h2>
              </div>

              <!-- Doctors Grid -->
              <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div v-for="doctor in filteredDoctors(hospital)" :key="doctor.id"
                     class="doctor-card bg-gradient-to-br from-white to-blue-50 rounded-lg p-6 hover:shadow-md transition-all duration-300 transform hover:scale-105"
                     :data-name="doctor.name.toLowerCase()">
                  <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-user-md text-white text-xl"></i>
                    </div>
                    <div class="ml-4">
                      <h3 class="font-semibold text-gray-900">{{ doctor.name }}</h3>
                      <p class="text-sm text-blue-600">{{ doctor.designation }}</p>
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
                  </div>
                  <a :href="doctor.profileLink" target="_blank" class="block">
                    <button class="mt-4 w-full bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-lg py-2 px-4 hover:from-blue-600 hover:to-indigo-600 transition-colors duration-300">
                      View Profile
                    </button>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<script setup lang="ts">
import { ref } from 'vue';
import NavBar from '@/components/NavBar.vue';
import Footer from '@/components/Footer.vue';

// Define types for our data
interface Doctor {
  id: number;
  name: string;
  designation: string;
  department: string;
  time: string;
  profileLink: string;
}

interface Hospital {
  id: number;
  name: string;
  address: string;
  hotline: string;
  doctors: Doctor[];
}

// Props from controller
const props = defineProps<{
  hospitals: Hospital[];
}>();

// Search functionality
const searchQuery = ref('');
const filteredHospitals = ref<Hospital[]>([]);
const hasResults = ref(true);

// Initialize filtered hospitals with all hospitals
filteredHospitals.value = [...props.hospitals];

// Handle search across hospitals, doctors, and departments
const handleSearch = () => {
  if (!searchQuery.value) {
    // If search is empty, show all hospitals
    filteredHospitals.value = [...props.hospitals];
    hasResults.value = true;
    return;
  }

  const query = searchQuery.value.toLowerCase();

  // Filter hospitals that match the query or have doctors that match the query
  filteredHospitals.value = props.hospitals.filter(hospital => {
    // Check if hospital name matches
    const hospitalMatches = hospital.name.toLowerCase().includes(query) ||
                           hospital.address.toLowerCase().includes(query);

    // Check if any doctors match
    const doctorsMatch = hospital.doctors.some(doctor =>
      doctor.name.toLowerCase().includes(query) ||
      doctor.department.toLowerCase().includes(query) ||
      doctor.designation.toLowerCase().includes(query)
    );

    return hospitalMatches || doctorsMatch;
  });

  // Update hasResults flag
  hasResults.value = filteredHospitals.value.length > 0;
};

// Filter doctors based on search query
const filteredDoctors = (hospital: Hospital) => {
  if (!searchQuery.value) {
    return hospital.doctors;
  }

  const query = searchQuery.value.toLowerCase();
  return hospital.doctors.filter((doctor: Doctor) =>
    doctor.name.toLowerCase().includes(query) ||
    doctor.department.toLowerCase().includes(query) ||
    doctor.designation.toLowerCase().includes(query)
  );
};
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
