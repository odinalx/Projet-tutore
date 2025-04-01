<script>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

export default {
  data() {
    return {
      formData: {
        nom: '',
        prenom: '',
        email: '',
        password: '',
        confirmPassword: '',
      },
      passwordError: '',
    };
  },
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();
    return { router, authStore };
  },
  methods: {
    async handleRegister() {
      if (this.formData.password !== this.formData.confirmPassword) {
        this.passwordError = "Les mots de passe ne correspondent pas.";
        return;
      }

      this.passwordError = ''; 
      
        const success = await this.authStore.register({
          nom: this.formData.nom,
          prenom: this.formData.prenom,
          email: this.formData.email,
          password: this.formData.password,
        });

        if (success) {
          this.router.push('/profil');
        }
    },
  },
  mounted() {
    if (this.authStore.isUserLoggedIn()) {
      this.router.push('/profil');
    }
  },
};
</script>

<template>
  <div class="container mx-auto p-6">
    <div class="flex items-center mb-8">
      <div class="w-1/4">
        <RouterLink to="/" class="text-blue-600 hover:underline flex items-center">
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
      <div v-if="passwordError" class="mb-4 p-3 bg-red-50 text-red-700 rounded-md">
        {{ passwordError }}
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
            <input v-model="formData.nom" type="text" required
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>

          <div>
            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input v-model="formData.prenom" type="text" required
              class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="formData.email" type="email" required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
          <input v-model="formData.password" type="password" required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <div>
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de
            passe</label>
          <input v-model="formData.confirmPassword" type="password" required
            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition">
          <span>S'inscrire</span>
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
