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
              <div class="staff-header-row">
                <div class="staff-profile">
                  <div class="staff-avatar">
                    <span class="avatar-emoji">{{ staff.avatar }}</span>
                  </div>
                  <div class="staff-info">
                    <div class="staff-name">{{ staff.name }}</div>
                    <div class="staff-role">{{ staff.role }}</div>
                  </div>
                </div>
                <div class="metrics-header">
                  <span class="total-value">{{ staff.total }} Total</span>
                </div>
              </div>
              <div class="staff-metrics">
                <div class="metrics-grid">
                  <div class="metric-item">
                    <span class="metric-label">Resolved</span>
                    <span class="metric-value resolved">{{ staff.resolved }}</span>
                  </div>
                  <div class="metric-item">
                    <span class="metric-label">In Progress</span>
                    <span class="metric-value in-progress">{{ staff.inProgress }}</span>
                  </div>
                  <div class="metric-item">
                    <span class="metric-label">Avg Time</span>
                    <span class="metric-value avg-time">{{ staff.avgTime }}</span>
                  </div>
                </div>
                <div class="workload-bar-container">
                  <span class="workload-label">Workload</span>
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
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import type { StaffMember } from '@/types/task'

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

const fetchTaskStatistics = async (_period: string) => {
  staffMembers.value = []
  statistics.value = { totalRequests: 0, resolved: 0, unresolved: 0, avgResponseTime: '-' }
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
}

.page-subtitle {
  font-size: 1rem;
  color: #757575;
  margin: 0;
}

.time-period-selector {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.period-button {
  padding: 0.75rem 1.5rem;
  border: 0.125rem solid var(--color-brand);
  background-color: #ffffff;
  color: var(--color-brand);
  border-radius: 1.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.period-button:hover {
  background-color: #e3f2fd;
}

.period-button.active {
  background-color: var(--color-brand);
  color: #ffffff;
}

.statistics-cards {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
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
}

.stat-label {
  font-size: 0.875rem;
  color: #757575;
}

.stat-trend {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
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
}

.staff-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.staff-card {
  padding: 1.5rem;
  background-color: #f5f5f5;
  border-radius: 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  position: relative;
}

.staff-header-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.staff-profile {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.staff-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background-color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.avatar-emoji {
  line-height: 1;
}

.staff-info {
  display: flex;
  flex-direction: column;
}

.staff-name {
  font-size: 1rem;
  font-weight: 700;
  color: #212121;
  margin-bottom: 0.25rem;
}

.staff-role {
  font-size: 0.875rem;
  color: #757575;
}

.staff-metrics {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.metrics-header {
  display: flex;
  justify-content: flex-end;
}

.total-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #757575;
}

.metrics-grid {
  display: flex;
  gap: 1.5rem;
  flex-wrap: wrap;
  width: 100%;
  justify-content: space-between;
}

.metric-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
  flex: 1;
  min-width: 0;
  align-items: center;
  text-align: center;
}

.metric-label {
  font-size: 0.75rem;
  color: #757575;
}

.metric-value {
  font-size: 0.875rem;
  font-weight: 500;
}

.metric-value.resolved {
  color: #4caf50;
}

.metric-value.in-progress {
  color: #ff9800;
}

.metric-value.avg-time {
  color: var(--color-brand);
}

.workload-bar-container {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.workload-label {
  font-size: 0.875rem;
  color: #212121;
  font-weight: 500;
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
}

@media (max-width: 48rem) {
  .statistics-cards {
    grid-template-columns: 1fr;
  }

  .metrics-grid {
    flex-direction: column;
    gap: 0.75rem;
  }

  .time-period-selector {
    flex-wrap: wrap;
  }
}
</style>
