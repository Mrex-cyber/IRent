<template>
  <div class="top-header">
    <button class="back-icon-button" @click="$router.push('/menu')">
      <svg
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <polyline points="15 18 9 12 15 6" />
      </svg>
    </button>
    <button class="home-link-button" @click="$router.push('/menu')">
      <svg
        width="20"
        height="20"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
      >
        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
        <polyline points="9 22 9 12 15 12 15 22" />
      </svg>
      <span>Home Link</span>
    </button>
    <div class="top-right-icons">
      <button class="top-icon-button">
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
        >
          <line x1="21" y1="6" x2="7" y2="6" />
          <line x1="21" y1="12" x2="7" y2="12" />
          <line x1="21" y1="18" x2="7" y2="18" />
          <circle cx="4" cy="6" r="2" />
          <circle cx="4" cy="12" r="2" />
          <circle cx="4" cy="18" r="2" />
        </svg>
      </button>
      <button class="top-icon-button">
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <circle cx="11" cy="11" r="8" />
          <path d="M21 21l-4.35-4.35" />
        </svg>
      </button>
      <button class="top-icon-button" @click="showCalendar = true">
        <svg
          width="20"
          height="20"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
        >
          <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
          <line x1="16" y1="2" x2="16" y2="6" />
          <line x1="8" y1="2" x2="8" y2="6" />
          <line x1="3" y1="10" x2="21" y2="10" />
        </svg>
      </button>
    </div>
  </div>

  <v-dialog v-model="showCalendar" max-width="400">
    <v-card>
      <v-card-title>
        <v-spacer></v-spacer>
        <button class="close-button" @click="showCalendar = false">
          <svg
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </v-card-title>
      <v-date-picker v-model="selectedDate" show-adjacent-months></v-date-picker>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'

const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])
</script>

<style scoped>
.top-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
  width: 100%;
  background-color: #ffffff;
  padding: 1rem;
  border-radius: 1rem;
}

.back-icon-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  border: 0.09375rem solid #000;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #000;
  padding: 0;
  transition: all 0.2s;
}

.back-icon-button:hover {
  background-color: #f5f5f5;
}

.top-right-icons {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.top-icon-button {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  border: 0.09375rem solid #000;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #000;
  padding: 0;
  transition: all 0.2s;
}

.top-icon-button:hover {
  background-color: #f5f5f5;
}

.close-button {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;
  padding: 0;
  transition: all 0.2s;
}

.close-button:hover {
  background-color: #f5f5f5;
}

@media (max-width: 30rem) {
  .top-header {
    padding: 0.75rem;
    border-radius: 0.75rem;
  }
}
</style>
