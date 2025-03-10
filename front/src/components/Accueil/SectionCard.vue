<template>
  <div class="border rounded-lg p-4 flex flex-col items-center">
    <RouterLink to="section">
      <div class="w-32 h-32 border flex items-center justify-center">
        <span>Image</span>
      </div>
    </RouterLink>
    <p class="mt-2 font-semibold">
      {{ title }}
    </p>
  </div>
</template>

<script setup>
defineProps({ title: String })
</script>
