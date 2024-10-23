<script setup>
  import { useAuthStore } from './stores/authStore';

  const authStore = useAuthStore();
    authStore.initAuth();
</script>

<template>
    <RouterView />
</template>

