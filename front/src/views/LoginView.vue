<script>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    setup() {
        const router = useRouter()
        const authStore = useAuthStore()
        return { router, authStore }
    },
    methods: {
        async handleLogin() {
            const success = await this.authStore.login({
                email: this.email,
                password: this.password
            })

            if (success) {
                this.router.push('/profil')
            }
        }

    },
    mounted() {
        if (this.authStore.isUserLoggedIn()) {
            this.router.push('/profil')
        }
    }
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
            <h1 class="text-3xl font-bold text-center w-2/4">Connexion</h1>
            <div class="w-1/4"></div>
        </div>

        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <div v-if="authStore.error" class="mb-4 p-3 bg-red-50 text-red-700 rounded-md">
                {{ authStore.error }}
            </div>

            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" v-model="email" type="email" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                    <input id="password" v-model="password" type="password" required
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition"
                >
                    <span>Se connecter</span>
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
