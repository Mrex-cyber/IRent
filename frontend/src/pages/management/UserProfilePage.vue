<template>
  <v-container fluid class="pa-0">
    <div class="user-profile-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">User Profile</h1>
            <p class="page-subtitle">View and manage profile information</p>
          </div>
          <div class="header-actions">
            <button @click="handleLogout" title="Logout">
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                <polyline points="16 17 21 12 16 7" />
                <line x1="21" y1="12" x2="9" y2="12" />
              </svg>
              Logout
            </button>
            <button class="edit-profile-button" @click="openEditDialog">
              <svg
                width="16"
                height="16"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
              </svg>
              <span>Edit Profile</span>
            </button>
          </div>
        </div>

        <div class="profile-content">
          <div class="left-column">
            <div class="info-card">
              <div class="profile-info-content">
                <div class="profile-picture">
                  <img
                    v-if="userProfile.avatar"
                    :src="userProfile.avatar"
                    :alt="userProfile.firstName + ' ' + userProfile.lastName"
                  />
                  <svg v-else width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                </div>
                <div class="profile-info-fields">
                  <div class="name-row">
                    <div class="info-field">
                      <span class="field-label">First Name</span>
                      <span class="field-value">{{ displayOptional(userProfile.firstName) }}</span>
                    </div>
                    <div class="info-field">
                      <span class="field-label">Last Name</span>
                      <span class="field-value">{{ displayOptional(userProfile.lastName) }}</span>
                    </div>
                  </div>
                  <div class="info-field">
                    <span class="field-label">Role</span>
                    <span class="field-value">{{ displayOptional(userProfile.role) }}</span>
                  </div>
                  <div class="info-field">
                    <span class="field-label">Account</span>
                    <span class="field-value">{{ displayOptional(userProfile.userType) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="info-card">
              <h3 class="card-title">Contact Information</h3>
              <div class="contact-item">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                  />
                </svg>
                <span>{{ displayOptional(userProfile.phone) }}</span>
              </div>
              <div class="contact-item">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path
                    d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                  />
                  <polyline points="22,6 12,13 2,6" />
                </svg>
                <span>{{ displayOptional(userProfile.email) }}</span>
              </div>
              <div class="contact-item">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>{{ displayOptional(userProfile.address) }}</span>
              </div>
            </div>

            <div class="info-card">
              <h3 class="card-title">Entrance Responsibility</h3>
              <div v-if="userProfile.responsibleEntrances.length" class="entrance-pills">
                <span
                  v-for="ent in userProfile.responsibleEntrances"
                  :key="ent.id"
                  class="entrance-pill"
                  >{{ entranceLabel(ent) }}</span
                >
              </div>
              <p v-else class="entrance-empty">{{ emDash }}</p>
            </div>
          </div>

          <div class="right-column">
            <div class="info-card">
              <h3 class="card-title">Workload Statistics</h3>
              <div class="workload-item">
                <div class="workload-header">
                  <span class="workload-label">Total Requests:</span>
                  <span class="workload-value">{{ workloadStats.totalRequests }}</span>
                </div>
                <div class="workload-bar">
                  <div
                    class="workload-fill blue"
                    :style="{
                      width: workloadStats.totalRequests ? '100%' : '0%'
                    }"
                  ></div>
                </div>
              </div>
              <div class="workload-item">
                <div class="workload-header">
                  <span class="workload-label">Resolved:</span>
                  <span class="workload-value">{{ workloadStats.resolved }}</span>
                </div>
                <div class="workload-bar">
                  <div
                    class="workload-fill green"
                    :style="{
                      width: `${workloadStats.totalRequests ? (workloadStats.resolved / workloadStats.totalRequests) * 100 : 0}%`
                    }"
                  ></div>
                </div>
              </div>
              <div class="workload-item">
                <div class="workload-header">
                  <span class="workload-label">Unresolved:</span>
                  <span class="workload-value">{{ workloadStats.unresolved }}</span>
                </div>
                <div class="workload-bar">
                  <div
                    class="workload-fill orange"
                    :style="{
                      width: `${workloadStats.totalRequests ? (workloadStats.unresolved / workloadStats.totalRequests) * 100 : 0}%`
                    }"
                  ></div>
                </div>
              </div>
            </div>

            <div class="info-card">
              <h3 class="card-title">Quick Info</h3>
              <div class="quick-info-item">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                  <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <div class="quick-info-content">
                  <span class="quick-info-label">Bio:</span>
                  <span class="quick-info-value">{{ displayOptional(userProfile.bio) }}</span>
                </div>
              </div>
              <div class="quick-info-item">
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
                <div class="quick-info-content">
                  <span class="quick-info-label">Member Since:</span>
                  <span class="quick-info-value">{{
                    displayOptional(userProfile.memberSince)
                  }}</span>
                </div>
              </div>
              <div class="quick-info-item">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                <div class="quick-info-content">
                  <span class="quick-info-label">Apartment:</span>
                  <span class="quick-info-value">{{ displayOptional(userProfile.apartment) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="activity-history-card">
          <h3 class="card-title">Activity History</h3>
          <p v-if="activityHistory.length === 0" class="activity-empty">{{ emDash }}</p>
          <div v-else class="activity-list">
            <div v-for="activity in activityHistory" :key="activity.id" class="activity-item">
              <div class="activity-content">
                <span class="activity-description">{{ activity.description }}</span>
                <span class="activity-date"
                  >({{ activity.date }} <span class="activity-id">#{{ activity.id }}</span
                  >)</span
                >
              </div>
              <span
                :class="['activity-tag', `activity-tag--${activityTagModifier(activity.status)}`]"
              >
                {{ activity.status }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-dialog v-model="showEditDialog" max-width="600">
      <v-card>
        <v-card-title>Edit Profile</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="editForm.firstName"
            label="First Name"
            variant="outlined"
          ></v-text-field>
          <v-text-field
            v-model="editForm.lastName"
            label="Last Name"
            variant="outlined"
          ></v-text-field>
          <v-text-field v-model="editForm.phone" label="Phone" variant="outlined"></v-text-field>
          <v-text-field v-model="editForm.email" label="Email" variant="outlined"></v-text-field>
          <v-text-field
            v-model="editForm.address"
            label="Address"
            variant="outlined"
          ></v-text-field>
          <v-textarea v-model="editForm.bio" label="Bio" variant="outlined" rows="3"></v-textarea>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="showEditDialog = false">Cancel</v-btn>
          <v-btn color="primary" @click="saveProfile">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'
import apiClient from '@/services/api/client'

interface Activity {
  id: number
  description: string
  date: string
  status: string
}

interface ResponsibleEntrance {
  id: number
  name: string | null
  buildingAddress: string | null
}

const authStore = useAuthStore()
const showEditDialog = ref(false)

const emDash = '—'

const displayOptional = (v: string | null | undefined): string => {
  if (v === null || v === undefined) return emDash
  const s = String(v).trim()
  return s === '' ? emDash : s
}

const entranceLabel = (ent: ResponsibleEntrance): string => {
  const parts = [ent.name, ent.buildingAddress].filter(
    (p) => p != null && String(p).trim() !== ''
  ) as string[]
  return parts.length ? parts.join(' · ') : `ID ${ent.id}`
}

const activityTagModifier = (raw: string): string => {
  const s = raw
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '')
  if (s.includes('completed') || s === 'completed') return 'completed'
  if (s.includes('progress') || s === 'in-progress') return 'in-progress'
  if (s.includes('pending')) return 'pending'
  return 'neutral'
}

const userProfile = ref({
  firstName: '',
  lastName: '',
  role: '',
  userType: '',
  phone: '',
  email: '',
  address: '',
  avatar: null as string | null,
  bio: '' as string | null,
  responsibleEntrances: [] as ResponsibleEntrance[],
  memberSince: '',
  apartment: '' as string | null
})

const workloadStats = ref({
  totalRequests: 0,
  resolved: 0,
  unresolved: 0
})

const activityHistory = ref<Activity[]>([])

const editForm = ref({
  firstName: '',
  lastName: '',
  phone: '',
  email: '',
  address: '',
  bio: ''
})

const openEditDialog = () => {
  editForm.value = {
    firstName: userProfile.value.firstName ?? '',
    lastName: userProfile.value.lastName ?? '',
    phone: userProfile.value.phone ?? '',
    email: userProfile.value.email ?? '',
    address: userProfile.value.address ?? '',
    bio: userProfile.value.bio ?? ''
  }
  showEditDialog.value = true
}

const saveProfile = async () => {
  try {
    const { data } = await apiClient.put('management/profile', editForm.value)
    Object.assign(userProfile.value, data)
    showEditDialog.value = false
  } catch {}
}

const fetchUserProfile = async () => {
  try {
    const { data } = await apiClient.get('management/profile')
    Object.assign(userProfile.value, {
      ...data,
      responsibleEntrances: Array.isArray(data.responsibleEntrances)
        ? data.responsibleEntrances
        : []
    })
    workloadStats.value = data.workloadStats ?? workloadStats.value
    activityHistory.value = Array.isArray(data.activityHistory) ? data.activityHistory : []
  } catch {}
}

const handleLogout = () => {
  authStore.logout()
}

onMounted(() => {
  fetchUserProfile()
})
</script>

<style scoped>
.user-profile-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 75rem;
  margin: 0 auto;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.header-content {
  flex: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 500;
  color: #212121;
  margin: 0 0 0.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.page-subtitle {
  font-size: 1rem;
  color: #757575;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.edit-profile-button {
  padding: 0.75rem 1.5rem;
  background-color: #204ef6;
  border: none;
  border-radius: 0.5rem;
  color: #ffffff;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.edit-profile-button:hover {
  background-color: #1a3dd4;
  transform: translateY(-0.125rem);
}

.profile-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.left-column,
.right-column {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-card {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.card-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 1rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.profile-info-content {
  display: flex;
  gap: 1.5rem;
  align-items: flex-start;
}

.profile-picture {
  width: 6rem;
  height: 6rem;
  border-radius: 50%;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  flex-shrink: 0;
}

.profile-info-fields {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.profile-picture img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.profile-picture svg {
  color: #757575;
}

.name-row {
  display: flex;
  gap: 2rem;
}

.name-row .info-field {
  flex: 1;
}

.info-field {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.field-label {
  font-size: 0.875rem;
  color: #757575;
}

.field-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 0;
  border-bottom: 0.0625rem solid #e0e0e0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.contact-item:last-child {
  border-bottom: none;
}

.contact-item svg {
  color: #757575;
  flex-shrink: 0;
}

.contact-item span {
  font-size: 0.875rem;
  color: #212121;
}

.entrance-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.entrance-pill {
  padding: 0.375rem 0.75rem;
  border: 0.125rem solid #204ef6;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: #204ef6;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.entrance-empty {
  margin: 0;
  font-size: 0.875rem;
  color: #757575;
}

.workload-item {
  margin-bottom: 1rem;
}

.workload-item:last-child {
  margin-bottom: 0;
}

.workload-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.workload-label {
  font-size: 0.875rem;
  color: #212121;
}

.workload-value {
  font-size: 0.875rem;
  font-weight: 600;
  color: #212121;
}

.workload-bar {
  height: 0.5rem;
  background-color: #e0e0e0;
  border-radius: 0.25rem;
  overflow: hidden;
}

.workload-fill {
  height: 100%;
  border-radius: 0.25rem;
}

.workload-fill.blue {
  background-color: #204ef6;
}

.workload-fill.green {
  background-color: #dcfce7;
}

.workload-fill.orange {
  background-color: #ffd572;
}

.quick-info-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 0;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.quick-info-item:last-child {
  border-bottom: none;
}

.quick-info-item svg {
  color: #757575;
  flex-shrink: 0;
}

.quick-info-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.quick-info-label {
  font-size: 0.75rem;
  color: #757575;
}

.quick-info-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.activity-history-card {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.activity-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.activity-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #fafafa;
  border-radius: 0.5rem;
}

.activity-content {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.activity-description {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.activity-date {
  font-size: 0.75rem;
  color: #757575;
}

.activity-id {
  color: #204ef6;
  font-weight: 500;
}

.activity-empty {
  margin: 0;
  padding: 1rem;
  font-size: 0.875rem;
  color: #757575;
}

.activity-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.activity-tag--completed {
  background-color: #dcfce7;
  color: #166534;
  border-radius: 5px;
  padding: 0.25rem 0.75rem;
}

.activity-tag--in-progress {
  background-color: #ffd572;
  color: #92400e;
  border-radius: 5px;
  padding: 0.25rem 0.75rem;
}

.activity-tag--pending {
  background-color: #e0e7ff;
  color: #3730a3;
  border-radius: 5px;
  padding: 0.25rem 0.75rem;
}

.activity-tag--neutral {
  background-color: #f3f4f6;
  color: #374151;
  border-radius: 5px;
  padding: 0.25rem 0.75rem;
}

@media (max-width: 48rem) {
  .profile-content {
    grid-template-columns: 1fr;
  }

  .header-section {
    flex-direction: column;
    gap: 1rem;
  }

  .edit-profile-button {
    width: 100%;
  }
}
</style>
