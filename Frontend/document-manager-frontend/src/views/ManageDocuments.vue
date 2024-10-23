<template>
    <div>
      <!-- Navbar at the top -->
      <Navbar />
  
      <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Document Management</h1>
  
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <!-- Category List -->
          <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Categories</h2>
            <ul class="space-y-2">
              <li 
                v-for="category in categories" 
                :key="category.id" 
                @click="selectCategory(category)"
                class="cursor-pointer p-2 rounded hover:bg-gray-100 transition-colors"
                :class="{ 'bg-green-100': selectedCategory === category }"
              >
                {{ category.name }}
              </li>
            </ul>
          </div>
  
          <!-- Document List and Actions -->
          <div class="md:col-span-3 bg-white p-4 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold">Documents</h2>
              <button 
                @click="openUploadModal" 
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors flex items-center"
              >
                <UploadIcon class="w-5 h-5 mr-2" />
                Upload Document
              </button>
            </div>
  
            <!-- Document List -->
            <ul class="space-y-4">
              <li 
                v-for="document in documents" 
                :key="document.id" 
                class="flex items-center justify-between p-3 bg-gray-50 rounded"
              >
                <div class="flex items-center">
                  <FileIcon class="w-6 h-6 mr-3 text-gray-500" />
                  <span>{{ document.title }}</span>
                </div>
                <div class="flex items-center space-x-2">
                  <button @click="downloadDocument(document)" class="text-blue-500 hover:text-blue-700">
                    <DownloadIcon class="w-5 h-5" />
                  </button>
                  <button @click="deleteDocument(document)" class="text-red-500 hover:text-red-700">
                    <TrashIcon class="w-5 h-5" />
                  </button>
                </div>
              </li>
            </ul>
  
            <div v-if="documents.length === 0" class="text-center py-8 text-gray-500">
              <FolderIcon class="w-16 h-16 mx-auto mb-4" />
              <p>No documents in this category</p>
            </div>
          </div>
        </div>
  
        <!-- Upload Modal -->
        <div v-if="showUploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
          <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h3 class="text-xl font-semibold mb-4">Upload Document</h3>
  
            <div class="mb-4">
              <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Select Category</label>
              <select 
                id="category" 
                v-model="selectedUploadCategory" 
                class="w-full p-2 border rounded"
              >
                <option 
                  v-for="category in categories" 
                  :key="category.id" 
                  :value="category.id"
                >
                  {{ category.name }}
                </option>
              </select>
            </div>
  
            <div class="mb-4">
              <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
              <input type="file" id="file" class="w-full p-2 border rounded" ref="fileInput" />
            </div>
  
            <div class="flex justify-end space-x-2">
              <button @click="closeUploadModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors">
                Cancel
              </button>
              <button @click="uploadDocument" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors">
                Upload
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from '../axios'
  import { UploadIcon, FileIcon, DownloadIcon, TrashIcon, FolderIcon } from 'lucide-vue-next'
  import Navbar from '../views/Nav.vue'
  
  const categories = ref([])
  const documents = ref([])
  const selectedCategory = ref(null)
  const selectedUploadCategory = ref(null)
  const showUploadModal = ref(false)
  
  // Fetch Categories from API
  const fetchCategories = async () => {
    try {
      const { data } = await axios.get('/categories')
      categories.value = data

      console.log('data' , data);
    } catch (error) {
      console.error('Error fetching categories:', error)
    }
  }
  
  // Fetch Documents by Category
  const fetchDocuments = async (categoryId) => {
    try {
      const { data } = await axios.get(`/documents/category/${categoryId}`)
      documents.value = data
      console.log("data-documents" , data);
    } catch (error) {
      console.error('Error fetching documents:', error)
    }
  }
  
  const selectCategory = (category) => {
    selectedCategory.value = category
    fetchDocuments(category.id)
  }
  
  const openUploadModal = () => {
    selectedUploadCategory.value = selectedCategory.value?.id || null
    showUploadModal.value = true
  }
  
  const closeUploadModal = () => {
    showUploadModal.value = false
  }
  
  const uploadDocument = async () => {
  const file = document.querySelector('#file').files[0];
  const categoryId = selectedUploadCategory.value;

  if (!file || !categoryId) {
    alert('Please select a file and category.');
    return;
  }

  const formData = new FormData();
  formData.append('file', file);
  formData.append('category_id', categoryId);
  formData.append('title', file.name);

  try {
    const response = await axios.post('/documents/upload', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    console.log('Upload successful:', response.data);
    closeUploadModal();
    fetchDocuments(categoryId);
  } catch (error) {
    if (error.response && error.response.status === 422) {
      console.error('Validation error:', error.response.data.errors);
    } else {
      console.error('Error uploading document:', error);
    }
  }
};

  
  const downloadDocument = (document) => {
    window.open(document.file_link, '_blank')
  }
  
  const deleteDocument = async (document) => {
    try {
      await axios.delete(`/documents/${document.id}`)
      fetchDocuments(selectedCategory.value.id)
    } catch (error) {
      console.error('Error deleting document:', error)
    }
  }
  
  // Initial Fetch of Categories
  fetchCategories()
  </script>