<template>
  <v-container fluid class="pa-0">
    <div class="news-announcements-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">News & Announcements</h1>
            <p class="page-subtitle">Manage posts, announcements, and communications</p>
          </div>
          <button class="new-post-button" @click="openCreateDialog">
            <svg
              width="20"
              height="20"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            <span>New Post</span>
          </button>
        </div>

        <div class="search-filter-section">
          <SearchField v-model="searchQuery" placeholder="Search announcements..." />

          <div class="filter-buttons">
            <button
              v-for="category in categories"
              :key="category"
              :class="['filter-button', { active: selectedCategory === category }]"
              @click="selectCategory(category)"
            >
              {{ category }}
            </button>
          </div>
        </div>

        <div class="announcements-list">
          <div
            v-for="announcement in filteredAnnouncements"
            :key="announcement.id"
            class="announcement-card"
          >
            <div class="card-header">
              <div class="tags">
                <span :class="['category-tag', getCategoryClass(announcement.category)]">
                  {{ announcement.category }}
                </span>
                <span :class="['status-tag', getStatusClass(announcement.status)]">
                  {{ announcement.status }}
                </span>
              </div>
              <div class="card-menu" @click.stop>
                <v-menu>
                  <template v-slot:activator="{ props }">
                    <button class="menu-button" v-bind="props">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="5" r="2" />
                        <circle cx="12" cy="12" r="2" />
                        <circle cx="12" cy="19" r="2" />
                      </svg>
                    </button>
                  </template>
                  <v-list>
                    <v-list-item @click="openEditDialog(announcement)">
                      <v-list-item-title>Edit</v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="deleteAnnouncement(announcement.id)">
                      <v-list-item-title>Delete</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </div>
            </div>

            <h3 class="card-title">{{ announcement.title }}</h3>
            <p class="card-content">{{ truncateContent(announcement.content) }}</p>

            <div class="card-footer">
              <div class="card-dates">
                <span class="date-item">
                  <svg
                    width="16"
                    height="16"
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
                  {{ announcement.createdAt }}
                </span>
                <span v-if="announcement.scheduledAt" class="date-item scheduled">
                  <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                  </svg>
                  {{ announcement.scheduledAt }}
                </span>
              </div>
              <div class="card-author">By {{ announcement.author.name }}</div>
            </div>
          </div>

          <div v-if="filteredAnnouncements.length === 0" class="empty-state">
            <p>No announcements found</p>
          </div>
        </div>
      </div>
    </div>

    <v-dialog v-model="showDialog" max-width="600">
      <v-card>
        <v-card-title>
          <span class="text-h6">{{
            editingAnnouncement ? 'Edit Announcement' : 'New Announcement'
          }}</span>
          <v-spacer></v-spacer>
          <v-btn icon variant="text" @click="closeDialog">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
            >
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-text-field
            v-model="formData.title"
            label="Title"
            variant="outlined"
            required
          ></v-text-field>
          <v-textarea
            v-model="formData.content"
            label="Content"
            variant="outlined"
            rows="6"
            required
          ></v-textarea>
          <v-select
            v-model="formData.category"
            :items="categories"
            label="Category"
            variant="outlined"
            required
          ></v-select>
          <v-select
            v-model="formData.status"
            :items="statusOptions"
            label="Status"
            variant="outlined"
            required
          ></v-select>
          <v-text-field
            v-if="formData.status === 'Scheduled'"
            v-model="formData.scheduledAt"
            type="date"
            label="Scheduled Date"
            variant="outlined"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" @click="closeDialog">Cancel</v-btn>
          <v-btn color="primary" @click="saveAnnouncement">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'

interface Announcement {
  id: number
  title: string
  content: string
  category: string
  status: string
  publishedAt: string | null
  scheduledAt: string | null
  createdAt: string
  author: {
    id: number
    name: string
  }
}

const authStore = useAuthStore()
const announcements = ref<Announcement[]>([])
const searchQuery = ref('')
const selectedCategory = ref('All')
const showDialog = ref(false)
const editingAnnouncement = ref<Announcement | null>(null)

const categories = ['All', 'General', 'Maintenance', 'Events', 'Financial', 'Safety', 'Updates']
const statusOptions = ['Draft', 'Scheduled', 'Published']

const formData = ref({
  title: '',
  content: '',
  category: 'General',
  status: 'Draft',
  scheduledAt: ''
})

const filteredAnnouncements = computed(() => {
  let filtered = announcements.value

  if (selectedCategory.value !== 'All') {
    filtered = filtered.filter((a) => a.category === selectedCategory.value)
  }

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(
      (a) => a.title.toLowerCase().includes(query) || a.content.toLowerCase().includes(query)
    )
  }

  return filtered
})

const getCategoryClass = (category: string) => {
  const classes: Record<string, string> = {
    General: 'category-general',
    Maintenance: 'category-maintenance',
    Events: 'category-events',
    Financial: 'category-financial',
    Safety: 'category-safety',
    Updates: 'category-updates'
  }
  return classes[category] || 'category-general'
}

const getStatusClass = (status: string) => {
  const classes: Record<string, string> = {
    Published: 'status-published',
    Scheduled: 'status-scheduled',
    Draft: 'status-draft'
  }
  return classes[status] || 'status-draft'
}

const truncateContent = (content: string) => {
  return content.length > 150 ? content.substring(0, 150) + '...' : content
}

const selectCategory = (category: string) => {
  selectedCategory.value = category
}

const handleSearch = () => {}

const mockAnnouncements: Announcement[] = [
  {
    id: 1,
    title: 'Annual General Meeting - November 2024',
    content:
      'Dear residents, we are pleased to announce the Annual General Meeting will be held on November 30th, 2024 at 6:00 PM in the community hall. All apartment owners are encouraged to attend. We will discuss important matters including building maintenance, financial reports, and upcoming projects. Please RSVP by November 25th.',
    category: 'Events',
    status: 'Published',
    publishedAt: '2024-11-15',
    scheduledAt: null,
    createdAt: '2024-11-15',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  },
  {
    id: 2,
    title: 'Water System Maintenance - Building A',
    content:
      'Please be informed that water supply will be temporarily interrupted in Building A on November 28th from 9:00 AM to 2:00 PM for routine maintenance work. We apologize for any inconvenience and appreciate your understanding. Please store water in advance if needed.',
    category: 'Maintenance',
    status: 'Scheduled',
    publishedAt: null,
    scheduledAt: '2024-11-27',
    createdAt: '2024-11-20',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  },
  {
    id: 3,
    title: 'New Parking Regulations',
    content:
      'Starting December 1st, new parking regulations will be in effect. All residents must register their vehicles with the management office. Visitor parking will be limited to 2 hours. Please review the complete parking policy document available at the management office.',
    category: 'General',
    status: 'Draft',
    publishedAt: null,
    scheduledAt: null,
    createdAt: '2024-11-18',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  },
  {
    id: 4,
    title: 'Holiday Decoration Guidelines',
    content:
      'As we approach the holiday season, please review the decoration guidelines for common areas and balconies. All decorations must be fire-resistant and securely attached. Please remove decorations by January 15th. Happy holidays!',
    category: 'General',
    status: 'Published',
    publishedAt: '2024-11-22',
    scheduledAt: null,
    createdAt: '2024-11-22',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  },
  {
    id: 5,
    title: 'Financial Report - Q3 2024',
    content:
      'The quarterly financial report for Q3 2024 is now available for review. All residents can access the report through the resident portal or request a printed copy from the management office. The report includes budget allocations, maintenance expenses, and reserve fund status.',
    category: 'Financial',
    status: 'Published',
    publishedAt: '2024-11-10',
    scheduledAt: null,
    createdAt: '2024-11-10',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  },
  {
    id: 6,
    title: 'Fire Safety Drill - December 5th',
    content:
      'A mandatory fire safety drill will be conducted on December 5th at 10:00 AM. All residents are required to participate. Please familiarize yourself with the evacuation routes posted on each floor. Your cooperation ensures the safety of all residents.',
    category: 'Safety',
    status: 'Scheduled',
    publishedAt: null,
    scheduledAt: '2024-12-05',
    createdAt: '2024-11-25',
    author: {
      id: 1,
      name: 'Tom Smith'
    }
  }
]

const fetchAnnouncements = async () => {
  await new Promise((resolve) => setTimeout(resolve, 300))
  announcements.value = [...mockAnnouncements]
}

const openCreateDialog = () => {
  editingAnnouncement.value = null
  formData.value = {
    title: '',
    content: '',
    category: 'General',
    status: 'Draft',
    scheduledAt: ''
  }
  showDialog.value = true
}

const openEditDialog = (announcement: Announcement) => {
  editingAnnouncement.value = announcement
  formData.value = {
    title: announcement.title,
    content: announcement.content,
    category: announcement.category,
    status: announcement.status,
    scheduledAt: announcement.scheduledAt || ''
  }
  showDialog.value = true
}

const closeDialog = () => {
  showDialog.value = false
  editingAnnouncement.value = null
}

const saveAnnouncement = async () => {
  await new Promise((resolve) => setTimeout(resolve, 300))

  if (editingAnnouncement.value) {
    const index = announcements.value.findIndex((a) => a.id === editingAnnouncement.value!.id)
    if (index !== -1) {
      announcements.value[index] = {
        ...announcements.value[index],
        title: formData.value.title,
        content: formData.value.content,
        category: formData.value.category,
        status: formData.value.status,
        scheduledAt: formData.value.scheduledAt || null,
        publishedAt:
          formData.value.status === 'Published' ? new Date().toISOString().split('T')[0] : null
      }
    }
  } else {
    const newAnnouncement: Announcement = {
      id: Math.max(...announcements.value.map((a) => a.id), 0) + 1,
      title: formData.value.title,
      content: formData.value.content,
      category: formData.value.category,
      status: formData.value.status,
      publishedAt:
        formData.value.status === 'Published' ? new Date().toISOString().split('T')[0] : null,
      scheduledAt: formData.value.scheduledAt || null,
      createdAt: new Date().toISOString().split('T')[0],
      author: {
        id: parseInt(authStore.user?.id || '1'),
        name: `${authStore.user?.firstName || 'Admin'} ${authStore.user?.lastName || 'User'}`
      }
    }
    announcements.value.push(newAnnouncement)
  }

  closeDialog()
}

const deleteAnnouncement = async (id: number) => {
  if (!confirm('Are you sure you want to delete this announcement?')) {
    return
  }

  await new Promise((resolve) => setTimeout(resolve, 200))
  announcements.value = announcements.value.filter((a) => a.id !== id)
}

onMounted(() => {
  fetchAnnouncements()
})
</script>

<style scoped>
.news-announcements-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 50rem;
  margin: 0 auto;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 2rem;
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

.new-post-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background-color: #204ef6;
  border: none;
  border-radius: 20px;
  color: #ffffff;
  font-weight: 500;
  cursor: pointer;
  transition: transform 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.new-post-button:hover {
  transform: translateY(-0.125rem);
}

.search-filter-section {
  background-color: #ffffff;
  padding: 1.5rem;
  border-radius: 0.75rem;
  margin-bottom: 1.5rem;
}

.filter-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.filter-button {
  padding: 0.5rem 1rem;
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

.filter-button:hover {
  background-color: #e3f2fd;
}

.filter-button.active {
  background-color: #204ef6;
  color: #ffffff;
}

.announcements-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.announcement-card {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.tags {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.category-tag,
.status-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.category-general {
  background-color: #cabeff;
  color: #5b21b6;
}

.category-maintenance {
  background-color: #ffd572;
  color: #92400e;
}

.category-events {
  background-color: #c8daff;
  color: #1e40af;
}

.category-financial {
  background-color: #cabeff;
  color: #5b21b6;
}

.category-safety {
  background-color: #f3f4f6;
  color: #374151;
}

.category-updates {
  background-color: #c8daff;
  color: #1e40af;
}

.status-published {
  background-color: #dcfce7;
  color: #166534;
}

.status-scheduled {
  background-color: #c8daff;
  color: #1e40af;
}

.status-draft {
  background-color: #f3f4f6;
  color: #374151;
}

.menu-button {
  background: transparent;
  border: none;
  cursor: pointer;
  padding: 0.25rem;
  color: #757575;
  display: flex;
  align-items: center;
  justify-content: center;
}

.menu-button:hover {
  color: #212121;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #212121;
  margin: 0 0 0.75rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-content {
  font-size: 0.9375rem;
  color: #616161;
  line-height: 1.5;
  margin: 0 0 1rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 1rem;
  border-top: 0.0625rem solid #e0e0e0;
}

.card-dates {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.date-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.date-item.scheduled {
  color: #204ef6;
}

.date-item svg {
  flex-shrink: 0;
}

.card-author {
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.empty-state {
  text-align: center;
  padding: 3rem;
  color: #9e9e9e;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 48rem) {
  .header-section {
    flex-direction: column;
    gap: 1rem;
  }

  .new-post-button {
    width: 100%;
    justify-content: center;
  }

  .card-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>
