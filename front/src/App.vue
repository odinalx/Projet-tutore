<script>
import { useAuthStore } from '@/stores/authStore'
import { useOrganismeStore } from './stores/organismeStore'

export default {
  setup() {
    const organismeStore = useOrganismeStore();
    const authStore = useAuthStore();
    return { organismeStore, authStore};
  },
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50">
    <nav class="bg-green-700 shadow-md">
      <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-20">
          
          <div class="flex items-center">
            <RouterLink to="/" class="flex items-center space-x-4">
              <img src="/logo.png" alt="Logo" class="h-10 w-10 rounded-full" />
              <span class="text-2xl font-bold text-white">
                {{ organismeStore.currentOrganisme?.nom}}
              </span>
            </RouterLink>
          </div>

          <div class="hidden md:flex space-x-4">
            <RouterLink to="/sections" class="nav-link">Sections</RouterLink>

            <template v-if="authStore.isUserLoggedIn()">
              <RouterLink to="/profil" class="nav-link">Mon Profil</RouterLink>
            </template>
            <template v-else>
              <RouterLink to="/login" class="nav-link">Se connecter</RouterLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <main class="flex-grow">
      <RouterView />
    </main>

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
.nav-link {
  @apply text-white hover:bg-green-600 px-4 py-2 rounded transition-all duration-300;
}
</style>
