<template>
  <header class="bg-gray-200 p-4 flex justify-between items-center">
    <RouterLink to="/" class="font-bold">LOGO ASSOCIATION</RouterLink>
    <nav class="flex gap-4">
      <RouterLink to="/" class="hover:underline">Accueil</RouterLink>
      <RouterLink to="/sections" class="hover:underline">Sections</RouterLink>
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button variant="link">Mes Sections ▼</Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-40">
          <DropdownMenuItem>Section 1</DropdownMenuItem>
          <DropdownMenuItem>Section 2</DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
      <Button variant="ghost" class="rounded-full p-2">👤</Button>
    </nav>
  </header>
</template>

<script setup>
import { RouterLink } from "vue-router";
import { Button } from "@/components/ui/button";
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem } from "@/components/ui/dropdown-menu";
</script>
