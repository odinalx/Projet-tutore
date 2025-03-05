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
      <h1 class="text-3xl font-bold text-center w-2/4">Inscription</h1>
      <div class="w-1/4"><!-- Espace vide pour équilibrer --></div>
    </div>
    
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
      <div v-if="authStore.error" class="mb-4 p-3 bg-red-50 text-red-700 rounded-md">
        {{ authStore.error }}
      </div>
      
      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
            <input 
              id="firstName" 
              v-model="formData.firstName" 
              type="text" 
              required
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': errors.firstName}"
            />
            <p v-if="errors.firstName" class="mt-1 text-sm text-red-600">{{ errors.firstName }}</p>
          </div>
          
          <div>
            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input 
              id="lastName" 
              v-model="formData.lastName" 
              type="text" 
              required
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              :class="{'border-red-500': errors.lastName}"
            />
            <p v-if="errors.lastName" class="mt-1 text-sm text-red-600">{{ errors.lastName }}</p>
          </div>
        </div>
        
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input 
            id="email" 
            v-model="formData.email" 
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
            v-model="formData.password" 
            type="password" 
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{'border-red-500': errors.password}"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
        </div>
        
        <div>
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
          <input 
            id="confirmPassword" 
            v-model="formData.confirmPassword" 
            type="password" 
            required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="{'border-red-500': errors.confirmPassword}"
          />
          <p v-if="errors.confirmPassword" class="mt-1 text-sm text-red-600">{{ errors.confirmPassword }}</p>
        </div>
        
        <div class="flex items-center">
          <input 
            id="terms" 
            type="checkbox" 
            v-model="formData.termsAccepted"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            :class="{'border-red-500': errors.termsAccepted}"
          />
          <label for="terms" class="ml-2 block text-sm text-gray-700">
            J'accepte les <a href="#" class="text-blue-600 hover:underline">conditions générales</a>
          </label>
        </div>
        <p v-if="errors.termsAccepted" class="mt-1 text-sm text-red-600">{{ errors.termsAccepted }}</p>
        
        <button 
          type="submit" 
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition"
          :disabled="authStore.isLoading"
        >
          <span v-if="authStore.isLoading">Inscription en cours...</span>
          <span v-else>S'inscrire</span>
        </button>
      </form>
      
      <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">
          Vous avez déjà un compte ?
          <RouterLink to="/login" class="text-blue-600 hover:underline">
            Se connecter
          </RouterLink>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const formData = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  termsAccepted: false
})

const errors = reactive({
  firstName: '',
  lastName: '',
  email: '',
  password: '',
  confirmPassword: '',
  termsAccepted: ''
})

async function handleRegister() {
  // Réinitialiser les erreurs
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })
  
  // Validation
  let isValid = true
  
  if (!formData.firstName) {
    errors.firstName = 'Le prénom est requis'
    isValid = false
  }
  
  if (!formData.lastName) {
    errors.lastName = 'Le nom est requis'
    isValid = false
  }
  
  if (!formData.email) {
    errors.email = 'L\'email est requis'
    isValid = false
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
    errors.email = 'L\'email n\'est pas valide'
    isValid = false
  }
  
  if (!formData.password) {
    errors.password = 'Le mot de passe est requis'
    isValid = false
  } else if (formData.password.length < 6) {
    errors.password = 'Le mot de passe doit contenir au moins 6 caractères'
    isValid = false
  }
  
  if (!formData.confirmPassword) {
    errors.confirmPassword = 'La confirmation du mot de passe est requise'
    isValid = false
  } else if (formData.password !== formData.confirmPassword) {
    errors.confirmPassword = 'Les mots de passe ne correspondent pas'
    isValid = false
  }
  
  if (!formData.termsAccepted) {
    errors.termsAccepted = 'Vous devez accepter les conditions générales'
    isValid = false
  }
  
  if (!isValid) return
  
  // Tentative d'inscription
  const success = await authStore.register({
    firstName: formData.firstName,
    lastName: formData.lastName,
    email: formData.email,
    password: formData.password
  })
  
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