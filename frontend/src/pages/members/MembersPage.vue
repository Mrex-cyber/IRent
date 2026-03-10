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
          <button class="filter-icon-button">
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
            @click="activeFilter = filter"
          >
            {{ filter }}
          </button>
        </div>
      </div>

      <div class="content-layout">
        <div ref="leftPanel" class="left-panel">
          <h2 class="panel-title">All Members ({{ filteredMembers.length }})</h2>
          <div class="members-list">
            <div
              v-for="member in filteredMembers"
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
          </div>
        </div>

        <div ref="rightPanel" class="right-panel">
          <div v-if="selectedMember" class="member-details">
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
                    <span v-if="selectedMember.title" class="title-tag">{{
                      selectedMember.title
                    }}</span>
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
                  <span>{{ selectedMember.phone }}</span>
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
                  <span>{{ selectedMember.location || 'Management Office' }}</span>
                </div>
              </div>
            </div>

            <div class="info-card">
              <h4 class="card-title">Activity Overview</h4>
              <div class="activity-stats">
                <div class="stat-card blue">
                  <div class="stat-value">{{ selectedMember.totalRequests || 156 }}</div>
                  <div class="stat-label">Total Requests</div>
                </div>
                <div class="stat-card green">
                  <div class="stat-value">{{ selectedMember.resolved || 142 }}</div>
                  <div class="stat-label">Resolved</div>
                </div>
                <div class="stat-card orange">
                  <div class="stat-value">{{ selectedMember.pending || 14 }}</div>
                  <div class="stat-label">Pending</div>
                </div>
              </div>
            </div>

            <div class="info-card">
              <h4 class="card-title">Statistics</h4>
              <div class="statistics-content">
                <div class="stat-item">
                  <div class="stat-item-label">Resolution Rate</div>
                  <div class="progress-bar-container">
                    <div
                      class="progress-bar"
                      :style="{ width: selectedMember.resolutionRate || '91%' }"
                    ></div>
                  </div>
                  <div class="stat-item-value">{{ selectedMember.resolutionRate || '91%' }}</div>
                </div>
                <div class="stat-item">
                  <div class="stat-item-label">Member Since</div>
                  <div class="stat-item-value">
                    {{ selectedMember.memberSince || '2019-01-10' }}
                  </div>
                </div>
              </div>
            </div>

            <div class="action-buttons">
              <button class="action-button outlined">View Full Profile</button>
              <button class="action-button primary">Send Message</button>
            </div>
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
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import SearchField from '@/components/SearchField.vue'

interface Member {
  id: number
  name: string
  email: string
  phone: string
  role: 'Owner' | 'Tenant' | 'Staff Member'
  apartment?: string
  title?: string
  location?: string
  totalRequests?: number
  resolved?: number
  pending?: number
  resolutionRate?: string
  memberSince?: string
}

const searchQuery = ref('')
const activeFilter = ref('All')
const selectedMember = ref<Member | null>(null)
const leftPanel = ref<HTMLElement | null>(null)
const rightPanel = ref<HTMLElement | null>(null)

const filters = ['All', 'Owner', 'Tenant', 'Staff']

const members = ref<Member[]>([
  {
    id: 1,
    name: 'Sarah Johnson',
    email: 'sarah.j@example.com',
    phone: '+38 099 999 99 99',
    role: 'Owner',
    apartment: 'A-302'
  },
  {
    id: 2,
    name: 'Michael Chen',
    email: 'michael.chen@example.com',
    phone: '+38 099 999 99 99',
    role: 'Tenant',
    apartment: 'B-105'
  },
  {
    id: 3,
    name: 'Tom Smith',
    email: 'tomsmith@gmail.com',
    phone: '+38 099 999 99 99',
    role: 'Staff Member',
    apartment: 'Office',
    title: 'Chairman of the condominium',
    location: 'Management Office',
    totalRequests: 156,
    resolved: 142,
    pending: 14,
    resolutionRate: '91%',
    memberSince: '2019-01-10'
  },
  {
    id: 4,
    name: 'Emma Davis',
    email: 'emma.davis@example.com',
    phone: '+38 099 999 99 99',
    role: 'Owner',
    apartment: 'A-101'
  },
  {
    id: 5,
    name: 'John Wilson',
    email: 'john.wilson@example.com',
    phone: '+38 099 999 99 99',
    role: 'Tenant',
    apartment: 'B-205'
  },
  {
    id: 6,
    name: 'Lisa Anderson',
    email: 'lisa.anderson@example.com',
    phone: '+38 099 999 99 99',
    role: 'Owner',
    apartment: 'A-203'
  },
  {
    id: 7,
    name: 'David Brown',
    email: 'david.brown@example.com',
    phone: '+38 099 999 99 99',
    role: 'Tenant',
    apartment: 'B-304'
  },
  {
    id: 8,
    name: 'Maria Garcia',
    email: 'maria.garcia@example.com',
    phone: '+38 099 999 99 99',
    role: 'Staff Member',
    apartment: 'Office'
  }
])

const filteredMembers = computed(() => {
  let filtered = members.value

  if (activeFilter.value !== 'All') {
    const filterRole = activeFilter.value === 'Staff' ? 'Staff Member' : activeFilter.value
    filtered = filtered.filter((m) => m.role === filterRole)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(
      (m) => m.name.toLowerCase().includes(query) || m.email.toLowerCase().includes(query)
    )
  }

  return filtered
})

const selectMember = (member: Member) => {
  selectedMember.value = member
  syncPanelHeights()
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

const syncPanelHeights = () => {
  nextTick(() => {
    if (rightPanel.value && leftPanel.value) {
      const rightHeight = rightPanel.value.offsetHeight
      leftPanel.value.style.height = `${rightHeight}px`
    }
  })
}

onMounted(() => {
  if (members.value.length > 0) {
    selectedMember.value = members.value[2]
  }
  syncPanelHeights()
  window.addEventListener('resize', syncPanelHeights)
})

onUnmounted(() => {
  window.removeEventListener('resize', syncPanelHeights)
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
}

.page-subtitle {
  font-size: 1rem;
  color: #757575;
  margin: 0;
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
  border: 0.125rem solid var(--color-brand);
  background-color: #ffffff;
  color: var(--color-brand);
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.filter-button:hover {
  background-color: #e3f2fd;
}

.filter-button.active {
  background-color: var(--color-brand);
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
  display: flex;
  flex-direction: column;
  height: fit-content;
  max-height: 100%;
}

.panel-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 1.5rem 0;
  flex-shrink: 0;
}

.members-list {
  min-height: 0;
  overflow-y: auto;
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
  background-color: transparent;
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
}

.member-info-small {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.member-name-small {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #212121;
}

.member-email-small {
  font-size: 0.875rem;
  color: #757575;
}

.member-tags-small {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.25rem;
}

.role-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
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
}

.right-panel {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
  height: fit-content;
  display: flex;
  flex-direction: column;
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
}

.member-header-info {
  flex: 1;
}

.member-name-large {
  font-size: 1.5rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 0.75rem 0;
}

.member-role-tags {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.title-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  background-color: #c8daff;
  color: #1e40af;
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
  background-color: var(--color-brand);
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
}

.stat-label {
  font-size: 0.875rem;
  font-weight: 500;
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
  background-color: #dcfce7;
  border-radius: 0.25rem;
}

.stat-item-value {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #212121;
}

.action-buttons {
  display: flex;
  gap: 1rem;
  margin-top: 0.5rem;
}

.action-button {
  flex: 1;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-size: 0.9375rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.action-button.outlined {
  border: 0.125rem solid var(--color-brand);
  background-color: #ffffff;
  color: var(--color-brand);
}

.action-button.outlined:hover {
  background-color: #e3f2fd;
}

.action-button.primary {
  border: none;
  background-color: var(--color-brand);
  color: #ffffff;
}

.action-button.primary:hover {
  background-color: #1a3dd4;
}

.empty-selection {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #9e9e9e;
}

@media (max-width: 48rem) {
  .content-layout {
    grid-template-columns: 1fr;
  }
}
</style>
