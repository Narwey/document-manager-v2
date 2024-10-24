import { createWebHistory, createRouter } from 'vue-router';
import Swal from 'sweetalert2'; 
import { useAuthStore } from '../stores/authStore';


import Landing from '../views/Landing.vue';
import Login from '../views/Auth/Login.vue';
import Register from '../views/Auth/Register.vue';
import ManageUsers from '../views/ManageUsers.vue';
import ManageCategories from '../views/ManageCategories.vue';
import ManageDocuments from '../views/ManageDocuments.vue';

const routes = [
  { path: '/', component: Landing },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/users', component: ManageUsers, meta: { roles: ['admin'] } },
  { path: '/categories', component: ManageCategories, meta: { roles: ['admin' , 'manager'] } },
  { path: '/documents', component: ManageDocuments , meta : { roles: ['admin' , 'manager','user']}},
];


const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const userRole = authStore.user?.role;

  if (to.meta.roles && !to.meta.roles.includes(userRole)) {
    
    Swal.fire({
      icon: 'error',
      title: 'Access Denied',
      text: 'You do not have permission to access this page.',
    }).then(() => {
      next('/'); 
    });
  } else {
    next(); // Allow access
  }
});

export default router;