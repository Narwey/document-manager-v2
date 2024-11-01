<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-md w-full space-y-8">
        <div>
          <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Sign in to your account</h2>
        </div>
  
        <form class="mt-8 space-y-6" @submit.prevent="login">
          <div class="rounded-md shadow-sm space-y-4">
            <div>
              <label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
              <input
                id="email-address"
                v-model="form.email"
                type="email"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Email address"
              />
            </div>
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Password"
              />
            </div>
          </div>
  
          <div v-if="error" class="text-red-500 text-sm mt-2">{{ error }}</div>
  
          <div>
            <button
              type="submit"
              :disabled="loading"
              class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
            >
              {{ loading ? 'Signing in...' : 'Sign in' }}
            </button>
          </div>
        </form>
  
        <div class="text-sm text-center">
          <span class="text-gray-600">Don't have an account?</span>
          <router-link to="/register" class="font-medium text-green-600 hover:text-green-500 ml-1">Sign up now</router-link>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Swal from 'sweetalert2';
import axios from '../../axios';
  import { useAuthStore } from '../../stores/authStore';
  
  export default {
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
        loading: false,
        error: null,
      };
    },
    methods: {
      async login() {
        this.loading = true;
        this.error = null;

        const authStore = useAuthStore(); 
  
        try {
          const response = await axios.post('/login', {
            email: this.form.email,
            password: this.form.password,
          });
  
          const { user, token } = response.data;

        
            
              authStore.login(user, token);
              this.$router.push('/');
            
          
           
  
          
        } catch (error) {
          
          if (error.response && error.response.data.message) {
            this.error = error.response.data.message;
          } else {
            this.error = 'Login failed. Please try again.';
          }
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>  