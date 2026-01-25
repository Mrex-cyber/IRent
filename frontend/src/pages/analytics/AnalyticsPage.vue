<template>
  <v-container fluid class="analytics-container-fluid pa-0">
    <div class="analytics-page">
      <div class="analytics-container">
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

        <div class="filters">
          <div class="filter-dropdown">
            <span>Timeframe: All-time</span>
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
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
          <div class="filter-dropdown">
            <span>People: All</span>
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
              <polyline points="6 9 12 15 18 9" />
            </svg>
          </div>
        </div>

        <div class="data-cards">
          <div class="data-card">
            <div class="card-title">Active members</div>
            <div class="card-value">
              <span class="highlight">27</span><span class="dimmed">/80</span>
            </div>
          </div>

          <div class="data-card">
            <div class="card-title">Av. Response time</div>
            <div class="card-value highlight">49m 34s</div>
          </div>

          <div class="data-card">
            <div class="card-title">Messages Answered</div>
            <div class="card-value highlight">3987</div>
          </div>

          <div class="data-card">
            <div class="card-title">Starting Efficiency</div>
            <div class="card-value highlight">64%</div>
            <div class="card-chart">
              <svg width="100" height="30" viewBox="0 0 100 30">
                <polyline
                  points="0,20 10,15 20,18 30,12 40,16 50,14 60,18 70,15 80,17 90,13 100,16"
                  fill="none"
                  stroke="#1976d2"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
          </div>

          <div class="data-card">
            <div class="card-title">Current Efficiency</div>
            <div class="card-value highlight">86%</div>
            <div class="card-chart">
              <svg width="100" height="30" viewBox="0 0 100 30">
                <polyline
                  points="0,20 10,18 20,16 30,14 40,12 50,10 60,12 70,14 80,16 90,18 100,20"
                  fill="none"
                  stroke="#1976d2"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
          </div>

          <div class="data-card">
            <div class="card-title">Efficiency Gain</div>
            <div class="card-value highlight">+34%</div>
            <div class="card-chart">
              <svg width="100" height="30" viewBox="0 0 100 30">
                <polyline
                  points="0,25 10,20 20,15 30,10 40,8 50,6 60,8 70,10 80,12 90,14 100,16"
                  fill="none"
                  stroke="#1976d2"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </div>
          </div>
        </div>

        <div class="task-status-section">
          <div class="task-header">
            <h2>Task status</h2>
            <div class="task-dropdown">
              <span>This week</span>
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
                <polyline points="6 9 12 15 18 9" />
              </svg>
            </div>
          </div>

          <div class="task-content">
            <div class="task-legend">
              <div class="legend-item">
                <div class="legend-circle overdue"></div>
                <span>Overdue tasks</span>
                <span class="legend-count">8</span>
              </div>
              <div class="legend-item">
                <div class="legend-circle in-progress"></div>
                <span>In progress</span>
                <span class="legend-count">12</span>
              </div>
              <div class="legend-item">
                <div class="legend-circle completed"></div>
                <span>Completed</span>
                <span class="legend-count">9</span>
              </div>
            </div>

            <div class="task-chart-wrapper">
              <div class="donut-chart">
                <svg width="180" height="180" viewBox="0 0 180 180">
                  <circle cx="90" cy="90" r="80" fill="none" stroke="#e0e0e0" stroke-width="30" />
                  <circle
                    cx="90"
                    cy="90"
                    r="80"
                    fill="none"
                    stroke="#4caf50"
                    stroke-width="30"
                    stroke-dasharray="188.4"
                    stroke-dashoffset="94.2"
                    transform="rotate(-90 90 90)"
                  />
                  <circle
                    cx="90"
                    cy="90"
                    r="80"
                    fill="none"
                    stroke="#204EF6"
                    stroke-width="30"
                    stroke-dasharray="188.4"
                    stroke-dashoffset="131.88"
                    transform="rotate(-90 90 90)"
                  />
                  <circle
                    cx="90"
                    cy="90"
                    r="80"
                    fill="none"
                    stroke="#ff5252"
                    stroke-width="30"
                    stroke-dasharray="188.4"
                    stroke-dashoffset="169.56"
                    transform="rotate(-90 90 90)"
                  />
                </svg>
                <div class="chart-center">
                  <div class="chart-text">35</div>
                  <div class="chart-label">tasks</div>
                </div>
              </div>

              <div class="chart-navigation">
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
                <div class="nav-divider"></div>
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
              </div>
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

const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])
</script>

<style scoped>
.analytics-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.analytics-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.analytics-page {
  min-height: 100vh;
  background-color: #ffffff;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.analytics-container {
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

.grid-icon-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.5rem;
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

.page-header {
  margin-bottom: 1.5rem;
}

.page-header h1 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  text-transform: uppercase;
  letter-spacing: 0.0625rem;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.filters {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.filter-dropdown {
  background: #ffffff;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 0.75rem;
  padding: 0.875rem 1rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: pointer;
  font-size: 1rem;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.filter-dropdown:hover {
  border-color: #1976d2;
}

.data-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.data-card {
  background: #ffffff;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 0.75rem;
  padding: 1rem;
  display: flex;
  flex-direction: column;
}

.card-title {
  font-size: 0.8125rem;
  color: #666;
  margin-bottom: 0.5rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-value {
  font-size: 1.375rem;
  font-weight: 700;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-value .highlight {
  color: #1976d2;
}

.card-value .dimmed {
  color: #999;
  font-weight: 400;
}

.card-chart {
  margin-top: 0.5rem;
}

.task-status-section {
  background: #ffffff;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 1rem;
  padding: 1.25rem;
}

.task-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.25rem;
}

.task-header h2 {
  font-size: 1.125rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.task-dropdown {
  background: #f5f5f5;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 0.375rem 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.task-content {
  display: flex;
  align-items: flex-start;
  gap: 1.5rem;
}

.task-legend {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  font-size: 0.875rem;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.legend-circle {
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 50%;
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
  font-weight: 600;
  margin-left: auto;
}

.task-chart-wrapper {
  position: relative;
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
  font-size: 1.75rem;
  font-weight: 700;
  color: #1976d2;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chart-label {
  font-size: 0.75rem;
  color: #666;
  margin-top: -0.25rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chart-navigation {
  position: absolute;
  bottom: 0.625rem;
  left: 0.625rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background-color: #424242;
  border-radius: 1.25rem;
  padding: 0.5rem 1rem;
}

.chart-navigation svg {
  cursor: pointer;
}

.nav-divider {
  width: 0.0625rem;
  height: 1rem;
  background-color: rgba(255, 255, 255, 0.3);
}

@media (max-width: 30rem) {
  .analytics-page {
    padding: 1rem;
  }

  .data-cards {
    grid-template-columns: repeat(2, 1fr);
  }

  .task-content {
    flex-direction: column;
  }

  .donut-chart {
    width: 7.5rem;
    height: 7.5rem;
  }

  .chart-navigation {
    bottom: 0.3125rem;
    left: 0.3125rem;
    padding: 0.375rem 0.75rem;
  }
}

@media (max-width: 22.5rem) {
  .data-cards {
    grid-template-columns: 1fr;
  }
}
</style>
