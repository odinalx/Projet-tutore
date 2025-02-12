import AccueilView from '@/views/AccueilView.vue'
import { createRouter, createWebHistory } from 'vue-router'
import SectionsListView from '@/views/SectionsListView.vue'
import DetailSectionView from '@/views/DetailSectionView.vue'
import InscriptionView from '@/views/InscriptionView.vue'
import DetailUserView from '@/views/DetailUserView.vue'
import DetailSectionInscritView from '@/views/DetailSectionInscritView.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/accueil',
      name: 'Accueil',
      component: AccueilView,
    },
 {
  path: '/sections/',
  name: 'SectionsList',
  component: SectionsListView
 },
 {
  path: '/section/',
  name: 'Details Section',
  component: DetailSectionView
 },
 {
  path: '/inscription/',
  name: 'Inscription',
  component: InscriptionView
 },
 {
  path: '/profil/',
  name: 'ProfilUtilisateur',
  component: DetailUserView
 },
 {
  path: '/auth/section/',
  name: 'Profil utilisateur',
  component: DetailSectionInscritView
 },
  ],
})

export default router