<template>
  <div class="container mx-auto p-6">
    <div v-if="isLoading" class="text-center py-12">
      <p class="text-xl">Chargement...</p>
    </div>
    
    <div v-else-if="error" class="text-center py-12">
      <p class="text-red-500 text-xl">{{ error }}</p>
      <button @click="goBack" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Retour
      </button>
    </div>
    
    <div v-else-if="section" class="max-w-4xl mx-auto">
      <div class="flex items-center mb-6">
        <button @click="goBack" class="mr-4 text-blue-500 hover:underline">
          &larr; Retour
        </button>
        <h1 class="text-3xl font-bold">{{ section.name }}</h1>
      </div>
      
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <img 
          v-if="section.image" 
          :src="section.image" 
          :alt="section.name" 
          class="w-full h-64 object-cover"
        />
        
        <div class="p-6">
          <div class="mb-6">
            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
              {{ section.category }}
            </span>
          </div>
          
          <p class="text-gray-700 mb-4">{{ section.description }}</p>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div v-if="section.schedule" class="flex items-center">
              <span class="font-semibold mr-2">Horaires:</span>
              <span>{{ section.schedule }}</span>
            </div>
            
            <div v-if="section.price" class="flex items-center">
              <span class="font-semibold mr-2">Tarif:</span>
              <span>{{ section.price }}€</span>
            </div>
            
            <div v-if="section.members" class="flex items-center">
              <span class="font-semibold mr-2">Membres:</span>
              <span>{{ section.members }}</span>
            </div>
          </div>
          
          <div class="mt-6">
            <RouterLink 
              :to="`/inscription?section=${section.slug}`" 
              class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
            >
              S'inscrire à cette section
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
    
    <div v-else class="text-center py-12">
      <p class="text-xl">Section non trouvée</p>
      <button @click="goBack" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Retour
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useSectionStore } from '@/stores/sectionStore'

const route = useRoute()
const router = useRouter()
const sectionStore = useSectionStore()

const isLoading = ref(true)
const error = ref<string | null>(null)

// Récupérer le slug de la section depuis les paramètres de route
const sectionSlug = computed(() => route.params.slug as string)

// Récupérer la section depuis le store
const section = computed(() => {
  if (!sectionSlug.value) return null
  return sectionStore.getSectionBySlug(sectionSlug.value)
})

// Fonction pour revenir en arrière
function goBack() {
  router.back()
}

onMounted(async () => {
  try {
    // Charger les sections si elles ne sont pas déjà chargées
    if (sectionStore.sections.length === 0) {
      await sectionStore.fetchSections()
    }
    
    // Vérifier si la section existe
    if (sectionSlug.value && !section.value) {
      error.value = 'Section non trouvée'
    }
  } catch (err) {
    error.value = 'Erreur lors du chargement de la section'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})
</script>
