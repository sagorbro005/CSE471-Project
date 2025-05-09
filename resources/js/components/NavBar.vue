<template>
  <nav class="navbar-black">
    <div class="container mx-auto px-0">
      <div class="flex justify-between h-16">
        <div class="flex items-center pl-2">
          <Link :href="'/'" class="flex-shrink-0 flex items-center">
            <img src="/logo/Medimart.png" alt="MediMart Logo" class="h-35">
          </Link>
        </div>
        <div class="flex items-center space-x-8">
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
    </div>
  </nav>
</template>

<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const page = usePage();
const cartCount = computed(() => page.props.cartCount || 0);

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

// Add event listener for clicks outside the dropdown
onMounted(() => {
  document.addEventListener('click', closeAccountMenu);
});

onUnmounted(() => {
  document.removeEventListener('click', closeAccountMenu);
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
  height: 70px;
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
</style>
