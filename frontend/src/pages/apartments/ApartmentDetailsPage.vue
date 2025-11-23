<template>
  <v-container fluid class="apartment-details-container pa-0">
    <div class="apartment-details-page">
      <div class="apartment-details-container">
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
          </div>
        </div>

        <div class="action-row">
          <div class="right-action-buttons">
            <button class="action-button">
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
            <button class="action-button" @click="showCalendar = true">
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

        <div v-if="landlord" class="owner-profile-card">
          <div class="profile-image-wrapper">
            <img
              :src="landlord.avatar || '/placeholder-avatar.jpg'"
              :alt="`${landlord.firstName} ${landlord.lastName}`"
              class="profile-image"
            />
          </div>
          <div class="profile-info">
            <h2 class="owner-name">{{ landlord.firstName }} {{ landlord.lastName }}</h2>
            <p class="owner-role">{{ getOwnerRole() }}</p>
            <div class="contact-info">
              <div class="contact-item">
                <svg
                  width="18"
                  height="18"
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
                <span>{{ landlord.phoneNumber || '+38 099 999 99 99' }}</span>
              </div>
              <div class="contact-item">
                <svg
                  width="18"
                  height="18"
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
                <span>{{ landlord.email }}</span>
              </div>
            </div>
            <button class="message-button" @click="contactOwner">
              <span>Message</span>
            </button>
          </div>
        </div>

        <div class="payment-history-section">
          <h3 class="section-title">PAYMENT HISTORY</h3>
          <div class="payment-list">
            <div v-for="payment in paymentHistory" :key="payment.id" class="payment-item">
              <div class="payment-icon">
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
                  <path v-if="payment.type === 'Electricity'" d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                  <path
                    v-if="payment.type === 'Gas'"
                    d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"
                  />
                  <path
                    v-if="payment.type === 'Water'"
                    d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"
                    fill="none"
                  />
                  <path
                    v-if="payment.type === 'Condo'"
                    d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"
                  />
                  <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
              </div>
              <div class="payment-details">
                <div class="payment-type">{{ payment.type }}</div>
                <div class="payment-date">{{ formatDate(payment.date) }}</div>
              </div>
              <div class="payment-amount">670 grn</div>
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

        <div class="requests-history-section">
          <h3 class="section-title">REQUESTS HISTORY</h3>
          <div class="request-item">
            <div class="request-avatar">
              <img src="/placeholder-avatar.jpg" alt="Request" class="avatar-image" />
            </div>
            <div class="request-details">
              <div class="request-name">{{ landlord.firstName }} {{ landlord.lastName }}</div>
              <div class="request-text">Contrary to popular belief, Lorem Ipsum is...</div>
              <div class="request-role">{{ getOwnerRole() }}</div>
            </div>
            <div class="request-meta">
              <div class="request-tag">INFORMATION REQUESTS</div>
              <div class="request-date">01.07.24</div>
            </div>
          </div>

          <div class="request-navigation">
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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useApartmentsStore } from '@/store/apartments'

const route = useRoute()
const router = useRouter()
const apartmentsStore = useApartmentsStore()

const landlord = ref<any>(null)
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])
const paymentHistory = ref([
  { id: 1, type: 'Electricity', date: new Date('2024-06-01') },
  { id: 2, type: 'Gas', date: new Date('2024-06-01') },
  { id: 3, type: 'Condo', date: new Date('2024-06-01') },
  { id: 4, type: 'Water', date: new Date('2024-06-01') }
])

const getOwnerRole = () => {
  return 'Flat owner'
}

const formatDate = (date: Date) => {
  const day = date.getDate().toString().padStart(2, '0')
  const month = (date.getMonth() + 1).toString().padStart(2, '0')
  const year = date.getFullYear().toString().slice(2)
  return `${day}.${month}.${year}`
}

const contactOwner = () => {
  router.push('/chat')
}

onMounted(async () => {
  const apartmentId = route.params.id as string
  await apartmentsStore.fetchApartment(apartmentId)

  if (apartmentsStore.currentApartment?.landlord) {
    landlord.value = apartmentsStore.currentApartment.landlord
  } else {
    landlord.value = {
      firstName: 'Tom',
      lastName: 'Smith',
      email: 'tomsmith@gmail.com',
      phoneNumber: '+38 099 999 99 99',
      avatar: '/placeholder-avatar.jpg'
    }
  }
})
</script>

<style scoped>
.apartment-details-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.apartment-details-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.apartment-details-page {
  min-height: 100vh;
  background-color: #ffffff;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.apartment-details-container {
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

.right-action-buttons {
  display: flex;
  align-items: center;
  gap: 12px;
}

.action-button {
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

.action-button:hover {
  background-color: #f5f5f5;
}

.owner-profile-card {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  padding: 20px;
  display: flex;
  gap: 16px;
  margin-bottom: 24px;
}

.profile-image-wrapper {
  flex-shrink: 0;
}

.profile-image {
  width: 100px;
  height: 100px;
  border-radius: 12px;
  object-fit: cover;
  border: 2px solid #e0e0e0;
}

.profile-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.owner-name {
  font-size: 20px;
  font-weight: 700;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.owner-role {
  font-size: 14px;
  color: #1976d2;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-top: 8px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: #333;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.contact-item svg {
  color: #666;
}

.message-button {
  background: #1976d2;
  color: #ffffff;
  border: none;
  border-radius: 24px;
  padding: 10px 24px;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: background-color 0.2s;
  margin-top: 8px;
}

.message-button:hover {
  background-color: #1565c0;
}

.payment-history-section,
.requests-history-section {
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

.payment-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 12px;
}

.payment-item {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 12px 16px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.payment-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: #e3f2fd;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #1976d2;
  flex-shrink: 0;
}

.payment-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.payment-type {
  font-size: 14px;
  font-weight: 500;
  color: #1976d2;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.payment-date {
  font-size: 13px;
  color: #666;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.payment-amount {
  font-size: 16px;
  font-weight: 600;
  color: #4caf50;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.check-button {
  background: #1976d2;
  color: #ffffff;
  border: none;
  border-radius: 24px;
  padding: 12px 24px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: background-color 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin-top: 12px;
  width: 100%;
}

.check-button:hover {
  background-color: #1565c0;
}

.request-item {
  background: #ffffff;
  border: 1px solid #e0e0e0;
  border-radius: 16px;
  padding: 16px;
  display: flex;
  gap: 12px;
  margin-bottom: 16px;
}

.request-avatar {
  flex-shrink: 0;
}

.avatar-image {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e0e0e0;
}

.request-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.request-name {
  font-size: 14px;
  font-weight: 600;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.request-text {
  font-size: 13px;
  color: #666;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.request-role {
  font-size: 12px;
  color: #1976d2;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.request-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
}

.request-tag {
  background: #ffeb3b;
  color: #000;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 10px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.request-date {
  font-size: 12px;
  color: #666;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.request-navigation {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background-color: #424242;
  border-radius: 24px;
  padding: 8px 16px;
  margin: 0 auto;
  width: fit-content;
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
  .apartment-details-page {
    padding: 16px;
  }

  .owner-profile-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .profile-info {
    width: 100%;
    align-items: center;
  }

  .message-button {
    width: 100%;
  }

  .request-item {
    flex-direction: column;
  }

  .request-meta {
    align-items: flex-start;
    width: 100%;
  }
}
</style>
