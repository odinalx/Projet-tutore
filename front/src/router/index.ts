import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

// Vues
import Home from '@/views/Home.vue'
import AccueilView from '@/views/AccueilView.vue'
import SectionsListView from '@/views/SectionsListView.vue'
import DetailSectionView from '@/views/DetailSectionView.vue'
import DetailSectionInscritView from '@/views/DetailSectionInscritView.vue'
import InscriptionView from '@/views/InscriptionView.vue'
import LoginView from '@/views/LoginView.vue'
import RegisterView from '@/views/RegisterView.vue'
import ProfileView from '@/views/ProfileView.vue'

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: '/accueil'
  },
  {
    path: '/accueil',
    name: 'accueil',
    component: AccueilView
  },
  {
    path: '/sections',
    name: 'sections',
    component: SectionsListView
  },
  {
    path: '/section/:slug',
    name: 'section-detail',
    component: DetailSectionView
  },
  {
    path: '/auth/section/:slug',
    name: 'section-detail-inscrit',
    component: DetailSectionInscritView,
    meta: { requiresAuth: true }
  },
  {
    path: '/inscription',
    name: 'inscription',
    component: InscriptionView
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'register',
    component: RegisterView
  },
  {
    path: '/profil',
    name: 'profil',
    component: ProfileView,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation guard pour les routes protégées
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // Vérifier si la route nécessite une authentification
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    // Rediriger vers la page de connexion
    next({ name: 'login' })
  } else {
    // Continuer normalement
    next()
  }
})

export default router
