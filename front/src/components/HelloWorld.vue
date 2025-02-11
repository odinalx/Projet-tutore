<script setup lang="ts">
import { ref } from 'vue'
import { Button } from '@/components/ui/button'

defineProps<{ msg: string }>()

const count = ref(0)
</script>
<template>
  <div>
    <Button @click="count++">{{ count }}</Button>
    <Button>{{ msg }}</Button>
  </div>
</template>

<style scoped></style>
