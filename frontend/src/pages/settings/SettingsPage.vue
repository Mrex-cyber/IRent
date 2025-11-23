<template>
  <v-container fluid class="pa-0">
    <div class="settings-wrapper">
      <TopNavbar />
      <v-container>
        <p>Settings page coming soon...</p>
      </v-container>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import TopNavbar from '@/components/TopNavbar.vue'
</script>

<style scoped>
.settings-wrapper {
  max-width: 30rem;
  margin: 0 auto;
  padding: 0;
  background-color: #f5f5f5;
  min-height: 100vh;
}
</style>
