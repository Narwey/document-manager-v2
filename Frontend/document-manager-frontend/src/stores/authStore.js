import { defineStore } from 'pinia';
import { useRouter } from 'vue-router';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null); 
  const token = ref(null); 
  const isAuthenticated = ref(false);
  const router = useRouter(); 

  
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

    router.push('/');
  };

  // Initialize the store from localStorage
  const initAuth = () => {
    const storedUser = localStorage.getItem('user');
    const storedToken = localStorage.getItem('token');

    if (storedUser && storedToken) {
      user.value = JSON.parse(storedUser);
      token.value = storedToken;
      isAuthenticated.value = true;
    }
  };

  return { user, token, isAuthenticated, login, logout, initAuth };
});
