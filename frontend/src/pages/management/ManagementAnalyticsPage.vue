<template>
  <v-container fluid class="pa-0">
    <div class="analytics-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">Analytics</h1>
            <p class="page-subtitle">Overview of platform performance and activity metrics</p>
          </div>
          <div class="period-selector">
            <button
              v-for="p in periods"
              :key="p.value"
              :class="['period-button', { active: selectedPeriod === p.value }]"
              @click="selectPeriod(p.value)"
            >
              {{ p.label }}
            </button>
          </div>
        </div>

        <div v-if="error" class="error-state">{{ error }}</div>
        <div v-else-if="isLoading" class="loading-state">Loading analytics…</div>

        <template v-else>
          <div class="data-cards">
            <div class="data-card">
              <div class="card-title">Active members</div>
              <div class="card-value">
                <span class="highlight">{{ analytics.activeMembers }}</span
                ><span class="dimmed">/{{ analytics.totalMembers }}</span>
              </div>
            </div>

            <div class="data-card">
              <div class="card-title">Avg. Response Time</div>
              <div class="card-value highlight">{{ analytics.avgResponseTime }}</div>
            </div>

            <div class="data-card">
              <div class="card-title">Messages Answered</div>
              <div class="card-value highlight">{{ analytics.messagesAnswered }}</div>
            </div>

            <div class="data-card">
              <div class="card-title">Starting Efficiency</div>
              <div class="card-value highlight">{{ analytics.startingEfficiency }}</div>
            </div>

            <div class="data-card">
              <div class="card-title">Current Efficiency</div>
              <div class="card-value highlight">{{ analytics.currentEfficiency }}</div>
            </div>

            <div class="data-card">
              <div class="card-title">Efficiency Gain</div>
              <div class="card-value highlight">{{ analytics.efficiencyGain }}</div>
            </div>
          </div>

          <div class="task-status-section">
            <div class="task-header">
              <h2>Task status</h2>
            </div>

            <div class="task-content">
              <div class="task-legend">
                <div class="legend-item">
                  <div class="legend-circle overdue"></div>
                  <span>Overdue tasks</span>
                  <span class="legend-count">{{ analytics.taskStatus.overdue }}</span>
                </div>
                <div class="legend-item">
                  <div class="legend-circle in-progress"></div>
                  <span>In progress</span>
                  <span class="legend-count">{{ analytics.taskStatus.inProgress }}</span>
                </div>
                <div class="legend-item">
                  <div class="legend-circle completed"></div>
                  <span>Completed</span>
                  <span class="legend-count">{{ analytics.taskStatus.completed }}</span>
                </div>
              </div>

              <div class="task-chart-wrapper">
                <div class="donut-chart">
                  <svg width="180" height="180" viewBox="0 0 180 180">
                    <circle cx="90" cy="90" r="80" fill="none" stroke="#e0e0e0" stroke-width="30" />
                    <circle
                      v-if="completedDash > 0"
                      cx="90"
                      cy="90"
                      r="80"
                      fill="none"
                      stroke="#4caf50"
                      stroke-width="30"
                      :stroke-dasharray="`${completedDash} ${circumference}`"
                      stroke-dashoffset="0"
                      transform="rotate(-90 90 90)"
                    />
                    <circle
                      v-if="inProgressDash > 0"
                      cx="90"
                      cy="90"
                      r="80"
                      fill="none"
                      stroke="#204EF6"
                      stroke-width="30"
                      :stroke-dasharray="`${inProgressDash} ${circumference}`"
                      :stroke-dashoffset="`-${completedDash}`"
                      transform="rotate(-90 90 90)"
                    />
                    <circle
                      v-if="overdueDash > 0"
                      cx="90"
                      cy="90"
                      r="80"
                      fill="none"
                      stroke="#ff5252"
                      stroke-width="30"
                      :stroke-dasharray="`${overdueDash} ${circumference}`"
                      :stroke-dashoffset="`-${completedDash + inProgressDash}`"
                      transform="rotate(-90 90 90)"
                    />
                  </svg>
                  <div class="chart-center">
                    <div class="chart-text">{{ analytics.taskStatus.total }}</div>
                    <div class="chart-label">tasks</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import apiClient from '@/services/api/client'

interface TaskStatus {
  overdue: number
  inProgress: number
  completed: number
  total: number
}

interface Analytics {
  activeMembers: number
  totalMembers: number
  avgResponseTime: string
  messagesAnswered: number
  startingEfficiency: string
  currentEfficiency: string
  efficiencyGain: string
  taskStatus: TaskStatus
}

const periods = [
  { label: 'All time', value: '' },
  { label: 'This month', value: 'month' },
  { label: 'This week', value: 'week' },
  { label: 'Today', value: 'today' }
]

const selectedPeriod = ref('')
const isLoading = ref(false)
const error = ref<string | null>(null)

const analytics = ref<Analytics>({
  activeMembers: 0,
  totalMembers: 0,
  avgResponseTime: 'N/A',
  messagesAnswered: 0,
  startingEfficiency: '0%',
  currentEfficiency: '0%',
  efficiencyGain: '0%',
  taskStatus: { overdue: 0, inProgress: 0, completed: 0, total: 0 }
})

const circumference = 2 * Math.PI * 80

const completedDash = computed(() => {
  const total = analytics.value.taskStatus.total
  if (!total) return 0
  return (analytics.value.taskStatus.completed / total) * circumference
})

const inProgressDash = computed(() => {
  const total = analytics.value.taskStatus.total
  if (!total) return 0
  return (analytics.value.taskStatus.inProgress / total) * circumference
})

const overdueDash = computed(() => {
  const total = analytics.value.taskStatus.total
  if (!total) return 0
  return (analytics.value.taskStatus.overdue / total) * circumference
})

const selectPeriod = (period: string) => {
  selectedPeriod.value = period
  fetchAnalytics()
}

const fetchAnalytics = async () => {
  isLoading.value = true
  error.value = null
  try {
    const params = selectedPeriod.value ? `?period=${selectedPeriod.value}` : ''
    const { data } = await apiClient.get<Analytics>(`management/analytics${params}`)
    analytics.value = data
  } catch {
    error.value = 'Failed to load analytics data'
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchAnalytics()
})
</script>

<style scoped>
.analytics-page {
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
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
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

.period-selector {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.period-button {
  padding: 0.5rem 1.25rem;
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

.loading-state,
.error-state {
  padding: 3rem;
  text-align: center;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.error-state {
  color: #f44336;
}

.data-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.data-card {
  background: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.card-title {
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-value {
  font-size: 1.5rem;
  font-weight: 700;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-value .highlight {
  color: #204ef6;
}

.card-value .dimmed {
  color: #9e9e9e;
  font-weight: 400;
  font-size: 1.25rem;
}

.task-status-section {
  background: #ffffff;
  border-radius: 0.75rem;
  padding: 2rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.task-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.task-header h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #212121;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.task-content {
  display: flex;
  align-items: center;
  gap: 3rem;
}

.task-legend {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.9375rem;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.legend-circle {
  width: 0.875rem;
  height: 0.875rem;
  border-radius: 50%;
  flex-shrink: 0;
}

.legend-circle.overdue {
  background-color: #ff5252;
}

.legend-circle.in-progress {
  background-color: #204ef6;
}

.legend-circle.completed {
  background-color: #4caf50;
}

.legend-count {
  font-weight: 700;
  margin-left: auto;
}

.task-chart-wrapper {
  display: flex;
  align-items: center;
  justify-content: center;
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
  font-size: 2rem;
  font-weight: 700;
  color: #204ef6;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chart-label {
  font-size: 0.75rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 48rem) {
  .data-cards {
    grid-template-columns: 1fr 1fr;
  }

  .task-content {
    flex-direction: column;
    gap: 2rem;
  }

  .header-section {
    flex-direction: column;
  }
}

@media (max-width: 30rem) {
  .data-cards {
    grid-template-columns: 1fr;
  }
}
</style>
