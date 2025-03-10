import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { api } from '@/lib/api/apiClient'
import type { User } from '@/types'

export interface UserProfile {
  id: string
  email: string
  firstName: string
  lastName: string
  role: string
  sections: any[]
  payments: any[]
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<UserProfile | null>(null)
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  const successMessage = ref<string | null>(null)
  
  // Getters
  const isAuthenticated = computed(() => !!user.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const userFullName = computed(() => {
    if (!user.value) return ''
    return `${user.value.firstName} ${user.value.lastName}`
  })

  // Actions
  async function login(email: string, password: string) {
    isLoading.value = true
    error.value = null

    try {
      // Appel API pour la connexion avec le format attendu par le backend
      const response = await api.post<any>('/auth/login', {
        email: email,
        password: password
      })
      
      // Vérifier si la réponse contient un token dans la structure correcte
      if (response && response.success && response.data) {
        // Stocker les tokens
        if (response.data.accessToken) {
          localStorage.setItem('accessToken', response.data.accessToken)
        }
        
        if (response.data.refreshToken) {
          localStorage.setItem('refreshToken', response.data.refreshToken)
        }
        
        // Extraire l'ID utilisateur du token JWT si disponible
        let userId = 'user-id'
        try {
          // Le token JWT est divisé en 3 parties séparées par des points
          // La deuxième partie contient les données (payload)
          const tokenPayload = response.data.accessToken.split('.')[1]
          // Décoder le payload Base64
          const decodedPayload = JSON.parse(atob(tokenPayload))
          // Extraire l'ID utilisateur
          if (decodedPayload && decodedPayload.id) {
            userId = decodedPayload.id
            localStorage.setItem('userId', userId)
          }
        } catch (e) {
          console.error('Erreur lors du décodage du token JWT:', e)
        }
        
        // Définir les données utilisateur
        user.value = {
          id: userId,
          email: email,
          firstName: email.split('@')[0], // Simulé pour l'instant
          lastName: '',
          role: 'user',
          sections: [],
          payments: []
        }
        
        return true
      }
      
      // Si nous arrivons ici, c'est que la réponse n'a pas la structure attendue
      throw new Error('Réponse invalide du serveur: structure incorrecte')
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Identifiants incorrects'
      console.error(error.value)
      return false
    } finally {
      isLoading.value = false
    }
  }

  async function register(userData: {
    email: string
    password: string
    firstName: string
    lastName: string
  }) {
    isLoading.value = true
    error.value = null

    try {
      // Appel API pour l'inscription avec le format attendu par le backend
      const response = await api.post<any>('/auth/register', {
        nom: userData.lastName,
        prenom: userData.firstName,
        email: userData.email,
        password: userData.password
      })
      
      // Vérifier si l'inscription a réussi
      if (response && response.success) {
        // Définir un message de succès
        successMessage.value = response.message || 'Inscription réussie ! Vous pouvez maintenant vous connecter.'
        
        // Si la réponse contient un token, le stocker
        if (response.data && response.data.accessToken) {
          localStorage.setItem('accessToken', response.data.accessToken)
          
          if (response.data.refreshToken) {
            localStorage.setItem('refreshToken', response.data.refreshToken)
          }
          
          // Extraire l'ID utilisateur du token JWT si disponible
          let userId = 'user-id'
          try {
            const tokenPayload = response.data.accessToken.split('.')[1]
            const decodedPayload = JSON.parse(atob(tokenPayload))
            if (decodedPayload && decodedPayload.id) {
              userId = decodedPayload.id
              localStorage.setItem('userId', userId)
            }
          } catch (e) {
            console.error('Erreur lors du décodage du token JWT:', e)
          }
          
          // Définir les données utilisateur
          user.value = {
            id: userId,
            email: userData.email,
            firstName: userData.firstName,
            lastName: userData.lastName,
            role: 'user',
            sections: [],
            payments: []
          }
        } else {
          // Si la réponse ne contient pas de token, rediriger vers la page de connexion
          // L'utilisateur devra se connecter avec les identifiants qu'il vient de créer
          console.log('Inscription réussie, redirection vers la page de connexion')
        }
        
        return true
      }
      
      // Si nous arrivons ici, c'est que la réponse n'a pas la structure attendue
      throw new Error('Réponse invalide du serveur: structure incorrecte')
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Une erreur est survenue lors de l\'inscription'
      console.error(error.value)
      return false
    } finally {
      isLoading.value = false
    }
  }

  async function logout() {
    user.value = null
    localStorage.removeItem('accessToken')
    localStorage.removeItem('refreshToken')
    localStorage.removeItem('userId')
    
    error.value = null
    successMessage.value = 'Vous avez été déconnecté avec succès'
  }

  async function fetchUserProfile() {
    console.log('fetchUserProfile: non implémenté')
  }

  async function refreshToken() {
    console.log('refreshToken: non implémenté')
    return false
  }

  // Initialiser l'état d'authentification au démarrage
  function init() {
    const token = localStorage.getItem('accessToken')
    if (token) {
      try {
        let userId = 'user-id'
        let email = 'user@example.com'
        
        try {
          const tokenPayload = token.split('.')[1]
          const decodedPayload = JSON.parse(atob(tokenPayload))
          if (decodedPayload) {
            userId = decodedPayload.id || userId
            email = decodedPayload.email || email
          }
        } catch (e) {
          console.error('Erreur lors du décodage du token JWT:', e)
        }
        
        user.value = {
          id: userId,
          email: email,
          firstName: email.split('@')[0],
          lastName: '',
          role: 'user',
          sections: [],
          payments: []
        }
      } catch (err) {
        console.error('Erreur lors de l\'initialisation du profil:', err)
        logout()
      }
    }
  }

  return {
    user,
    isLoading,
    error,
    successMessage,
    isAuthenticated,
    isAdmin,
    userFullName,
    login,
    register,
    logout,
    fetchUserProfile,
    refreshToken,
    init
  }
})
