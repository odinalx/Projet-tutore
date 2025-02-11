<script setup lang="ts">
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <div class="min-h-screen bg-red-100">
    <div class="flex items-center justify-center gap-4 p-4">
      <a href="https://vite.dev" target="_blank">
        <img src="/vite.svg" class="h-24 p-6 transition-all hover:drop-shadow-lg" alt="Vite logo" />
      </a>
      <a href="https://vuejs.org/" target="_blank">
        <img
          src="./assets/vue.svg"
          class="h-24 p-6 transition-all hover:drop-shadow-lg"
          alt="Vue logo"
        />
      </a>
    </div>
    <HelloWorld msg="Vite + Vue" />
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
