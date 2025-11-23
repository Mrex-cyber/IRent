<template>
  <v-container fluid class="chat-container-fluid pa-0">
    <div class="chat-page">
      <div class="chat-container">
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
            <button class="action-button">
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

        <div class="chat-content">
          <div v-if="currentRoom" class="messages-container">
            <div v-for="message in messages" :key="message.id" class="message-wrapper">
              <div class="message" :class="{ 'message-sent': message.senderId === currentUserId }">
                <div class="message-avatar" v-if="message.senderId !== currentUserId">
                  <img
                    :src="message.senderAvatar || '/placeholder-avatar.jpg'"
                    :alt="message.senderName"
                  />
                </div>
                <div class="message-bubble">
                  <div class="message-text">{{ message.content }}</div>
                  <div class="message-time">
                    {{ message.senderName }} • {{ formatTime(message.timestamp) }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="no-room-selected">
            <div class="room-icon">
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
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
              </svg>
            </div>
            <h3>Select a chat room to start messaging</h3>
          </div>

          <div v-if="currentRoom" class="input-container">
            <input
              v-model="newMessage"
              type="text"
              placeholder="Type a message..."
              class="message-input"
              @keyup.enter="sendMessage"
            />
            <button class="send-button" @click="sendMessage" :disabled="!newMessage.trim()">
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
                <line x1="22" y1="2" x2="11" y2="13" />
                <polygon points="22 2 15 22 11 13 2 9 22 2" />
              </svg>
            </button>
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
import { ref, onMounted, onUnmounted } from 'vue'
import { useChatStore } from '@/store/chat'
import { useAuthStore } from '@/store/auth'

const chatStore = useChatStore()
const authStore = useAuthStore()

const currentRoom = ref(null)
const newMessage = ref('')
const currentUserId = ref('')
const showCalendar = ref(false)
const selectedDate = ref(new Date().toISOString().split('T')[0])

const { messages, chatRooms, isConnected } = chatStore

const selectRoom = async (room) => {
  currentRoom.value = room
  await chatStore.joinRoom(room.id)
  await chatStore.fetchMessages(room.id)
}

const sendMessage = async () => {
  if (newMessage.value.trim() && currentRoom.value) {
    await chatStore.sendMessage(newMessage.value, currentRoom.value.id)
    newMessage.value = ''
  }
}

const formatTime = (timestamp) => {
  return new Date(timestamp).toLocaleTimeString()
}

onMounted(async () => {
  currentUserId.value = authStore.user?.id || ''
  await chatStore.startConnection()
  await chatStore.fetchChatRooms()
})

onUnmounted(async () => {
  await chatStore.stopConnection()
})
</script>

<style scoped>
.chat-container-fluid :deep(.v-container__inner) {
  padding: 0 !important;
  max-width: none !important;
}

.chat-container-fluid {
  padding: 0 !important;
}

:deep(.v-container) {
  padding: 0 !important;
}

.chat-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 0;
  width: 100%;
  display: flex;
  flex-direction: column;
}

.chat-container {
  max-width: 30rem;
  margin: 0 auto;
  width: 100%;
  display: flex;
  flex-direction: column;
  flex: 1;
  background-color: #ffffff;
  border-radius: 1rem;
  overflow: hidden;
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
  margin-bottom: 1.5rem;
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

.chat-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  padding: 1rem;
}

.messages-container {
  flex: 1;
  overflow-y: auto;
  margin-bottom: 1rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.message-wrapper {
  display: flex;
  flex-direction: column;
}

.message {
  display: flex;
  align-items: flex-end;
  gap: 0.5rem;
  max-width: 80%;
}

.message-sent {
  align-self: flex-end;
  flex-direction: row-reverse;
}

.message-avatar {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  overflow: hidden;
  flex-shrink: 0;
}

.message-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.message-bubble {
  background: #e3f2fd;
  border-radius: 0.75rem;
  padding: 0.625rem 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.message-sent .message-bubble {
  background: #1976d2;
}

.message-text {
  font-size: 0.875rem;
  color: #333;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-sent .message-text {
  color: #ffffff;
}

.message-time {
  font-size: 0.6875rem;
  color: #666;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-sent .message-time {
  color: rgba(255, 255, 255, 0.8);
}

.no-room-selected {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 2.5rem;
}

.room-icon {
  color: #ccc;
  margin-bottom: 1rem;
}

.no-room-selected h3 {
  font-size: 1rem;
  color: #666;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.input-container {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem;
  background: #f5f5f5;
  border-radius: 1.5rem;
}

.message-input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.5rem 0.75rem;
  font-size: 0.875rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #333;
  outline: none;
}

.message-input::placeholder {
  color: #999;
}

.send-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background: #1976d2;
  color: #ffffff;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s;
  flex-shrink: 0;
}

.send-button:hover:not(:disabled) {
  background-color: #1565c0;
}

.send-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

@media (max-width: 30rem) {
  .chat-container {
    border-radius: 0.75rem;
  }

  .top-header {
    padding: 0.75rem;
    border-radius: 0.75rem;
  }

  .message {
    max-width: 85%;
  }
}
</style>
