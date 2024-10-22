<template>
    <nav class="bg-white shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex-shrink-0">
            <router-link to="/" class="text-2xl font-bold text-green-600">DM</router-link>
          </div>
  
          <!-- Burger Menu Icon (Mobile Only) -->
          <div class="sm:hidden">
            <button
              @click="toggleMobileMenu"
              class="text-gray-600 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-green-600 rounded-md"
            >
              <MenuIcon v-if="!mobileMenuOpen" class="h-6 w-6" />
              <XIcon v-else class="h-6 w-6" />
            </button>
          </div>
  
          <!-- Navigation Links (Desktop) -->
          <div class="hidden sm:flex flex-1 justify-center space-x-8">
            <router-link
              v-for="link in navLinks"
              :key="link.to"
              :to="link.to"
              class="text-gray-600 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              {{ link.text }}
            </router-link>
          </div>
  
          <!-- Auth Buttons (Desktop) -->
          <div class="hidden sm:flex items-center space-x-4">
            <template v-if="!authStore.isAuthenticated">
              <router-link
                to="/login"
                class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-green-700 transition-colors"
              >
                Login
              </router-link>
            </template>
            <template v-else>
              <span class="text-gray-600 text-sm font-medium">Hello, {{ authStore.user.firstName }}</span>
              <button
                @click="logout"
                class="bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-green-700 transition-colors"
              >
                Logout
              </button>
            </template>
          </div>
        </div>
      </div>
  
      <!-- Mobile Menu -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="transform scale-95 opacity-0"
        enter-to-class="transform scale-100 opacity-100"
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="transform scale-100 opacity-100"
        leave-to-class="transform scale-95 opacity-0"
      >
        <div v-if="mobileMenuOpen" class="sm:hidden px-2 pt-2 pb-3 space-y-1">
          <router-link
            v-for="link in navLinks"
            :key="link.to"
            :to="link.to"
            class="text-gray-600 hover:text-green-600 block px-3 py-2 rounded-md text-base font-medium transition-colors"
            @click="closeMobileMenu"
          >
            {{ link.text }}
          </router-link>
  
          <!-- Auth Buttons (Mobile) -->
          <div class="pt-4 border-t border-gray-200">
            <template v-if="!authStore.isAuthenticated">
              <router-link
                to="/login"
                class="w-full bg-green-600 text-white px-4 py-2 rounded-full text-center text-sm font-semibold hover:bg-green-700 transition-colors"
                @click="closeMobileMenu"
              >
                Login
              </router-link>
            </template>
            <template v-else>
              <div class="flex items-center justify-between px-5">
                <span class="text-gray-600 text-sm font-medium">Hello, {{ authStore.user.firstName }}</span>
                <button
                  @click="logout"
                  class="ml-auto bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-green-700 transition-colors"
                >
                  Logout
                </button>
              </div>
            </template>
          </div>
        </div>
      </transition>
    </nav>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { useAuthStore } from '@/stores/authStore';
  import { MenuIcon, XIcon } from 'lucide-vue-next';
  
  // Initialize Pinia store
  const authStore = useAuthStore();
  authStore.initAuth(); // Initialize auth state on mount
  
  const mobileMenuOpen = ref(false);
  const navLinks = [
    { to: '/users', text: 'Manage Users' },
    { to: '/categories', text: 'Manage Categories' },
    { to: '/documents', text: 'Manage Documents', },
  ];
  
  
  
  const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
  };
  
  const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
  };
  
  const logout = () => {
    authStore.logout();
    closeMobileMenu();
  };
  </script>
  