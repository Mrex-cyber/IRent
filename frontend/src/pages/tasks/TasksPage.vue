<template>
  <v-container fluid class="pa-0">
    <div class="tasks-wrapper">
      <TopNavbar />
      <v-container>
        <v-row>
          <v-col cols="12">
            <div class="page-header">
              <h1 class="page-title">Tasks</h1>
              <div class="header-actions">
                <button class="filter-button" @click="showFilters = true">
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
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                  </svg>
                </button>
                <button class="calendar-button" @click="showCalendar = true">
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
                <v-btn
                  v-if="isOSBBRepresentative"
                  color="primary"
                  @click="createTask"
                  class="create-button"
                >
                  Create Task
                </v-btn>
              </div>
            </div>
            <v-card>
              <v-card-text>
                <v-list>
                  <v-list-item v-for="task in filteredTasks" :key="task.id" @click="viewTask(task)">
                    <template v-slot:prepend>
                      <v-icon :color="getPriorityColor(task.priority)" size="24">
                        {{ getPriorityIcon(task.priority) }}
                      </v-icon>
                    </template>

                    <v-list-item-title>{{ task.title }}</v-list-item-title>
                    <v-list-item-subtitle>
                      {{ task.description }}
                    </v-list-item-subtitle>

                    <template v-slot:append>
                      <div class="text-right">
                        <v-chip :color="getStatusColor(task.status)" size="small" class="mb-1">
                          {{ task.status }}
                        </v-chip>
                        <div class="text-caption">Due: {{ formatDate(task.dueDate) }}</div>
                      </div>
                    </template>
                  </v-list-item>
                </v-list>

                <div v-if="filteredTasks.length === 0" class="text-center pa-8">
                  <v-icon size="64" color="grey">mdi-clipboard-list-outline</v-icon>
                  <h3 class="mt-4">No tasks found</h3>
                  <p>Try adjusting your filters or create a new task.</p>
                </div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-navigation-drawer v-model="showFilters" temporary location="top" class="filters-drawer">
        <div class="drawer-header">
          <h2>Filters</h2>
          <button class="close-button" @click="showFilters = false">
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
        </div>
        <div class="drawer-content">
          <v-select
            v-model="filters.status"
            label="Status"
            :items="statusOptions"
            clearable
            class="mb-4"
          ></v-select>

          <v-select
            v-model="filters.priority"
            label="Priority"
            :items="priorityOptions"
            clearable
            class="mb-4"
          ></v-select>

          <v-select
            v-model="filters.apartment"
            label="Apartment"
            :items="apartmentOptions"
            clearable
            class="mb-4"
          ></v-select>

          <v-btn color="primary" block @click="applyFilters"> Apply Filters </v-btn>
        </div>
      </v-navigation-drawer>

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
          <v-date-picker v-model="selectedDate" show-adjacent-months></v-date-picker>
        </v-card>
      </v-dialog>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'
import TopNavbar from '@/components/TopNavbar.vue'

const authStore = useAuthStore()

const tasks = ref([])
const apartments = ref([])
const showFilters = ref(false)
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])

const filters = ref({
  status: '',
  priority: '',
  apartment: ''
})

const statusOptions = ['Pending', 'InProgress', 'Completed', 'Cancelled']
const priorityOptions = ['Low', 'Medium', 'High', 'Urgent']

const apartmentOptions = computed(() =>
  apartments.value.map((apt) => ({ title: apt.title, value: apt.id }))
)

const isOSBBRepresentative = computed(() => {
  return authStore.user?.role === 'OSBBRepresentative' || authStore.user?.role === 'Realtor'
})

const filteredTasks = computed(() => {
  return tasks.value.filter((task) => {
    if (filters.value.status && task.status !== filters.value.status) return false
    if (filters.value.priority && task.priority !== filters.value.priority) return false
    if (filters.value.apartment && task.apartmentId !== filters.value.apartment) return false
    return true
  })
})

const getPriorityColor = (priority: string) => {
  const colors: Record<string, string> = {
    Low: 'green',
    Medium: 'blue',
    High: 'orange',
    Urgent: 'red'
  }
  return colors[priority] || 'grey'
}

const getPriorityIcon = (priority: string) => {
  const icons: Record<string, string> = {
    Low: 'mdi-arrow-down',
    Medium: 'mdi-minus',
    High: 'mdi-arrow-up',
    Urgent: 'mdi-exclamation'
  }
  return icons[priority] || 'mdi-minus'
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    Pending: 'orange',
    InProgress: 'blue',
    Completed: 'green',
    Cancelled: 'red'
  }
  return colors[status] || 'grey'
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString()
}

const applyFilters = () => {
  console.log('Apply filters')
}

const createTask = () => {
  console.log('Create task')
}

const viewTask = (task: any) => {
  console.log('View task', task)
}

const fetchTasks = async () => {
  try {
    const response = await fetch('/api/tasks')
    if (response.ok) {
      tasks.value = await response.json()
    }
  } catch (error) {
    console.error('Fetch tasks error:', error)
  }
}

const fetchApartments = async () => {
  try {
    const response = await fetch('/api/apartments')
    if (response.ok) {
      apartments.value = await response.json()
    }
  } catch (error) {
    console.error('Fetch apartments error:', error)
  }
}

onMounted(() => {
  fetchTasks()
  fetchApartments()
})
</script>

<style scoped>
.tasks-wrapper {
  max-width: 30rem;
  margin: 0 auto;
  padding: 0;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding: 0 1rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.0625rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.filter-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  border: 0.09375rem solid #1976d2;
  background: #e3f2fd;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #1976d2;
  padding: 0;
  transition: all 0.2s;
}

.filter-button:hover {
  background: #bbdefb;
}

.calendar-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  border: 0.09375rem solid #1976d2;
  background: #e3f2fd;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #1976d2;
  padding: 0;
  transition: all 0.2s;
}

.calendar-button:hover {
  background: #bbdefb;
}

:deep(.filters-drawer) {
  width: calc(100% - 2rem) !important;
  max-height: 50vh;
  left: 1rem !important;
  right: 1rem !important;
  top: 0 !important;
  border-radius: 0 0 1rem 1rem;
}

.drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.drawer-header h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.close-button {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;
  padding: 0;
  transition: all 0.2s;
}

.close-button:hover {
  background-color: #f5f5f5;
}

.drawer-content {
  padding: 1.5rem;
}

.create-button {
  text-transform: none;
}
</style>
