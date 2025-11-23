<template>
  <v-container fluid class="news-container-fluid pa-0">
    <div class="news-page">
      <div class="news-container">
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
            <button class="top-icon-button">
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
            <button class="top-icon-button" @click="showCalendar = true">
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
            <button class="filter-button">
              <span>Last week</span>
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
            </button>
          </div>
        </div>

        <div class="page-header">
          <div class="page-title">COMMUNITY UPDATES</div>
          <button class="add-button" @click="createNewsItem">
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
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <polyline points="14 2 14 8 20 8" />
              <line x1="12" y1="18" x2="12" y2="12" />
              <line x1="9" y1="15" x2="15" y2="15" />
            </svg>
          </button>
        </div>

        <div class="news-feed">
          <div v-for="item in newsItems" :key="item.id" class="news-post">
            <div class="post-header">
              <div class="author-info">
                <div class="author-avatar">
                  <img
                    :src="item.authorAvatar || '/placeholder-avatar.jpg'"
                    :alt="`${item.authorFirstName} ${item.authorLastName}`"
                  />
                </div>
                <div class="author-details">
                  <div class="author-name">
                    {{ item.authorFirstName }} {{ item.authorLastName }}
                  </div>
                  <div class="post-time">{{ getTimeAgo(item.createdAt) }}</div>
                </div>
              </div>
              <button v-if="canEdit(item)" class="edit-button" @click="editNewsItem(item)">
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
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
              </button>
            </div>

            <div class="post-image-container">
              <img
                :src="item.imageUrl || '/placeholder-news.jpg'"
                :alt="item.title"
                class="post-image"
              />
              <div class="image-indicators">
                <span
                  v-for="(image, index) in item.images"
                  :key="index"
                  :class="{ active: index === 0 }"
                  class="indicator-dot"
                ></span>
              </div>
            </div>

            <div class="post-content">
              <h3 class="post-title">{{ item.title }}</h3>
              <div class="hashtags">
                <span v-for="hashtag in item.hashtags" :key="hashtag" class="hashtag">
                  #{{ hashtag }}
                </span>
              </div>
            </div>

            <div class="post-footer">
              <button class="comment-button">
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
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <span>{{ item.commentCount || 0 }}</span>
              </button>
            </div>
          </div>

          <div v-if="newsItems.length === 0" class="no-posts">
            <svg
              width="64"
              height="64"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
              <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
            </svg>
            <h3>No community updates yet</h3>
            <p>Be the first to share something with your community!</p>
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
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/store/auth'

const authStore = useAuthStore()
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])

const newsItems = ref([
  {
    id: 1,
    title: 'Schedule of Repair Work',
    imageUrl: 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=400&fit=crop',
    images: ['https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=400&fit=crop'],
    authorFirstName: 'Tom',
    authorLastName: 'Smith',
    authorAvatar: '/placeholder-avatar.jpg',
    createdAt: new Date(Date.now() - 2 * 60 * 60 * 1000),
    hashtags: ['CommunityLife', 'CondoLiving'],
    commentCount: 3
  },
  {
    id: 2,
    title: 'Monthly Meeting Announcement',
    imageUrl: 'https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=400&fit=crop',
    images: ['https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=400&fit=crop'],
    authorFirstName: 'Tom',
    authorLastName: 'Smith',
    authorAvatar: '/placeholder-avatar.jpg',
    createdAt: new Date(Date.now() - 2 * 60 * 60 * 1000),
    hashtags: ['Meeting', 'Community'],
    commentCount: 0
  }
])

const isOSBBRepresentative = computed(() => {
  return authStore.user?.role === 'OSBBRepresentative' || authStore.user?.role === 'Realtor'
})

const canEdit = (item: any) => {
  return isOSBBRepresentative.value || item.authorId === authStore.user?.id
}

const getTimeAgo = (date: Date) => {
  const seconds = Math.floor((Date.now() - date.getTime()) / 1000)

  if (seconds < 60) return 'just now'
  if (seconds < 3600) {
    const minutes = Math.floor(seconds / 60)
    return `${minutes}m ago`
  }
  if (seconds < 86400) {
    const hours = Math.floor(seconds / 3600)
    return `${hours}h ago`
  }
  const days = Math.floor(seconds / 86400)
  return `${days}d ago`
}

const createNewsItem = () => {
  console.log('Create news item')
}

const editNewsItem = (item: any) => {
  console.log('Edit news item', item)
}

const fetchNewsItems = async () => {
  try {
    const response = await fetch('/api/news')
    if (response.ok) {
      const data = await response.json()
      newsItems.value = data.map((item: any) => ({
        ...item,
        createdAt: new Date(item.createdAt)
      }))
    }
  } catch (error) {
    console.error('Fetch news error:', error)
  }
}

onMounted(() => {
  fetchNewsItems()
})
</script>

<style scoped>
.news-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.news-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.news-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.news-container {
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
  background-color: #ffffff;
  padding: 1rem;
  border-radius: 1rem;
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
  margin-bottom: 1rem;
  padding: 0 1rem;
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

.filter-button {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  background: #ffffff;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 1.25rem;
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: all 0.2s;
}

.filter-button:hover {
  border-color: #1976d2;
  color: #1976d2;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.25rem;
  padding: 0 1rem;
}

.page-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.03125rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.add-button {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  border: 0.0625rem solid #1976d2;
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #1976d2;
  padding: 0;
  transition: all 0.2s;
}

.add-button:hover {
  background: #e3f2fd;
}

.news-feed {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.news-post {
  background: #ffffff;
  border-radius: 1rem;
  overflow: hidden;
}

.post-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 1rem;
}

.author-info {
  display: flex;
  align-items: center;
  gap: 0.625rem;
}

.author-avatar {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}

.author-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.author-details {
  display: flex;
  flex-direction: column;
  gap: 0.125rem;
}

.author-name {
  font-size: 0.875rem;
  font-weight: 600;
  color: #000;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.post-time {
  font-size: 0.75rem;
  color: #999;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.edit-button {
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

.edit-button:hover {
  background-color: #f5f5f5;
}

.post-image-container {
  position: relative;
  width: 100%;
  aspect-ratio: 1;
}

.post-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-indicators {
  position: absolute;
  bottom: 0.75rem;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 0.375rem;
  align-items: center;
}

.indicator-dot {
  width: 0.375rem;
  height: 0.375rem;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transition: all 0.2s;
}

.indicator-dot.active {
  background: #ffffff;
  width: 1.25rem;
  border-radius: 0.1875rem;
}

.post-content {
  padding: 0.75rem 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.625rem;
}

.post-title {
  font-size: 1rem;
  font-weight: 600;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.hashtags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.hashtag {
  background: #e3f2fd;
  color: #1976d2;
  padding: 0.25rem 0.75rem;
  border-radius: 0.75rem;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.post-footer {
  padding: 0.75rem 1rem;
  border-top: 0.0625rem solid #f0f0f0;
  display: flex;
  justify-content: flex-end;
}

.comment-button {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  background: transparent;
  border: none;
  cursor: pointer;
  color: #666;
  font-size: 0.875rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  transition: all 0.2s;
}

.comment-button:hover {
  color: #1976d2;
}

.no-posts {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 3.75rem 1.25rem;
  text-align: center;
  color: #999;
}

.no-posts svg {
  margin-bottom: 1rem;
  color: #ccc;
}

.no-posts h3 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #666;
  margin: 0 0 0.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.no-posts p {
  font-size: 0.875rem;
  color: #999;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

@media (max-width: 30rem) {
  .news-page {
    padding: 1rem;
  }

  .top-header {
    padding: 0.75rem;
    border-radius: 0.75rem;
  }

  .news-post {
    border-radius: 0.75rem;
  }
}
</style>
