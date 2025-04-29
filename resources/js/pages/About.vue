<template>
  <div>
    <!-- Hero Section with Gradient -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-purple-500 to-pink-500">
      <div class="absolute inset-0 bg-pattern opacity-10"></div>
      <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
          <div class="pt-10 mx-auto max-w-7xl px-4 sm:pt-12 sm:px-6 md:pt-16 lg:pt-20 lg:px-8 xl:pt-28">
            <div class="sm:text-center lg:text-left">
              <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl animate-fade-in-up">
                <span class="block">Welcome to</span>
                <span class="block text-transparent bg-clip-text bg-gradient-to-r from-yellow-200 to-yellow-400">
                  MediMart
                </span>
              </h1>
              <p class="mt-3 text-base text-blue-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0 animate-fade-in-up-delay">
                Your One-Stop Health and Wellness Shop. We're committed to providing quality medicines and healthcare products with reliable service.
              </p>
              <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start animate-fade-in-up-delay-2">
                <a href="#journey" class="rounded-full px-8 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 md:py-4 md:text-lg md:px-10 transition transform hover:scale-105">
                  Our Journey
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full transform hover:scale-105 transition-transform duration-500"
             :src="'/images/about/pharmacy.jpg'"
             alt="MediMart Pharmacy">
      </div>
    </div>

    <!-- Our Journey Section with Timeline -->
    <div id="journey" class="py-16 bg-gradient-to-b from-white to-blue-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 sm:text-4xl">
            Our Journey
          </h2>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            From a small pharmacy to Bangladesh's leading online medicine delivery platform
          </p>
        </div>
        <div class="mt-10">
          <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">
            <!-- Journey Items with Hover Effects -->
            <div v-for="(milestone, index) in milestones" :key="index" class="relative group">
              <div :class="milestone.iconBg" class="absolute flex items-center justify-center h-16 w-16 rounded-full text-white transform transition-all duration-300 group-hover:scale-110">
                <i :class="milestone.icon" class="text-2xl"></i>
              </div>
              <div class="ml-20 p-6 bg-white rounded-xl shadow-xl transform transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-2xl">
                <p class="text-lg leading-6 font-medium text-gray-900">{{ milestone.year }}</p>
                <p class="mt-2 text-base text-gray-500">{{ milestone.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Our Mission & Values with Gradient Cards -->
    <div class="py-16 bg-gradient-to-b from-blue-50 to-purple-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600 sm:text-4xl">
            Our Mission & Values
          </h2>
        </div>
        <div class="mt-10">
          <div class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
            <!-- Mission Card -->
            <div class="group bg-gradient-to-br from-blue-500 to-purple-500 p-1 rounded-2xl transform transition-all duration-300 hover:scale-105">
              <div class="bg-white p-6 rounded-2xl h-full">
                <div class="flex items-center mb-4">
                  <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-14 w-14 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white transform transition-all duration-300 group-hover:scale-110">
                      <i class="fas fa-bullseye text-2xl"></i>
                    </div>
                  </div>
                  <h3 class="ml-4 text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Our Mission</h3>
                </div>
                <p class="text-gray-600 text-lg">
                  To make quality healthcare accessible to everyone through technology and innovation.
                  We strive to provide authentic medicines at affordable prices with the convenience of home delivery.
                </p>
              </div>
            </div>

            <!-- Values Card -->
            <div class="group bg-gradient-to-br from-purple-500 to-pink-500 p-1 rounded-2xl transform transition-all duration-300 hover:scale-105">
              <div class="bg-white p-6 rounded-2xl h-full">
                <div class="flex items-center mb-4">
                  <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-14 w-14 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 text-white transform transition-all duration-300 group-hover:scale-110">
                      <i class="fas fa-heart text-2xl"></i>
                    </div>
                  </div>
                  <h3 class="ml-4 text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Our Values</h3>
                </div>
                <ul class="space-y-4">
                  <li v-for="(value, index) in values" :key="index" class="flex items-center text-lg text-gray-600 transform transition-all duration-300 hover:translate-x-2">
                    <i class="fas fa-check-circle text-purple-500 mr-3 text-xl"></i>
                    {{ value }}
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Team Section -->
    <div class="bg-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 sm:text-4xl">
            Our Team
          </h2>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            Meet the dedicated professionals behind MediMart
          </p>
        </div>
        <div class="mt-12 grid gap-8 md:grid-cols-3 lg:gap-x-12">
          <div v-for="(member, index) in teamMembers" :key="index" class="group">
            <div class="relative transform transition-all duration-300 group-hover:-translate-y-2">
              <div :class="member.gradientClass" class="p-1 rounded-2xl shadow-xl">
                <div class="bg-white p-6 rounded-2xl text-center">
                  <div class="relative mb-4">
                    <img :src="member.image" :alt="member.name" class="w-32 h-32 rounded-full mx-auto object-cover">
                  </div>
                  <h3 class="text-xl font-semibold text-gray-900">{{ member.name }}</h3>
                  <p class="text-gray-500">{{ member.role }}</p>
                  <div class="mt-4 flex justify-center space-x-4">
                    <a :href="member.twitter" target="_blank" class="text-blue-400 hover:text-blue-600 transition-colors">
                      <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a :href="member.linkedin" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors">
                      <i class="fab fa-linkedin text-xl"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Section with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-500 to-pink-500 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
          <!-- Left Side: Contact Information -->
          <div class="space-y-6">
            <h2 class="text-3xl font-bold text-white">Need Help? Contact Us</h2>

            <!-- Hotline -->
            <div class="flex items-center space-x-4">
              <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-phone text-white text-xl"></i>
              </div>
              <div>
                <p class="text-white/70">Hotline</p>
                <p class="text-white text-lg">+88-01711008229</p>
              </div>
            </div>

            <!-- Email -->
            <div class="flex items-center space-x-4">
              <div class="bg-white/20 p-3 rounded-full">
                <i class="fas fa-envelope text-white text-xl"></i>
              </div>
              <div>
                <p class="text-white/70">Email Us</p>
                <p class="text-white text-lg">support@medimart.com</p>
              </div>
            </div>
          </div>

          <!-- Right Side: 24/7 Support Card -->
          <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 text-center transform hover:scale-105 transition-transform duration-300">
            <div class="bg-white/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-headset text-white text-3xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-4">24/7 Customer Support</h3>
            <p class="text-white/90">We're here to help you anytime</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'About',
  data() {
    return {
      milestones: [
        {
          year: '2020 - Founded',
          description: 'Started as a small pharmacy with a vision to make healthcare accessible to all.',
          icon: 'fas fa-store',
          iconBg: 'bg-gradient-to-r from-blue-500 to-purple-500'
        },
        {
          year: '2022 - Digital Transformation',
          description: 'Launched our online platform to reach more customers across Bangladesh.',
          icon: 'fas fa-mobile-alt',
          iconBg: 'bg-gradient-to-r from-purple-500 to-pink-500'
        },
        {
          year: '2024 - Market Leader',
          description: 'Became one of Bangladesh\'s leading online pharmacies with nationwide delivery.',
          icon: 'fas fa-chart-line',
          iconBg: 'bg-gradient-to-r from-pink-500 to-red-500'
        }
      ],
      values: [
        'Quality & Authenticity',
        'Customer First',
        'Innovation',
        'Integrity & Trust'
      ],
      teamMembers: [
        {
          name: 'Shahadat Sagor',
          role: 'Head of Operations',
          image: '/images/about/team1.jpg',
          gradientClass: 'bg-gradient-to-br from-blue-500 to-purple-500',
          twitter: 'https://twitter.com/sagorbro005',
          linkedin: 'https://linkedin.com/in/drsarahrahman'
        },
        {
          name: 'Zubayer Hassan',
          role: 'Chief Executive Officer',
          image: '/images/about/team2.jpg',
          gradientClass: 'bg-gradient-to-br from-purple-500 to-pink-500',
          twitter: 'https://twitter.com/zubayerhassan',
          linkedin: 'https://linkedin.com/in/zubayerhassan'
        },
        {
          name: 'Israt Ipsit',
          role: 'Customer Relations Manager',
          image: '/images/about/team3.jpg',
          gradientClass: 'bg-gradient-to-br from-pink-500 to-red-500',
          twitter: 'https://twitter.com/isratipsit',
          linkedin: 'https://linkedin.com/in/isratipsit'
        }
      ]
    }
  }
}
</script>

<style scoped>
/* Add any custom styles here */
.animate-fade-in-up {
  animation: fadeInUp 1s ease-out;
}

.animate-fade-in-up-delay {
  animation: fadeInUp 1s ease-out 0.3s;
  animation-fill-mode: backwards;
}

.animate-fade-in-up-delay-2 {
  animation: fadeInUp 1s ease-out 0.6s;
  animation-fill-mode: backwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
