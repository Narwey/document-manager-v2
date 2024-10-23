<template>
    <div>
      <Navbar />
      <div class="container mx-auto p-8">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold">Categories Management</h2>
          <button
            class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700"
            @click="openAddModal"
          >
            Add Category
          </button>
        </div>
  
        <div class="overflow-x-auto bg-white shadow-md rounded-lg p-6">
          <table class="min-w-full table-auto">
            <thead>
              <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">ID</th>
                <th class="py-3 px-6 text-left">Name</th>
                <th class="py-3 px-6 text-left">Description</th>
                <th class="py-3 px-6 text-left">Created At</th>
                <th class="py-3 px-6 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
              <tr
                v-for="category in categories"
                :key="category.id"
                class="border-b border-gray-200 hover:bg-gray-100 transition-colors duration-150 ease-in-out"
              >
                <td class="py-3 px-6">{{ category.id }}</td>
                <td class="py-3 px-6">{{ category.name }}</td>
                <td class="py-3 px-6">{{ category.description }}</td>
                <td class="py-3 px-6">{{ category.created_at }}</td>
                <td class="py-3 px-6 text-center">
                  <div class="flex items-center justify-center">
                    <button
                      class="w-4 mr-2 transform hover:text-green-500 hover:scale-110 transition-all duration-150 ease-in-out"
                      @click="openEditModal(category)"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                      </svg>
                    </button>
                    <button
                      class="w-4 mr-2 transform hover:text-red-500 hover:scale-110 transition-all duration-150 ease-in-out"
                      @click="openDeleteModal(category)"
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
  
        <!-- Add/Edit Category Modal -->
        <div v-if="showAddEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4">{{ editMode ? 'Edit Category' : 'Add Category' }}</h3>
            <input
              v-model="selectedCategory.name"
              class="w-full mb-4 px-4 py-2 border rounded"
              placeholder="Category Name"
            />
            <textarea
              v-model="selectedCategory.description"
              class="w-full mb-4 px-4 py-2 border rounded"
              placeholder="Category Description"
            ></textarea>
            <div class="flex justify-center">
              <button class="px-4 py-2 bg-gray-500 text-white rounded mr-2" @click="closeAddEditModal">
                Cancel
              </button>
              <button class="px-4 py-2 bg-green-600 text-white rounded" @click="saveCategory">
                Save
              </button>
            </div>
          </div>
        </div>
  
        <!-- Delete Confirmation Modal -->
        <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-xl font-semibold mb-4">Are you sure you want to delete this category?</h3>
            <div class="flex justify-center">
              <button class="px-4 py-2 bg-gray-500 text-white rounded mr-2" @click="closeDeleteModal">
                Cancel
              </button>
              <button class="px-4 py-2 bg-red-600 text-white rounded" @click="deleteCategory">
                Delete
              </button>
            </div>
          </div>
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
        categories: [],
        showAddEditModal: false,
        showDeleteModal: false,
        selectedCategory: { name: '', description: '' },
        editMode: false,
      };
    },
    created() {
      this.fetchCategories();
    },
    methods: {
      async fetchCategories() {
        try {
          const response = await axios.get('/categories');
          this.categories = response.data;
        } catch (error) {
          console.error('Error fetching categories:', error);
        }
      },
      openAddModal() {
        this.selectedCategory = { name: '', description: '' };
        this.editMode = false;
        this.showAddEditModal = true;
      },
      openEditModal(category) {
        this.selectedCategory = { ...category };
        this.editMode = true;
        this.showAddEditModal = true;
      },
      closeAddEditModal() {
        this.showAddEditModal = false;
      },
      async saveCategory() {
        try {
          if (this.editMode) {
            await axios.put(`/categories/${this.selectedCategory.id}`, this.selectedCategory);
          } else {
            await axios.post('/categories', this.selectedCategory);
          }
          this.fetchCategories();
          this.closeAddEditModal();
        } catch (error) {
          console.error('Error saving category:', error);
        }
      },
      openDeleteModal(category) {
        this.selectedCategory = { ...category };
        this.showDeleteModal = true;
      },
      closeDeleteModal() {
        this.showDeleteModal = false;
      },
      async deleteCategory() {
        try {
          await axios.delete(`/categories/${this.selectedCategory.id}`);
          this.fetchCategories();
          this.closeDeleteModal();
        } catch (error) {
          console.error('Error deleting category:', error);
        }
      },
    },
  };
  </script>
  