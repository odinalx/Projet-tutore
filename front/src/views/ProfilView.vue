<script>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import UserSections from '@/components/UserSections.vue'
import MyEncadrants from '@/components/MyEncadrants.vue';
import utilsMixin from '@/mixins/utilsMixin';

export default {
  mixins: [utilsMixin],
  components: {
    UserSections,
    MyEncadrants,
  },
  setup() {
    const router = useRouter()
    const authStore = useAuthStore()
    return { router, authStore }
  },
  methods: {
    async handleLogout() {
      await this.authStore.logout()
      this.router.push('/')
    }
  },
}
</script>
<template>
  <div class="container mx-auto p-6">
    <div class="flex items-center mb-8">
      <div class="w-1/4">
        <RouterLink to="/" class="text-blue-600 hover:underline flex items-center">
          <span class="mr-1">&larr;</span> Retour à l'accueil
        </RouterLink>
      </div>
      <h1 class="text-3xl font-bold text-center w-2/4">Mon Profil</h1>
      <div class="w-1/4"></div>
    </div>

    <div v-if="!authStore.isUserLoggedIn()" class="text-center py-12">
      <p class="text-xl mb-4">Vous devez être connecté pour accéder à cette page</p>
      <div class="flex justify-center space-x-4">
        <RouterLink to="/login" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
          Se connecter
        </RouterLink>
        <RouterLink to="/register" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition">
          S'inscrire
        </RouterLink>
      </div>
    </div>

    <div v-else-if="authStore.user" class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="md:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Informations personnelles</h2>

          <div class="space-y-4">
            <div>
              <p class="text-sm text-gray-500">Email</p>
              <p class="font-medium">{{ authStore.user.email }}</p>
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
            <div class="pt-4 border-t">
              <button @click="handleLogout" class="text-red-600 hover:underline">
                Se déconnecter
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="md:col-span-2">
        <UserSections />
        <MyEncadrants :isVisible="checkUserRole()" />

        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold mb-4">Historique des paiements</h2>
        </div>
      </div>
    </div>
  </div>
</template>
