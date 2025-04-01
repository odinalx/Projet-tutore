import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/authStore';
import { useOrganismeStore } from './stores/organismeStore';

import App from './App.vue'
import router from './router'

import './assets/tailwind.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)

const authStore = useAuthStore();
authStore.initializeStore();

const organismeStore = useOrganismeStore();
const organisme = "a0c84a3b-a7dd-4d31-8f12-6a573ebe264d"; // ID de l'organisme à choisir

organismeStore.fetchOrganisme(organisme).then(() => {
    app.mount('#app');
});
