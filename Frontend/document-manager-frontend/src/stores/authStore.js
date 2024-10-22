import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null); // Store user data
  const token = ref(null); // Store token
  const isAuthenticated = ref(false); // Track authentication status

  // Login method to store user data and token
  const login = (userData, userToken) => {
    user.value = userData;
    token.value = userToken;
    isAuthenticated.value = true;

    // Save to localStorage to persist data across reloads
    localStorage.setItem('user', JSON.stringify(userData));
    localStorage.setItem('token', userToken);
  };

  // Logout method to clear user data and token
  const logout = () => {
    user.value = null;
    token.value = null;
    isAuthenticated.value = false;

    // Clear from localStorage
    localStorage.removeItem('user');
    localStorage.removeItem('token');
  };

  // Initialize the store from localStorage
  const initAuth = () => {

    if(localStorage.getItem('user')  && localStorage.getItem('token')) {
      const storedUser = JSON.parse(localStorage.getItem('user'));
      const storedToken = localStorage.getItem('token');
      user.value = storedUser;
      token.value = storedToken;
      isAuthenticated.value = true;
    }
  };

  return { user, token, isAuthenticated, login, logout , initAuth };
});
