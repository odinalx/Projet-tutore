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
      <h1 class="text-3xl font-bold text-center w-2/4">Mon Profil</h1>
      <div class="w-1/4"><!-- Espace vide pour équilibrer --></div>
    </div>
    
    <div v-if="authStore.isLoading" class="text-center py-12">
      <p class="text-xl">Chargement du profil...</p>
    </div>
    
    <div v-else-if="!authStore.isAuthenticated" class="text-center py-12">
      <p class="text-xl mb-4">Vous devez être connecté pour accéder à cette page</p>
      <div class="flex justify-center space-x-4">
        <RouterLink 
          to="/login" 
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition"
        >
          Se connecter
        </RouterLink>
        <RouterLink 
          to="/register" 
          class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition"
        >
          S'inscrire
        </RouterLink>
      </div>
    </div>
    
    <div v-else-if="authStore.user" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Informations personnelles -->
      <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Informations personnelles</h2>
          
          <div class="space-y-4">
            <div>
              <p class="text-sm text-gray-500">Nom complet</p>
              <p class="font-medium">{{ authStore.user.firstName }} {{ authStore.user.lastName }}</p>
            </div>
            
            <div>
              <p class="text-sm text-gray-500">Email</p>
              <p class="font-medium">{{ authStore.user.email }}</p>
            </div>
            
            <div v-if="authStore.user.phone">
              <p class="text-sm text-gray-500">Téléphone</p>
              <p class="font-medium">{{ authStore.user.phone }}</p>
            </div>
            
            <div v-if="authStore.user.address">
              <p class="text-sm text-gray-500">Adresse</p>
              <p class="font-medium">{{ authStore.user.address }}</p>
            </div>
            
            <div v-if="authStore.user.birthdate">
              <p class="text-sm text-gray-500">Date de naissance</p>
              <p class="font-medium">{{ formatDate(authStore.user.birthdate) }}</p>
            </div>
          </div>
          
          <div class="mt-6">
            <button class="text-blue-600 hover:underline">
              Modifier mes informations
            </button>
          </div>
        </div>
        
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Sécurité</h2>
          
          <div class="space-y-4">
            <button class="text-blue-600 hover:underline">
              Changer mon mot de passe
            </button>
            
            <div class="pt-4 border-t">
              <button 
                @click="handleLogout" 
                class="text-red-600 hover:underline"
              >
                Se déconnecter
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Sections et paiements -->
      <div class="md:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Mes sections</h2>
            <RouterLink 
              to="/sections" 
              class="text-blue-600 hover:underline"
            >
              Voir toutes les sections
            </RouterLink>
          </div>
          
          <div v-if="authStore.user.sections.length === 0" class="text-center py-6">
            <p class="text-gray-500">Vous n'êtes inscrit à aucune section</p>
            <RouterLink 
              to="/sections" 
              class="mt-2 inline-block text-blue-600 hover:underline"
            >
              Découvrir les sections
            </RouterLink>
          </div>
          
          <div v-else class="space-y-4">
            <div 
              v-for="section in authStore.user.sections" 
              :key="section.id"
              class="border rounded-lg p-4 hover:bg-gray-50 transition"
            >
              <div class="flex justify-between items-center">
                <div>
                  <h3 class="font-semibold">{{ section.name }}</h3>
                  <p class="text-sm text-gray-600">{{ section.category }}</p>
                </div>
                
                <RouterLink 
                  :to="`/auth/section/${section.slug}`" 
                  class="text-blue-600 hover:underline"
                >
                  Voir les détails
                </RouterLink>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Historique des paiements</h2>
          
          <div v-if="authStore.user.payments.length === 0" class="text-center py-6">
            <p class="text-gray-500">Aucun paiement enregistré</p>
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Date
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Description
                  </th>
                  <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Montant
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="payment in authStore.user.payments" :key="payment.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ formatDate(payment.date) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ payment.description }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                    {{ payment.amount }}€
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

function formatDate(dateString: string): string {
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  }).format(date)
}

async function handleLogout() {
  await authStore.logout()
  router.push('/accueil')
}

onMounted(() => {
  if (!authStore.isAuthenticated) {
    authStore.fetchUserProfile()
  }
})
</script> 