<template>
  <v-container fluid class="members-container-fluid pa-0">
    <div class="members-page">
      <div class="members-container">
        <div class="top-header">
          <button class="grid-icon-button" @click="$router.push('/menu')">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <rect x="3" y="3" width="7" height="7" rx="1" />
              <rect x="14" y="3" width="7" height="7" rx="1" />
              <rect x="3" y="14" width="7" height="7" rx="1" />
              <rect x="14" y="14" width="7" height="7" rx="1" />
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

        <div class="members-overview-section">
          <h3 class="section-title">Condo members</h3>
          <div class="members-chart-container">
            <div class="donut-chart">
              <svg width="180" height="180" viewBox="0 0 180 180">
                <circle cx="90" cy="90" r="80" fill="none" stroke="#e0e0e0" stroke-width="30" />
                <circle
                  cx="90"
                  cy="90"
                  r="80"
                  fill="none"
                  stroke="#90caf9"
                  stroke-width="30"
                  stroke-dasharray="94.7"
                  stroke-dashoffset="0"
                  transform="rotate(-90 90 90)"
                />
                <circle
                  cx="90"
                  cy="90"
                  r="80"
                  fill="none"
                  stroke="#B9D0FF"
                  stroke-width="30"
                  stroke-dasharray="94.7"
                  stroke-dashoffset="47.35"
                  transform="rotate(-90 90 90)"
                />
                <circle
                  cx="90"
                  cy="90"
                  r="80"
                  fill="none"
                  stroke="#3E7EFF"
                  stroke-width="30"
                  stroke-dasharray="94.7"
                  stroke-dashoffset="75.76"
                  transform="rotate(-90 90 90)"
                />
                <circle
                  cx="90"
                  cy="90"
                  r="80"
                  fill="none"
                  stroke="#ba68c8"
                  stroke-width="30"
                  stroke-dasharray="94.7"
                  stroke-dashoffset="100.24"
                  transform="rotate(-90 90 90)"
                />
              </svg>
              <div class="chart-center">
                <div class="chart-text">26</div>
                <div class="chart-label">members</div>
              </div>
            </div>
            <button class="check-button">
              <span>Check</span>
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
            </button>
          </div>

          <div class="members-legend">
            <div class="legend-item">
              <div class="legend-circle managers"></div>
              <span>Managers</span>
              <span class="legend-count">4</span>
            </div>
            <div class="legend-item">
              <div class="legend-circle realtors"></div>
              <span>Realtors</span>
              <span class="legend-count">3</span>
            </div>
            <div class="legend-item">
              <div class="legend-circle cleaning"></div>
              <span>Cleaning</span>
              <span class="legend-count">12</span>
            </div>
            <div class="legend-item">
              <div class="legend-circle technicians"></div>
              <span>Technicians</span>
              <span class="legend-count">7</span>
            </div>
          </div>
        </div>

        <div class="member-cards-section">
          <div v-for="(member, index) in members" :key="member.id" class="member-card">
            <div class="member-avatar">
              <img :src="member.avatar || '/placeholder-avatar.jpg'" :alt="member.name" />
            </div>
            <div class="member-info">
              <h4 class="member-name">{{ member.name }}</h4>
              <p class="member-title">{{ member.title }}</p>
              <div class="member-contact">
                <div class="contact-item">
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
                    <path
                      d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                    />
                  </svg>
                  <span>{{ member.phone }}</span>
                </div>
                <div class="contact-item">
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
                    <path
                      d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                    />
                    <polyline points="22,6 12,13 2,6" />
                  </svg>
                  <span>{{ member.email }}</span>
                </div>
              </div>
              <div v-if="member.responsibility" class="entrance-responsibility">
                <span class="responsibility-label">Entrance responsibility:</span>
                <div class="entrance-buttons">
                  <button v-for="num in member.responsibility" :key="num" class="entrance-btn">
                    {{ num }}
                  </button>
                </div>
              </div>
              <button class="message-button" @click="contactMember(member)">
                <span>Message</span>
              </button>
            </div>
            <div v-if="index < members.length - 1" class="member-navigation">
              <button class="nav-button">
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="white"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="15 18 9 12 15 6" />
                </svg>
              </button>
              <div class="nav-divider"></div>
              <button class="nav-button">
                <svg
                  width="16"
                  height="16"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="white"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                >
                  <polyline points="9 18 15 12 9 6" />
                </svg>
              </button>
            </div>
          </div>
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
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])

const members = ref([
  {
    id: 1,
    name: 'Tom Smith',
    title: 'Chairman of the condominium',
    phone: '+38 099 999 99 99',
    email: 'tomsmith@gmail.com',
    avatar: '/placeholder-avatar.jpg',
    responsibility: [1, 2, 3, 4]
  },
  {
    id: 2,
    name: 'Tom Smith',
    title: 'the condominium',
    phone: '+38 099 999 99 99',
    email: 'tomsmith2@gmail.com',
    avatar: '/placeholder-avatar.jpg',
    responsibility: [1, 2]
  }
])

const contactMember = (member: any) => {
  router.push('/chat')
}
</script>

<style scoped>
.members-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.members-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.members-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.members-container {
  max-width: 480px;
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
  margin-bottom: 16px;
  width: 100%;
  background-color: #ffffff;
  padding: 16px;
  border-radius: 16px;
}

.grid-icon-button {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #000;
  padding: 0;
}

.grid-icon-button:hover {
  background-color: #f5f5f5;
}

.top-right-icons {
  display: flex;
  align-items: center;
  gap: 8px;
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
  margin-bottom: 24px;
  padding: 0 16px;
}

.back-button {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1.5px solid #000;
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

.members-overview-section {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 24px;
}

.section-title {
  font-size: 16px;
  font-weight: 700;
  color: #000;
  margin-bottom: 16px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.members-chart-container {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}

.check-button {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #1976d2;
  color: #ffffff;
  border: none;
  border-radius: 20px;
  padding: 8px 16px;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: background-color 0.2s;
  display: flex;
  align-items: center;
  gap: 6px;
}

.check-button:hover {
  background-color: #1565c0;
}

.donut-chart {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chart-center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.chart-text {
  font-size: 28px;
  font-weight: 700;
  color: #1976d2;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chart-label {
  font-size: 12px;
  color: #666;
  margin-top: -4px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.members-legend {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.legend-circle {
  width: 16px;
  height: 16px;
  border-radius: 50%;
  flex-shrink: 0;
}

.legend-circle.managers {
  background-color: #90caf9;
}

.legend-circle.realtors {
  background-color: #b9d0ff;
}

.legend-circle.cleaning {
  background-color: #3e7eff;
}

.legend-circle.technicians {
  background-color: #ba68c8;
}

.legend-count {
  font-weight: 600;
  margin-left: auto;
}

.member-cards-section {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.member-card {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  gap: 16px;
  position: relative;
}

.member-avatar {
  flex-shrink: 0;
}

.member-avatar img {
  width: 80px;
  height: 80px;
  border-radius: 12px;
  object-fit: cover;
  border: 2px solid #e0e0e0;
}

.member-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.member-name {
  font-size: 18px;
  font-weight: 600;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-title {
  font-size: 14px;
  color: #1976d2;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-contact {
  display: flex;
  flex-direction: column;
  gap: 4px;
  margin-top: 4px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: #333;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.contact-item svg {
  color: #666;
  flex-shrink: 0;
}

.entrance-responsibility {
  margin-top: 8px;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.responsibility-label {
  font-size: 13px;
  color: #666;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.entrance-buttons {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.entrance-btn {
  width: 36px;
  height: 36px;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  background: #ffffff;
  color: #333;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: all 0.2s;
}

.entrance-btn:hover {
  border-color: #1976d2;
  color: #1976d2;
}

.message-button {
  background: #1976d2;
  color: #ffffff;
  border: none;
  border-radius: 24px;
  padding: 10px 24px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: background-color 0.2s;
  margin-top: 12px;
  width: fit-content;
}

.message-button:hover {
  background-color: #1565c0;
}

.member-navigation {
  position: absolute;
  bottom: 16px;
  right: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background-color: #424242;
  border-radius: 20px;
  padding: 8px 16px;
}

.nav-button {
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

.nav-divider {
  width: 1px;
  height: 20px;
  background-color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 480px) {
  .members-page {
    padding: 16px;
  }

  .top-header {
    padding: 12px;
    border-radius: 12px;
  }

  .member-avatar img {
    width: 60px;
    height: 60px;
  }

  .members-chart-container {
    margin-bottom: 12px;
  }

  .member-navigation {
    bottom: 12px;
    right: 12px;
    padding: 6px 12px;
  }
}
</style>
