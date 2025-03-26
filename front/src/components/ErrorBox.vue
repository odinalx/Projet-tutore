<script>
export default {
    props: {
        message: {
            type: String,
            required: true,
        },
    },

};
</script>

<template>
    <div class="flex items-center justify-center">
        <div class="error-box text-center border border-red-500 bg-red-100 text-red-700 px-4 py-3 rounded relative">
            <p class="text-red-500 text-xl">{{ message }}</p>
        </div>
    </div>
</template>

<style scoped>

.error-box::before {
  @apply absolute -left-1.5 -top-1.5 w-6 h-6 bg-red-500 text-white flex items-center justify-center rounded-full border-4 border-red-100;
  content: '!';
}
</style>