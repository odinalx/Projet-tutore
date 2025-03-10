<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const route = useRoute()
const authStore = useAuthStore()
const isMobileMenuOpen = ref(false)

// Fonction pour vérifier si une route est active
const isActiveRoute = (path) => {
  if (path === '/accueil' && route.path === '/') {
    return true
  }
  
  if (path === '/sections') {
    return route.path === '/sections' || route.path.startsWith('/section/')
  }
  
  return route.path === path || route.path.startsWith(path + '/')
}

// Fonctions pour gérer le menu mobile
const toggleMobileMenu = () => {
  isMobileMenuOpen.value = !isMobileMenuOpen.value
}

const closeMobileMenu = () => {
  isMobileMenuOpen.value = false
}

// Initialiser l'état d'authentification au chargement de l'application
onMounted(() => {
  authStore.init()
})
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navbar améliorée -->
    <nav class="bg-white shadow-md">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
          <!-- Nom du site -->
          <div class="flex items-center">
            <RouterLink to="/" class="flex items-center">
              <span class="text-xl font-bold text-gray-800">Association Sportive</span>
            </RouterLink>
          </div>
          
          <!-- Navigation principale -->
          <div class="hidden md:flex space-x-1">
            <RouterLink 
              to="/accueil" 
              class="nav-link"
              :class="{ 'nav-link-active': isActiveRoute('/accueil') }"
            >
              Accueil
            </RouterLink>
            
            <RouterLink 
              to="/sections" 
              class="nav-link"
              :class="{ 'nav-link-active': isActiveRoute('/sections') }"
            >
              Sections
            </RouterLink>
            
            <!-- Boutons d'authentification -->
            <template v-if="authStore.isAuthenticated">
              <RouterLink 
                to="/profil" 
                class="nav-link"
                :class="{ 'nav-link-active': isActiveRoute('/profil') }"
              >
                Mon Profil
              </RouterLink>
            </template>
            <template v-else>
              <RouterLink 
                to="/login" 
                class="nav-link"
                :class="{ 'nav-link-active': isActiveRoute('/login') }"
              >
                Se connecter
              </RouterLink>
            </template>
          </div>
          
          <!-- Bouton menu mobile -->
          <div class="md:hidden">
            <button 
              @click="toggleMobileMenu" 
              class="text-gray-700 hover:text-blue-600 focus:outline-none"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  v-if="isMobileMenuOpen" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M6 18L18 6M6 6l12 12"
                />
                <path 
                  v-else 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Menu mobile -->
      <div 
        v-if="isMobileMenuOpen" 
        class="md:hidden bg-white border-t"
      >
        <div class="container mx-auto px-4 py-2 space-y-1">
          <RouterLink 
            to="/accueil" 
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': isActiveRoute('/accueil') }"
            @click="closeMobileMenu"
          >
            Accueil
          </RouterLink>
          
          <RouterLink 
            to="/sections" 
            class="mobile-nav-link"
            :class="{ 'mobile-nav-link-active': isActiveRoute('/sections') }"
            @click="closeMobileMenu"
          >
            Sections
          </RouterLink>
          
          <!-- Boutons d'authentification mobile -->
          <template v-if="authStore.isAuthenticated">
            <RouterLink 
              to="/profil" 
              class="mobile-nav-link"
              :class="{ 'mobile-nav-link-active': isActiveRoute('/profil') }"
              @click="closeMobileMenu"
            >
              Mon Profil
            </RouterLink>
          </template>
          <template v-else>
            <RouterLink 
              to="/login" 
              class="mobile-nav-link"
              :class="{ 'mobile-nav-link-active': isActiveRoute('/login') }"
              @click="closeMobileMenu"
            >
              Se connecter
            </RouterLink>
          </template>
        </div>
      </div>
    </nav>

    <!-- Contenu principal -->
    <main class="flex-grow">
      <RouterView />
    </main>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
      <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <div class="mb-4 md:mb-0">
            <p>&copy; 2024 Association Sportive. Tous droits réservés.</p>
          </div>
          <div class="flex space-x-4">
            <a href="#" class="hover:text-blue-300">Mentions légales</a>
            <a href="#" class="hover:text-blue-300">Contact</a>
            <a href="#" class="hover:text-blue-300">Plan du site</a>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}

.nav-link {
  @apply px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors;
}

.nav-link-active {
  @apply bg-blue-100 text-blue-800;
}

.mobile-nav-link {
  @apply block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors;
}

.mobile-nav-link-active {
  @apply bg-blue-100 text-blue-800;
}
</style>
