<script>
export default {
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
    },

};
</script>

<template>
    <div v-if="isVisible" class="flex items-center justify-center bg-secondary space-x-4">
      <div class="w-12 h-12 border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin"></div>
      <p class="text-gray-500 text-xl font-bold">Chargement...</p>
    </div>
</template>