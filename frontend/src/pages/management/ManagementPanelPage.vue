<template>
  <v-container fluid class="pa-0">
    <div class="management-panel">
      <div class="panel-container">
        <div class="top-header">
          <div class="grid-icon-placeholder"></div>
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
        </div>

        <div class="panel-header">
          <h1>MANAGEMENT PANEL</h1>
        </div>

        <div class="navigation-menu">
          <router-link to="/management/news" class="nav-item" active-class="active">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M18 8A2 2 0 0 0 20 6H4A2 2 0 0 0 2 8v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8z"
                />
                <polyline
                  points="22 6 12 13 2 6"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
            <span class="nav-label">News & Announcements</span>
          </router-link>

          <router-link to="/management/requests" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
              </svg>
            </div>
            <span class="nav-label">Requests Chat</span>
          </router-link>

          <router-link to="/management/tasks" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline
                  points="14 2 14 8 20 8"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <line
                  x1="16"
                  y1="13"
                  x2="8"
                  y2="13"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
                <line
                  x1="16"
                  y1="17"
                  x2="8"
                  y2="17"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
              </svg>
            </div>
            <span class="nav-label">Task Load Overview</span>
          </router-link>

          <router-link to="/management/buildings" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path
                  d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9v0M9 12v0M9 15v0M9 18v0"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  fill="none"
                />
              </svg>
            </div>
            <span class="nav-label">Buildings & Apartments</span>
          </router-link>

          <router-link to="/management/members" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
            </div>
            <span class="nav-label">Members Directory</span>
          </router-link>

          <router-link to="/management/profile" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
            </div>
            <span class="nav-label">User Profile</span>
          </router-link>

          <router-link to="/management/analytics" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <line
                  x1="18"
                  y1="20"
                  x2="18"
                  y2="10"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
                <line
                  x1="12"
                  y1="20"
                  x2="12"
                  y2="4"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
                <line
                  x1="6"
                  y1="20"
                  x2="6"
                  y2="14"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
              </svg>
            </div>
            <span class="nav-label">Analytics</span>
          </router-link>

          <router-link to="/management/settings" class="nav-item">
            <div class="nav-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2" />
                <path
                  d="M12 1v6m0 6v6m11-9h-6m-6 0H1m17.364-6.364l-4.243 4.243m-4.242-4.243L6.636 6.636m12.728 12.728l-4.243-4.243m-4.242 4.243L6.636 17.364"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                />
              </svg>
            </div>
            <span class="nav-label">Settings</span>
          </router-link>
        </div>

        <div class="user-profile">
          <div class="profile-avatar">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
          </div>
          <div class="profile-info">
            <div class="profile-name">{{ user?.firstName }} {{ user?.lastName }}</div>
            <div class="profile-role">Chairman</div>
          </div>
        </div>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/store/auth'
import { computed } from 'vue'

const authStore = useAuthStore()
const user = computed(() => authStore.user)
</script>

<style scoped>
.management-panel {
  min-height: 100vh;
  background-color: #ffffff;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.panel-container {
  max-width: 30rem;
  margin: 0 auto;
  width: 100%;
  display: flex;
  flex-direction: column;
  flex: 1;
  padding: 1.5rem 1rem;
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

.home-link-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background-color: #204ef6;
  border: none;
  border-radius: 1.5rem;
  color: #ffffff;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.2s;
}

.home-link-button:hover {
  transform: translateY(-0.125rem);
}

.panel-header {
  margin-bottom: 2rem;
}

.panel-header h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  text-transform: uppercase;
  letter-spacing: 0.0625rem;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.navigation-menu {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 0.75rem;
  text-decoration: none;
  color: #000;
  transition: all 0.2s ease;
  background-color: transparent;
  cursor: pointer;
  position: relative;
}

.nav-item:hover {
  background-color: #e3f2fd;
  transform: translateX(0.25rem);
  box-shadow: 0 0.125rem 0.5rem rgba(32, 78, 246, 0.2);
}

.nav-item.active,
.nav-item.router-link-active {
  background: linear-gradient(
    to bottom,
    #ffffff 0%,
    rgba(185, 208, 255, 0.925) 50%,
    rgba(62, 126, 255, 0.8) 100%
  );
  color: #ffffff;
  box-shadow: 0 0.25rem 0.75rem rgba(32, 78, 246, 0.3);
}

.nav-item.active:hover,
.nav-item.router-link-active:hover {
  transform: translateX(0.25rem);
  box-shadow: 0 0.375rem 1rem rgba(32, 78, 246, 0.4);
}

.nav-icon {
  width: 2.5rem;
  height: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.nav-label {
  font-size: 1rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(
    to bottom,
    #ffffff 0%,
    rgba(185, 208, 255, 0.925) 50%,
    rgba(62, 126, 255, 0.8) 100%
  );
  border-radius: 1rem;
  margin-top: 2rem;
  color: #ffffff;
}

.profile-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.profile-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.profile-name {
  font-size: 1rem;
  font-weight: 600;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.profile-role {
  font-size: 0.875rem;
  font-weight: 400;
  opacity: 0.9;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 30rem) {
  .panel-container {
    padding: 1rem 0.75rem;
  }

  .nav-item {
    padding: 0.75rem;
  }

  .nav-icon {
    width: 2rem;
    height: 2rem;
  }

  .nav-label {
    font-size: 0.875rem;
  }
}
</style>
