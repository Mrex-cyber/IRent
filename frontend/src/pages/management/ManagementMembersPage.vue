<template>
  <v-container fluid class="pa-0">
    <div class="members-directory-page">
      <div class="page-header">
        <h1 class="page-title">Members Directory</h1>
        <p class="page-subtitle">View and manage all residents, tenants, and staff members</p>
      </div>

      <div class="search-section">
        <div class="search-container">
          <SearchField v-model="searchQuery" placeholder="Search members by name or email..." />
          <button class="filter-icon-button" @click="applyFilters">
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
            </svg>
          </button>
        </div>
        <div class="filter-buttons">
          <button
            v-for="filter in filters"
            :key="filter"
            :class="['filter-button', { active: activeFilter === filter }]"
            @click="setFilter(filter)"
          >
            {{ filter }}
          </button>
        </div>
      </div>

      <div class="content-layout">
        <div class="left-panel">
          <h2 class="panel-title">All Members ({{ members.length }})</h2>
          <div v-if="isLoading" class="loading-state">Loading members…</div>
          <div v-else-if="error" class="error-state">{{ error }}</div>
          <div v-else class="members-list">
            <div
              v-for="member in members"
              :key="member.id"
              :class="['member-item', { active: selectedMember?.id === member.id }]"
              @click="selectMember(member)"
            >
              <div class="member-avatar-small">
                <span>{{ getInitials(member.name) }}</span>
              </div>
              <div class="member-info-small">
                <div class="member-name-small">{{ member.name }}</div>
                <div class="member-email-small">{{ member.email }}</div>
                <div class="member-tags-small">
                  <span :class="['role-tag', getRoleTagClass(member.role)]">{{ member.role }}</span>
                  <span v-if="member.apartment" class="apartment-tag">{{ member.apartment }}</span>
                </div>
              </div>
            </div>
            <div v-if="members.length === 0" class="empty-state">
              <p>No members found</p>
            </div>
          </div>
        </div>

        <div class="right-panel">
          <div v-if="selectedMember" class="member-details">
            <div v-if="detailLoading" class="loading-state">Loading details…</div>
            <template v-else>
              <div class="info-card">
                <div class="member-header">
                  <div class="member-avatar-large">
                    <span>{{ getInitials(selectedMember.name) }}</span>
                  </div>
                  <div class="member-header-info">
                    <h3 class="member-name-large">{{ selectedMember.name }}</h3>
                    <div class="member-role-tags">
                      <span :class="['role-tag', getRoleTagClass(selectedMember.role)]">
                        {{ selectedMember.role }}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="contact-info">
                  <div class="contact-item">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                      />
                    </svg>
                    <span>{{ displayOptional(selectedMember.phone) }}</span>
                  </div>
                  <div class="contact-item">
                    <svg
                      width="16"
                      height="16"
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
                    <span>{{ selectedMember.email }}</span>
                  </div>
                  <div class="contact-item">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                      <circle cx="12" cy="10" r="3" />
                    </svg>
                    <span>{{ displayOptional(selectedMember.location) }}</span>
                  </div>
                </div>
              </div>

              <div v-if="selectedDetail" class="info-card">
                <h4 class="card-title">Activity Overview</h4>
                <div class="activity-stats">
                  <div class="stat-card blue">
                    <div class="stat-value">{{ selectedDetail.totalRequests ?? 0 }}</div>
                    <div class="stat-label">Total Requests</div>
                  </div>
                  <div class="stat-card green">
                    <div class="stat-value">{{ selectedDetail.resolved ?? 0 }}</div>
                    <div class="stat-label">Resolved</div>
                  </div>
                  <div class="stat-card orange">
                    <div class="stat-value">{{ selectedDetail.pending ?? 0 }}</div>
                    <div class="stat-label">Pending</div>
                  </div>
                </div>
              </div>

              <div v-if="selectedDetail" class="info-card">
                <h4 class="card-title">Statistics</h4>
                <div class="statistics-content">
                  <div class="stat-item">
                    <div class="stat-item-label">Resolution Rate</div>
                    <div class="progress-bar-container">
                      <div
                        class="progress-bar"
                        :style="{ width: selectedDetail.resolutionRate || '0%' }"
                      ></div>
                    </div>
                    <div class="stat-item-value">{{ selectedDetail.resolutionRate || '0%' }}</div>
                  </div>
                  <div class="stat-item">
                    <div class="stat-item-label">Member Since</div>
                    <div class="stat-item-value">{{ selectedMember.memberSince }}</div>
                  </div>
                </div>
              </div>
            </template>
          </div>
          <div v-else class="empty-selection">
            <p>Select a member to view details</p>
          </div>
        </div>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import SearchField from '@/components/SearchField.vue'
import apiClient from '@/services/api/client'

interface Member {
  id: number
  name: string
  email: string
  phone: string | null
  role: string
  apartment: string | null
  memberSince: string
  location: string | null
  totalRequests?: number
  resolved?: number
  pending?: number
  resolutionRate?: string
}

const searchQuery = ref('')
const activeFilter = ref('All')
const selectedMember = ref<Member | null>(null)
const selectedDetail = ref<Member | null>(null)
const members = ref<Member[]>([])
const isLoading = ref(false)
const detailLoading = ref(false)
const error = ref<string | null>(null)

const filters = ['All', 'Owner', 'Tenant', 'Staff']

const emDash = '—'

const displayOptional = (v: string | null | undefined): string => {
  if (v === null || v === undefined) return emDash
  const s = String(v).trim()
  return s === '' ? emDash : s
}

const getInitials = (name: string) => {
  return name
    .split(' ')
    .map((n) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const getRoleTagClass = (role: string) => {
  if (role === 'Owner') return 'tag-owner'
  if (role === 'Tenant') return 'tag-tenant'
  if (role === 'Staff Member') return 'tag-staff'
  return ''
}

const buildParams = () => {
  const params: string[] = []
  if (activeFilter.value !== 'All') params.push(`role=${activeFilter.value}`)
  if (searchQuery.value) params.push(`search=${encodeURIComponent(searchQuery.value)}`)
  return params.length ? `?${params.join('&')}` : ''
}

const fetchMembers = async () => {
  isLoading.value = true
  error.value = null
  try {
    const { data } = await apiClient.get<Member[]>(`management/members${buildParams()}`)
    members.value = data
  } catch {
    error.value = 'Failed to load members'
  } finally {
    isLoading.value = false
  }
}

const selectMember = async (member: Member) => {
  selectedMember.value = member
  selectedDetail.value = null
  detailLoading.value = true
  try {
    const { data } = await apiClient.get<Member>(`management/members/${member.id}`)
    selectedDetail.value = data
  } catch {
  } finally {
    detailLoading.value = false
  }
}

const setFilter = (filter: string) => {
  activeFilter.value = filter
  selectedMember.value = null
  selectedDetail.value = null
  fetchMembers()
}

const applyFilters = () => {
  fetchMembers()
}

let searchTimer: ReturnType<typeof setTimeout>
watch(searchQuery, () => {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(fetchMembers, 350)
})

onMounted(() => {
  fetchMembers()
})
</script>

<style scoped>
.members-directory-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem;
}

.page-header {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
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

.search-section {
  margin-bottom: 2rem;
}

.search-container {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.filter-icon-button {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  border: 1.67px solid #99a1af;
  background-color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #757575;
  flex-shrink: 0;
}

.filter-icon-button:hover {
  background-color: #f5f5f5;
}

.filter-buttons {
  display: flex;
  gap: 0.75rem;
}

.filter-button {
  padding: 0.5rem 1.5rem;
  border: 0.125rem solid #204ef6;
  background-color: #ffffff;
  color: #204ef6;
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.filter-button:hover {
  background-color: #e3f2fd;
}

.filter-button.active {
  background-color: #204ef6;
  color: #ffffff;
}

.content-layout {
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 2rem;
  align-items: start;
}

.left-panel {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.panel-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 1.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.loading-state,
.error-state {
  padding: 2rem;
  text-align: center;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.error-state {
  color: #f44336;
}

.members-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.member-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.2s;
}

.member-item:hover {
  background-color: #f5f5f5;
}

.member-item.active {
  background-color: #e3f2fd;
}

.member-avatar-small {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background-color: #ffd572;
  color: #92400e;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.875rem;
  flex-shrink: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-info-small {
  flex: 1;
  min-width: 0;
}

.member-name-small {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-email-small {
  font-size: 0.8125rem;
  color: #757575;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-tags-small {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.25rem;
  flex-wrap: wrap;
}

.role-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.tag-owner {
  background-color: #c8daff;
  color: #1e40af;
}

.tag-tenant {
  background-color: #ffd572;
  color: #92400e;
}

.tag-staff {
  background-color: #cabeff;
  color: #5b21b6;
}

.apartment-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  background-color: #f3f4f6;
  color: #374151;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.empty-state {
  text-align: center;
  padding: 2rem;
  color: #9e9e9e;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.right-panel {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.member-details {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-card {
  background-color: #ffffff;
  border-radius: 0.5rem;
  padding: 1.5rem;
  box-shadow: 0 0.0625rem 0.1875rem rgba(0, 0, 0, 0.1);
}

.member-header {
  display: flex;
  align-items: flex-start;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.member-avatar-large {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  background-color: #ffd572;
  color: #92400e;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 1.5rem;
  flex-shrink: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-header-info {
  flex: 1;
}

.member-name-large {
  font-size: 1.5rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 0.75rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.member-role-tags {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9375rem;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.contact-item svg {
  color: #757575;
  flex-shrink: 0;
}

.card-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #212121;
  margin: 0 0 1rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.activity-stats {
  display: flex;
  gap: 1rem;
}

.stat-card {
  flex: 1;
  padding: 1rem;
  border-radius: 0.5rem;
  text-align: center;
}

.stat-card.blue {
  background-color: #204ef6;
  color: #ffffff;
}

.stat-card.green {
  background-color: #dcfce7;
  color: #166534;
}

.stat-card.orange {
  background-color: #ffd572;
  color: #92400e;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.statistics-content {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.stat-item-label {
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.progress-bar-container {
  width: 100%;
  height: 0.5rem;
  background-color: #e0e0e0;
  border-radius: 0.25rem;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background-color: #4caf50;
  border-radius: 0.25rem;
}

.stat-item-value {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.empty-selection {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  min-height: 15rem;
  color: #9e9e9e;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 48rem) {
  .content-layout {
    grid-template-columns: 1fr;
  }
}
</style>
