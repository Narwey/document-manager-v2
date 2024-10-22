import { createWebHistory, createRouter } from 'vue-router';

// Import your views
import Landing from '../views/Landing.vue';
import Login from '../views/Auth/Login.vue';
import Register from '../views/Auth/Register.vue';
import ManageUsers from '../views/ManageUsers.vue';

const routes = [
  { path: '/', component: Landing },
  { path: '/login', component:  Login },
  { path: '/register', component:  Register },
  { path: '/users', component:  ManageUsers },

];

// Create the router
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Add the navigation guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token'); // Check for token
  if (to.meta.requiresAuth && !token) {
    next('/login'); // Redirect to login if not authenticated
  } else {
    next(); // Proceed to the route
  }
});

export default router;
