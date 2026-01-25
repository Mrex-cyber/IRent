<template>
  <v-container fluid class="menu-container-fluid pa-0">
    <div class="menu-page">
      <div class="menu-container">
        <div class="top-header">
          <div class="grid-icon-placeholder"></div>
          <button class="home-link-button">
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

        <div class="menu-header">
          <div>MENU</div>
        </div>

        <div class="menu-grid">
          <router-link to="/apartments" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M17 8h2a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h2"
                />
                <path d="M9 6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4H9V6z" />
                <path
                  d="M12 14l3-3 3 3"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <line
                  x1="12"
                  y1="14"
                  x2="12"
                  y2="18"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
              </svg>
            </div>
            <span class="menu-label">Apartments</span>
          </router-link>

          <router-link to="/members" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="9" cy="7" r="4" />
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="17" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
            </div>
            <span class="menu-label">Members</span>
          </router-link>

          <router-link to="/tasks" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <path
                  d="M14 2v6h6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <line
                  x1="16"
                  y1="13"
                  x2="8"
                  y2="13"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <line
                  x1="16"
                  y1="17"
                  x2="8"
                  y2="17"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                />
                <polyline
                  points="10 9 11 10 14 7"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
            <span class="menu-label">Tasks</span>
          </router-link>

          <router-link to="/activities" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M12 5v14M5 12h14"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  fill="none"
                />
                <path
                  d="M7 9l5-5 5 5"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <path
                  d="M7 15l5 5 5-5"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
            <span class="menu-label">Activities</span>
          </router-link>

          <router-link to="/analytics" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                <path d="M22 12A10 10 0 0 0 12 2v10z" />
              </svg>
            </div>
            <span class="menu-label">Analytics</span>
          </router-link>

          <router-link to="/settings" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                <path
                  d="M12 1v6m0 6v6m11-9h-6m-6 0H1m17.364-6.364l-4.243 4.243m-4.242-4.243L6.636 6.636m12.728 12.728l-4.243-4.243m-4.242 4.243L6.636 17.364"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
              </svg>
            </div>
            <span class="menu-label">Settings</span>
          </router-link>

          <router-link to="/management" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <rect
                  x="3"
                  y="3"
                  width="18"
                  height="18"
                  rx="2"
                  ry="2"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                />
                <line x1="9" y1="3" x2="9" y2="21" stroke="currentColor" stroke-width="2" />
                <line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="2" />
              </svg>
            </div>
            <span class="menu-label">Management</span>
          </router-link>

          <router-link to="/auth/login" class="menu-item">
            <div class="menu-button">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                <polyline points="10 17 15 12 10 7" />
                <line x1="15" y1="12" x2="3" y2="12" />
              </svg>
            </div>
            <span class="menu-label">Login</span>
          </router-link>
        </div>

        <div class="bottom-navigation">
          <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="15 18 9 12 15 6" />
          </svg>
          <div class="nav-divider"></div>
          <svg
            width="16"
            height="16"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <polyline points="9 18 15 12 9 6" />
          </svg>
        </div>
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
        <v-card-text>
          <v-date-picker v-model="selectedDate" show-adjacent-months></v-date-picker>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'

const authStore = useAuthStore()
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])

onMounted(async () => {
  if (authStore.token && !authStore.user) {
    await authStore.fetchUser()
  }
})

const isAdmin = computed(() => authStore.isAdmin)
</script>

<style scoped>
.menu-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.menu-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.menu-page {
  min-height: 100vh;
  background-color: #ffffff;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.menu-container {
  max-width: 30rem;
  margin: 0 auto;
  width: 100%;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.top-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
  width: 100%;
}

.grid-icon-placeholder {
  width: 2.5rem;
  height: 2.5rem;
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

.action-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.back-button {
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

.back-button:hover {
  background-color: #f5f5f5;
}

.right-action-buttons {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.action-button {
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

.action-button:hover {
  background-color: #f5f5f5;
}

.menu-header {
  margin-bottom: 2rem;
}

.menu-header h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  text-transform: uppercase;
  letter-spacing: 0.0625rem;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.menu-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: auto;
}

.menu-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s;
}

.menu-item:hover {
  transform: translateY(-0.125rem);
}

.menu-button {
  width: 6.25rem;
  height: 6.25rem;
  border-radius: 1rem;
  background: linear-gradient(
    to bottom,
    #ffffff 0%,
    rgba(185, 208, 255, 0.925) 50%,
    rgba(62, 126, 255, 0.8) 100%
  );
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.75rem;
  color: #ffffff;
  box-shadow:
    0 0.25rem 0.75rem rgba(32, 78, 246, 0.3),
    inset 0 0.0625rem 0 rgba(255, 255, 255, 0.2),
    inset 0 -0.0625rem 0 rgba(0, 0, 0, 0.1);
}

.menu-label {
  font-size: 0.875rem;
  font-weight: 500;
  color: #000;
  text-align: center;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.bottom-navigation {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  background-color: #424242;
  border-radius: 1.5rem;
  padding: 0.75rem 1.5rem;
  margin-top: 2rem;
  width: fit-content;
  margin-left: auto;
  margin-right: auto;
}

.bottom-navigation svg {
  color: #ffffff;
  cursor: pointer;
}

.nav-divider {
  width: 0.0625rem;
  height: 1.25rem;
  background-color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 30rem) {
  .menu-page {
    padding: 1rem;
  }

  .menu-grid {
    gap: 1rem;
  }

  .menu-button {
    width: 5rem;
    height: 5rem;
  }

  .menu-label {
    font-size: 0.75rem;
  }
}
</style>
