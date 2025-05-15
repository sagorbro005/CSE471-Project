<template>
  <nav class="navbar-black">
    <div class="container mx-auto px-4">
      <div class="flex justify-between h-16">
        <!-- Logo section -->  
        <div class="flex items-center">
          <Link :href="'/'" class="flex-shrink-0 flex items-center">
            <img src="/logo/Medimart.png" alt="MediMart Logo" class="h-35">
          </Link>
        </div>
        
        <!-- Mobile menu button -->
        <div class="flex md:hidden items-center">
          <button @click="toggleMobileMenu" class="text-white focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
          </button>
        </div>

        <!-- Desktop navigation - hidden on mobile -->
        <div class="hidden md:flex items-center space-x-4 lg:space-x-8">
          <Link v-for="item in menu" :key="item.title" :href="item.link" class="navbar-link">
            {{ item.title }}
          </Link>
          <Link :href="route('cart.index')" class="navbar-link relative cart-icon">
            <i class="fas fa-shopping-cart text-xl"></i>
            <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
          </Link>
          <div class="relative">
            <div v-if="$page.props.auth.user">
              <button @click.stop="toggleAccountMenu" class="navbar-link flex items-center profile-button">
                <i class="fas fa-user-circle text-xl mr-2"></i>
                <span class="user-name">{{ $page.props.auth.user.name }}</span>
                <i class="fas fa-chevron-down ml-1"></i>
              </button>
              <div v-if="showAccountMenu" class="profile-dropdown">
                <Link :href="route('profile.edit')" class="dropdown-item">
                  <i class="fas fa-cog mr-2"></i> Settings
                </Link>
                <Link :href="route('orders.index')" class="dropdown-item">
                  <i class="fas fa-box mr-2"></i> My Orders
                </Link>
                <Link :href="route('logout')" method="post" as="button" type="button" class="dropdown-item w-full text-left">
                  <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </Link>
              </div>
            </div>
            <div v-else class="flex space-x-2">
              <Link :href="route('login')" class="navbar-link">
                Login
              </Link>
              <Link :href="route('register')" class="navbar-link-btn">
                Register
              </Link>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Mobile menu, toggles based on state -->
      <div v-if="showMobileMenu" class="md:hidden py-3">
        <div class="flex flex-col space-y-3">
          <Link v-for="item in menu" :key="item.title" :href="item.link" class="mobile-nav-link" @click="showMobileMenu = false">
            {{ item.title }}
          </Link>
          <Link :href="route('cart.index')" class="mobile-nav-link relative" @click="showMobileMenu = false">
            <div class="flex items-center">
              <i class="fas fa-shopping-cart mr-2"></i> Cart
              <span v-if="cartCount > 0" class="mobile-cart-badge ml-2">{{ cartCount }}</span>
            </div>
          </Link>
          
          <!-- Mobile Auth Links -->
          <div class="border-t border-gray-700 pt-3 mt-2">
            <div v-if="$page.props.auth.user">
              <div class="text-yellow-400 font-medium mb-2">{{ $page.props.auth.user.name }}</div>
              <Link :href="route('profile.edit')" class="mobile-nav-link" @click="showMobileMenu = false">
                <i class="fas fa-cog mr-2"></i> Settings
              </Link>
              <Link :href="route('orders.index')" class="mobile-nav-link" @click="showMobileMenu = false">
                <i class="fas fa-box mr-2"></i> My Orders
              </Link>
              <Link :href="route('logout')" method="post" as="button" type="button" class="mobile-nav-link w-full text-left" @click="showMobileMenu = false">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </Link>
            </div>
            <div v-else class="flex flex-col space-y-2">
              <Link :href="route('login')" class="mobile-nav-link" @click="showMobileMenu = false">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
              </Link>
              <Link :href="route('register')" class="mobile-nav-link bg-yellow-500 text-gray-900 font-medium" @click="showMobileMenu = false">
                <i class="fas fa-user-plus mr-2"></i> Register
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const page = usePage();
const cartCount = computed(() => page.props.cartCount || 0);

// Mobile menu state
const showMobileMenu = ref(false);

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value;
  // Close account menu if it's open
  if (showMobileMenu.value && showAccountMenu.value) {
    showAccountMenu.value = false;
  }
};

// Account dropdown menu functionality
const showAccountMenu = ref(false);

const toggleAccountMenu = (e) => {
  e.stopPropagation();
  showAccountMenu.value = !showAccountMenu.value;
};

// Close dropdown when clicking outside
const closeAccountMenu = (e) => {
  if (showAccountMenu.value) {
    showAccountMenu.value = false;
  }
};

// Close mobile menu when window resizes to desktop size
const handleResize = () => {
  if (window.innerWidth >= 768 && showMobileMenu.value) { // 768px is md breakpoint in Tailwind
    showMobileMenu.value = false;
  }
};

// Add event listeners
onMounted(() => {
  document.addEventListener('click', closeAccountMenu);
  window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
  document.removeEventListener('click', closeAccountMenu);
  window.removeEventListener('resize', handleResize);
});

// No custom logout handler needed - using Inertia Link component with method="post"

const menu = [
  { title: 'Home', link: '/' },
  { title: 'Products', link: '/products' },
  { title: 'Service', link: '/services' },
  { title: 'Upload Prescription', link: '/upload-prescription' },
  { title: 'Blogs', link: '/blogs' },
  { title: 'Support', link: '/support' },
  { title: 'FAQ', link: '/faq' },
  { title: 'About Us', link: '/about' },
];
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.navbar-black {
  background: #111827;
  border-bottom: 1px solid #222;
  min-height: 70px;
  display: flex;
  align-items: center;
}

.navbar-link {
  color: #fff;
  transition: color 0.2s;
  padding: 0.5rem 0.5rem;
  font-size: 14.5px;
  font-weight: 500;
  text-decoration: none;
  white-space: nowrap;
}

.navbar-link:hover {
  color: yellow;
}

.navbar-link-btn {
  color: #111827;
  background-color: yellow;
  transition: all 0.2s;
  padding: 0.5rem 0.5rem;
  border-radius: 0.375rem;
  font-size: 14px;
  font-weight: 500;
  text-decoration: none;
}

.navbar-link-btn:hover {
  background-color: #e6e600;
}

.cart-icon {
  font-size: 1rem;
  padding: 0.5rem 0.3rem;
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -8px;
  background-color: red;
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
}

.profile-button {
  padding: 0.5rem 0.8rem;
  display: flex;
  align-items: center;
}

.user-name {
  font-size: 14.5px;
  font-weight: 500;
  max-width: 150px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.profile-dropdown {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 0.5rem;
  width: 180px;
  background-color: #1f2937;
  border: 1px solid #374151;
  border-radius: 0.375rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  z-index: 50;
  overflow: hidden;
}

.dropdown-item {
  display: block;
  padding: 0.75rem 1rem;
  color: #f3f4f6;
  font-size: 0.875rem;
  transition: background-color 0.2s;
  text-decoration: none;
  cursor: pointer;
  border: none;
  background: transparent;
  width: 100%;
  text-align: left;
}

.dropdown-item:hover {
  background-color: #374151;
}

.dropdown-item-form {
  margin: 0;
  padding: 0;
}

/* Mobile menu styles */
.mobile-nav-link {
  display: block;
  padding: 0.75rem 0.5rem;
  color: #f3f4f6;
  font-size: 0.95rem;
  transition: background-color 0.2s;
  text-decoration: none;
  border-radius: 0.25rem;
}

.mobile-nav-link:hover {
  background-color: #1f2937;
  color: yellow;
}

.mobile-cart-badge {
  background-color: red;
  color: white;
  border-radius: 9999px;
  padding: 0.1rem 0.5rem;
  font-size: 0.75rem;
  font-weight: bold;
}

/* Make sure content is scrollable on small screens */
@media (max-width: 768px) {
  .navbar-black {
    height: auto;
    padding: 0.75rem 0;
  }
  
  /* Ensure dropdown stays within viewport */
  .profile-dropdown {
    right: 1rem;
    width: calc(100vw - 2rem);
    max-width: 300px;
  }
}
</style>
