<template>
  <v-container fluid class="pa-0">
    <div class="task-load-overview-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">Task Load Overview</h1>
            <p class="page-subtitle">Monitor request statistics and staff workload</p>
          </div>
        </div>

        <div class="time-period-selector">
          <button
            v-for="period in timePeriods"
            :key="period"
            :class="['period-button', { active: selectedPeriod === period }]"
            @click="selectPeriod(period)"
          >
            {{ period }}
          </button>
        </div>

        <div class="statistics-cards">
          <div class="stat-card">
            <div class="stat-icon blue">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value">{{ statistics.totalRequests }}</div>
              <div class="stat-label">Total Requests</div>
              <div class="stat-trend positive">
                <svg
                  width="12"
                  height="12"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                >
                  <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                  <polyline points="17 6 23 6 23 12" />
                </svg>
                <span>12%</span>
              </div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon green">
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <polyline points="20 6 9 17 4 12" />
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value">{{ statistics.resolved }}</div>
              <div class="stat-label">Resolved</div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon orange">
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value">{{ statistics.unresolved }}</div>
              <div class="stat-label">Unresolved</div>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon purple">
              <svg
                width="32"
                height="32"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
              </svg>
            </div>
            <div class="stat-content">
              <div class="stat-value">{{ statistics.avgResponseTime }}</div>
              <div class="stat-label">Avg Response Time</div>
            </div>
          </div>
        </div>

        <div class="staff-workload-section">
          <h2 class="section-title">Staff Workload</h2>
          <div class="staff-list">
            <div v-for="staff in staffMembers" :key="staff.id" class="staff-card">
              <div class="staff-header">
                <div class="staff-avatar">
                  <span class="avatar-emoji">{{ staff.avatar }}</span>
                </div>
                <div class="staff-info">
                  <div class="staff-name">{{ staff.name }}</div>
                  <div class="staff-role">{{ staff.role }}</div>
                </div>
              </div>
              <div class="staff-stats">
                <div class="stat-item">
                  <span class="stat-value-blue">{{ staff.resolved }} Resolved</span>
                </div>
                <div class="stat-item">
                  <span class="stat-value-orange">{{ staff.inProgress }} In Progress</span>
                </div>
                <div class="stat-item">
                  <span class="stat-value-blue">{{ staff.avgTime }} Avg Time</span>
                </div>
                <div class="stat-item total">
                  <span class="stat-value-gray">{{ staff.total }} Total</span>
                </div>
              </div>
              <div class="workload-bar-container">
                <div class="workload-bar">
                  <div
                    :class="['workload-fill', getWorkloadColorClass(staff.workload)]"
                    :style="{ width: `${staff.workload}%` }"
                  ></div>
                </div>
                <span class="workload-percentage">{{ staff.workload }}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'

interface StaffMember {
  id: number
  name: string
  role: string
  avatar: string
  resolved: number
  inProgress: number
  avgTime: string
  total: number
  workload: number
}

const authStore = useAuthStore()
const selectedPeriod = ref('Week')
const timePeriods = ['Today', 'Week', 'Month', 'Year']

const statistics = ref({
  totalRequests: 487,
  resolved: 412,
  unresolved: 75,
  avgResponseTime: '3.2 hours'
})

const staffMembers = ref<StaffMember[]>([])

const getWorkloadColorClass = (workload: number) => {
  if (workload >= 80) return 'workload-high'
  if (workload >= 60) return 'workload-medium'
  return 'workload-low'
}

const selectPeriod = (period: string) => {
  selectedPeriod.value = period
  fetchTaskStatistics(period)
}

const fetchTaskStatistics = async (period: string) => {
  try {
    const response = await fetch(`/api/tasks/statistics?period=${period}`)
    if (response.ok) {
      const data = await response.json()
      statistics.value = data.statistics
      staffMembers.value = data.staffMembers
    } else {
      loadMockData()
    }
  } catch (error) {
    console.error('Fetch task statistics error:', error)
    loadMockData()
  }
}

const loadMockData = () => {
  staffMembers.value = [
    {
      id: 1,
      name: 'Tom Smith',
      role: 'Chairman',
      avatar: '😊',
      resolved: 142,
      inProgress: 14,
      avgTime: '2.3 hours',
      total: 156,
      workload: 85
    },
    {
      id: 2,
      name: 'Sarah Johnson',
      role: 'Building Manager',
      avatar: '👩',
      resolved: 120,
      inProgress: 14,
      avgTime: '3.1 hours',
      total: 134,
      workload: 72
    },
    {
      id: 3,
      name: 'Michael Chen',
      role: 'Maintenance Coordinator',
      avatar: '👓',
      resolved: 95,
      inProgress: 17,
      avgTime: '4.2 hours',
      total: 112,
      workload: 68
    }
  ]
}

onMounted(() => {
  fetchTaskStatistics(selectedPeriod.value)
})
</script>

<style scoped>
.task-load-overview-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 75rem;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
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

.time-period-selector {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.period-button {
  padding: 0.75rem 1.5rem;
  border: 0.125rem solid #204ef6;
  background-color: #ffffff;
  color: #204ef6;
  border-radius: 1.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.period-button:hover {
  background-color: #e3f2fd;
}

.period-button.active {
  background-color: #204ef6;
  color: #ffffff;
}

.statistics-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
  gap: 1.5rem;
  margin-bottom: 3rem;
}

.stat-card {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.stat-icon.blue {
  background-color: #bbdefb;
  color: #1565c0;
}

.stat-icon.green {
  background-color: #c8e6c9;
  color: #2e7d32;
}

.stat-icon.orange {
  background-color: #ffe0b2;
  color: #e65100;
}

.stat-icon.purple {
  background-color: #e1bee7;
  color: #6a1b9a;
}

.stat-content {
  flex: 1;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #212121;
  margin-bottom: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-label {
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-trend {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-trend.positive {
  color: #4caf50;
}

.stat-trend.negative {
  color: #f44336;
}

.staff-workload-section {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 2rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.section-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 1.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.staff-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.staff-card {
  padding: 1.5rem;
  background-color: #fafafa;
  border-radius: 0.75rem;
  border: 0.0625rem solid #e0e0e0;
}

.staff-header {
  display: flex;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.staff-avatar {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 50%;
  background-color: #fff59d;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  flex-shrink: 0;
}

.avatar-emoji {
  line-height: 1;
}

.staff-info {
  flex: 1;
}

.staff-name {
  font-size: 1.125rem;
  font-weight: 600;
  color: #212121;
  margin-bottom: 0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.staff-role {
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.staff-stats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
  margin-bottom: 1rem;
}

.stat-item {
  font-size: 0.875rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.stat-item.total {
  text-align: right;
}

.stat-value-blue {
  color: #204ef6;
  font-weight: 500;
}

.stat-value-orange {
  color: #ff9800;
  font-weight: 500;
}

.stat-value-gray {
  color: #757575;
  font-weight: 500;
}

.workload-bar-container {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.workload-bar {
  flex: 1;
  height: 0.5rem;
  background-color: #e0e0e0;
  border-radius: 0.25rem;
  overflow: hidden;
}

.workload-fill {
  height: 100%;
  border-radius: 0.25rem;
  transition: width 0.3s ease;
}

.workload-high {
  background-color: #f44336;
}

.workload-medium {
  background-color: #ff9800;
}

.workload-low {
  background-color: #4caf50;
}

.workload-percentage {
  font-size: 0.875rem;
  font-weight: 600;
  color: #212121;
  min-width: 3rem;
  text-align: right;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 48rem) {
  .statistics-cards {
    grid-template-columns: 1fr;
  }

  .staff-stats {
    grid-template-columns: repeat(2, 1fr);
  }

  .stat-item.total {
    grid-column: 1 / -1;
    text-align: left;
  }

  .time-period-selector {
    flex-wrap: wrap;
  }
}
</style>
