import { createWebHistory, createRouter } from 'vue-router';
import Swal from 'sweetalert2'; // Import SweetAlert2 directly
import { useAuthStore } from '../stores/authStore';

// Import your views
import Landing from '../views/Landing.vue';
import Login from '../views/Auth/Login.vue';
import Register from '../views/Auth/Register.vue';
import ManageUsers from '../views/ManageUsers.vue';
import ManageCategories from '../views/ManageCategories.vue';
import ManageDocuments from '@/views/ManageDocuments.vue';

const routes = [
  { path: '/', component: Landing },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/users', component: ManageUsers, meta: { roles: ['admin'] } },
  { path: '/categories', component: ManageCategories, meta: { roles: ['admin'] } },
  { path: '/documents', component: ManageDocuments },
];

// Create the router
const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const userRole = authStore.user?.role;

  if (to.meta.roles && !to.meta.roles.includes(userRole)) {
    // Use Swal directly instead of accessing it from the store
    Swal.fire({
      icon: 'error',
      title: 'Access Denied',
      text: 'You do not have permission to access this page.',
    }).then(() => {
      next('/'); // Redirect to home
    });
  } else {
    next(); // Allow access
  }
});

export default router;