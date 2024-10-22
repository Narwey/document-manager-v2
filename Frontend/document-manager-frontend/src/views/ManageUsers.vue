<template>
    <div>
      <Navbar />
      <div class="container mx-auto p-8">
        <div class="overflow-x-auto bg-white shadow-md rounded-lg p-6">
          <table class="min-w-full table-auto">
            <thead>
              <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">First Name</th>
                <th class="py-3 px-6 text-left">Last Name</th>
                <th class="py-3 px-6 text-left">Email</th>
                <th class="py-3 px-6 text-left">Role</th>
                <th class="py-3 px-6 text-left">Created At</th>
                <th class="py-3 px-6 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
              <tr
                v-for="user in users"
                :key="user.id"
                class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-150 ease-in-out"
              >
                <td class="py-3 px-6 text-left">{{ user.id }}</td>
                <td class="py-3 px-6 text-left">{{ user.firstName }}</td>
                <td class="py-3 px-6 text-left">{{ user.lastName }}</td>
                <td class="py-3 px-6 text-left">{{ user.email }}</td>
                <td class="py-3 px-6 text-left">
                  <span
                    :class="{
                      'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs': user.role === 'user',
                      'bg-blue-200 text-blue-600 py-1 px-3 rounded-full text-xs': user.role === 'admin'
                    }"
                  >
                    {{ user.role }}
                  </span>
                </td>
                <td class="py-3 px-6 text-left">{{ formatDate(user.created_at) }}</td>
                <td class="py-3 px-6 text-center">
                  <div class="flex items-center justify-center">
                    <button
                      class="w-4 mr-2 transform hover:text-green-500 hover:scale-110 transition-all duration-150 ease-in-out"
                      @click="openEditModal(user)"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button
                      class="w-4 mr-2 transform hover:text-red-500 hover:scale-110 transition-all duration-150 ease-in-out"
                      @click="openDeleteModal(user)"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import Navbar from '../views/Nav.vue';
  import axios from '../axios';
  
  export default {
    components: {
      Navbar,
    },
    data() {
      return {
        users: [],
        showEditModal: false,
        showDeleteModal: false,
        selectedUser: {},
      };
    },
    created() {
      this.fetchUsers();
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get('/users');
          this.users = response.data;
          console.log("ach wa9e3" , this.users);
        } catch (error) {
          if (error.response && error.response.status === 403) {
            this.$swal({
              icon: 'error',
              title: 'Access Denied',
              text: 'You are not authorized to access this page.',
            }).then(() => {
              this.$router.push('/');
            });
          } else {
            this.$swal({
              icon: 'error',
              title: 'Access Denied',
              text: 'You are not authorized to access this page.',
            }).then(() => {
              this.$router.push('/');
            });
          }
        }
      },
      openEditModal(user) {
        this.selectedUser = { ...user };
        this.showEditModal = true;
      },
      closeEditModal() {
        this.showEditModal = false;
      },
      async saveUser() {
        try {
          await axios.put(`/users/${this.selectedUser.id}`, this.selectedUser);
          this.fetchUsers();
          this.closeEditModal();
        } catch (error) {
          console.error('Error saving user:', error);
        }
      },
      openDeleteModal(user) {
        this.selectedUser = { ...user };
        this.showDeleteModal = true;
      },
      closeDeleteModal() {
        this.showDeleteModal = false;
      },
      async deleteUser() {
        try {
          await axios.delete(`/users/${this.selectedUser.id}`);
          this.fetchUsers();
          this.closeDeleteModal();
        } catch (error) {
          console.error('Error deleting user:', error);
        }
      },
      formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
        });
      },
    },
  };
  </script>
  
  <style scoped>
  .container {
    margin-top: 20px;
  }
  </style>  