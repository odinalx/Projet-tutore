<script setup lang="ts">
// Le router est déjà configuré dans main.ts
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-3">
        <router-link
          to="/"
          class="text-gray-800 hover:text-gray-600 font-medium"
        >
          Accueil
        </router-link>
      </div>
    </nav>

    <router-view />
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
