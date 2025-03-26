import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import ProfilView from '../views/ProfilView.vue'
import RegisterView from '../views/RegisterView.vue'
import SectionsView from '../views/SectionsView.vue'
import DetailSectionView from '../views/DetailSectionView.vue'
import CreateSectionForm from '@/components/CreateSectionForm.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/login',
      name: 'login',
      component : LoginView,
    },
    {
      path: '/register',
      name: 'register',
      component : RegisterView,
    },
    {
      path: '/profil',
      name: 'profil',
      component : ProfilView,
      meta: { requiresAuth: true },
    },
    {
      path: '/sections',
      name: 'sections',
      component: SectionsView,
    },
    {
      path: '/sections/:id',
      name: 'section',
      component: DetailSectionView,
    },
    {
      path: '/create-section',
      name: 'create-section',
      component: CreateSectionForm,
      meta: { requiresAuth: true },
    }
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.isUserLoggedIn()) {
      next({ name: 'login' });
    } else {
      next();
    }
  } else {
    next();
  }
})

export default router
