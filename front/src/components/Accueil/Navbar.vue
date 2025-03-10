<template>
  <nav class="flex justify-between items-center p-4 border-b">
    <RouterLink to="accueil">
      <div class="font-bold">LOGO ASSOCIATION</div>
    </RouterLink>

    <div class="flex space-x-4">
      <Button variant="link" class="text-blue-500">
        <RouterLink to="/accueil"> Accueil </RouterLink>
      </Button>
      <Button variant="link">
        <RouterLink to="/sections"> Sections </RouterLink>
      </Button>
      <RouterLink to="profil">
        <Button variant="link"> Mon profil </Button>
      </RouterLink>
    </div>
  </nav>
</template>

<script setup>
import { RouterLink } from 'vue-router'
import { Button } from '@/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from '@/components/ui/dropdown-menu'
</script>
