<template>
  <div class="loading-container">
    <v-progress-circular
      :size="size"
      :width="width"
      :color="color"
      indeterminate
    ></v-progress-circular>
    <p v-if="text" class="mt-4 text-body-1">{{ text }}</p>
  </div>
</template>

<script setup lang="ts">
interface Props {
  size?: number | string
  width?: number | string
  color?: string
  text?: string
}

withDefaults(defineProps<Props>(), {
  size: 64,
  width: 4,
  color: 'primary',
  text: ''
})
</script>

<style scoped>
.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}
</style>
