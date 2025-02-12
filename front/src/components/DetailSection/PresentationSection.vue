<template>
    <div class="p-8">
      <Card class="w-full h-64 flex items-center justify-center">
        <CardContent class="text-lg font-bold">{{ title }}</CardContent>
      </Card>
      <div class="mt-6 flex gap-6">
        <div class="flex-1">
          <h2 class="text-xl font-bold">Presentation</h2>
          <p class="mt-2 text-sm">
            {{ description }}
          </p>
          <RouterLink to="/inscription">
          <Button class="mt-4">S'inscrire</Button>
        </RouterLink>
        </div>
        <Card class="w-32 h-32 flex items-center justify-center">
          <CardContent>Image</CardContent>
        </Card>
      </div>
    </div>
  </template>
  
  <script setup>
    import { Card, CardContent } from "@/components/ui/card";
    import { Button } from "@/components/ui/button";
    defineProps({ title: String, description: String });
  </script>