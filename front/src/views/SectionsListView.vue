<template>
  <div class="container mx-auto p-6">
    <div class="flex items-center mb-8">
      <div class="w-1/4">
        <RouterLink 
          to="/accueil" 
          class="text-blue-600 hover:underline flex items-center"
        >
          <span class="mr-1">&larr;</span> Retour à l'accueil
        </RouterLink>
      </div>
      <h1 class="text-3xl font-bold text-center w-2/4">Toutes nos sections</h1>
      <div class="w-1/4"><!-- Espace vide pour équilibrer --></div>
    </div>
    
    <div v-if="isLoading" class="text-center py-12">
      <p class="text-xl">Chargement des sections...</p>
    </div>
    
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-500 text-xl">{{ error }}</p>
    </div>
    
    <div v-else>
      <div v-for="category in categories" :key="category" class="mb-12">
        <h2 class="text-2xl font-semibold mb-4">{{ category }}</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="section in getSectionsByCategory(category)" 
            :key="section.id"
            class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition"
          >
            <img 
              v-if="section.image" 
              :src="section.image" 
              :alt="section.name" 
              class="w-full h-48 object-cover"
            />
            
            <div class="p-4">
              <h3 class="text-xl font-semibold mb-2">{{ section.name }}</h3>
              <p class="text-gray-600 mb-4 line-clamp-2">{{ section.description }}</p>
              
              <div class="flex flex-col space-y-3">
                <div class="flex justify-between items-center">
                  <div class="text-sm text-gray-600">
                    <span class="font-medium">Prix:</span> {{ section.price }}€
                  </div>
                  <div class="text-sm text-gray-600">
                    <span class="font-medium">Places:</span> {{ section.members }}
                  </div>
                </div>
                
                <div class="flex justify-between items-center">
                  <RouterLink 
                    :to="`/section/${section.slug}`" 
                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center"
                  >
                    <span>Détails</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </RouterLink>
                  
                  <RouterLink 
                    :to="`/inscription?section=${section.slug}`" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition flex items-center"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>S'inscrire</span>
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useSectionStore } from '@/stores/sectionStore'

const sectionStore = useSectionStore()
const isLoading = ref(true)
const error = ref<string | null>(null)

// Récupérer les catégories depuis le store
const categories = computed(() => sectionStore.getCategories)

// Fonction pour récupérer les sections par catégorie
const getSectionsByCategory = (category: string) => {
  return sectionStore.getSectionsByCategory(category)
}

onMounted(async () => {
  try {
    await sectionStore.fetchSections()
  } catch (err) {
    error.value = 'Erreur lors du chargement des sections'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})
</script>
