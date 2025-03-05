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
      <h1 class="text-3xl font-bold text-center w-2/4">Connexion</h1>
      <div class="w-1/4"><!-- Espace vide pour équilibrer --></div>
    </div>
    
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
      <div v-if="authStore.error" class="mb-4 p-3 bg-red-50 text-red-700 rounded-md">
        {{ authStore.error }}
      </div>
      
      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input 
            id="email" 
            v-model="email" 
            type="email" 
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{'border-red-500': errors.email}"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
          <input 
            id="password" 
            v-model="password" 
            type="password" 
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{'border-red-500': errors.password}"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
        </div>
        
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input 
              id="remember" 
              type="checkbox" 
              v-model="rememberMe"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            />
            <label for="remember" class="ml-2 block text-sm text-gray-700">
              Se souvenir de moi
            </label>
          </div>
          
          <a href="#" class="text-sm text-blue-600 hover:underline">
            Mot de passe oublié ?
          </a>
        </div>
        
        <button 
          type="submit" 
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition"
          :disabled="authStore.isLoading"
        >
          <span v-if="authStore.isLoading">Connexion en cours...</span>
          <span v-else>Se connecter</span>
        </button>
      </form>
      
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
          Vous n'avez pas de compte ?
          <RouterLink to="/register" class="text-blue-600 hover:underline">
            S'inscrire
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const errors = reactive({
  email: '',
  password: ''
})

async function handleLogin() {
  // Réinitialiser les erreurs
  errors.email = ''
  errors.password = ''
  
  // Validation simple
  let isValid = true
  
  if (!email.value) {
    errors.email = 'L\'email est requis'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errors.email = 'L\'email n\'est pas valide'
    isValid = false
  }
  
  if (!password.value) {
    errors.password = 'Le mot de passe est requis'
    isValid = false
  } else if (password.value.length < 6) {
    errors.password = 'Le mot de passe doit contenir au moins 6 caractères'
    isValid = false
  }
  
  if (!isValid) return
  
  // Tentative de connexion
  const success = await authStore.login(email.value, password.value)
  
  if (success) {
    router.push('/profil')
  }
}

onMounted(() => {
  // Si l'utilisateur est déjà connecté, rediriger vers le profil
  if (authStore.isAuthenticated) {
    router.push('/profil')
  }
})
</script> 