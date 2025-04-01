<script>
  export default {
    props: {
      section: {
        type: Object,
        required: true,
      },
    },
  };
</script>

<template>
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
      <div class="p-4">
        <h3 class="text-xl font-semibold mb-2">{{ section.nom }}</h3>
        <p class="text-gray-600 mb-2">{{ section.description }}</p>
        <p class="text-sm text-gray-500">
          <span class="font-medium">Catégorie:</span> {{ section.categorie }}
        </p>
  
        <div class="flex justify-between items-center mt-4">
          <div class="text-sm text-gray-600">
            <span class="font-medium">Prix:</span> {{ section.tarif }}€
          </div>
  
          <RouterLink
            :to="`/sections/${section.id}`"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition flex items-center"
          >
            <span>Détails</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </RouterLink>
        </div>
      </div>
    </div>
  </template>
  
  
  