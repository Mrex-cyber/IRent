<template>
  <v-container fluid class="pa-0">
    <div class="messages-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">MESSAGES</h1>
            <p class="page-subtitle">Manage resident requests and communications</p>
          </div>
          <div class="header-actions">
            <button class="action-button outline" @click="openCreateGroupDialog">
              <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
              >
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
              <span>Create Group Chat</span>
            </button>
            <button class="action-button primary" @click="openBroadcastDialog">
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
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
              </svg>
              <span>Send Broadcast</span>
            </button>
          </div>
        </div>

        <div class="search-filter-section">
          <SearchField v-model="searchQuery" placeholder="Search name, category, or preview" />

          <div class="filter-buttons">
            <button
              v-for="filter in filters"
              :key="filter"
              :class="['filter-button', { active: selectedFilter === filter }]"
              @click="selectFilter(filter)"
            >
              {{ filter }}
            </button>
          </div>
        </div>

        <div class="messages-layout">
          <div class="left-panel">
            <div class="tabs">
              <button
                :class="['tab', { active: activeTab === 'messages' }]"
                @click="activeTab = 'messages'"
              >
                MESSAGES
              </button>
              <button
                :class="['tab', { active: activeTab === 'groups' }]"
                @click="activeTab = 'groups'"
              >
                GROUPS
              </button>
            </div>

            <div class="messages-list">
              <div
                v-for="message in messages"
                :key="message.conversation_id"
                :class="[
                  'message-item',
                  { active: selectedMessage?.conversation_id === message.conversation_id }
                ]"
                @click="selectMessage(message)"
              >
                <div class="message-avatar">
                  <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                </div>
                <div class="message-content">
                  <div class="message-header">
                    <span class="message-name">{{
                      message.type === 'group'
                        ? message.subject || participantNamesDisplay(message.participant_names)
                        : message.senderName
                    }}</span>
                    <span :class="['message-tag', getTagClass(message.category)]">
                      {{ message.category }}
                    </span>
                  </div>
                  <p v-if="message.type === 'group'" class="message-participants">
                    {{ participantNamesDisplay(message.participant_names) }}
                  </p>
                  <p class="message-snippet">{{ truncateContent(message.content) }}</p>
                  <div class="message-footer">
                    <span class="message-role">{{ message.senderRole }}</span>
                    <span class="message-date">{{ formatDate(message.date) }}</span>
                  </div>
                </div>
              </div>

              <div v-if="!isLoading && messages.length === 0" class="empty-list">
                <p>
                  {{ activeTab === 'groups' ? 'No groups yet' : 'No messages found' }}
                </p>
              </div>
            </div>
          </div>

          <div class="right-panel">
            <div v-if="selectedMessage" class="conversation-view">
              <div class="conversation-header">
                <div class="conversation-user">
                  <div class="conversation-avatar-large">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                      <circle cx="12" cy="7" r="4" />
                    </svg>
                  </div>
                  <div class="conversation-info">
                    <div class="conversation-name-row">
                      <h3>
                        {{
                          selectedMessage.type === 'group'
                            ? selectedMessage.subject ||
                              participantNamesDisplay(selectedMessage.participant_names)
                            : selectedMessage.senderName
                        }}
                      </h3>
                      <span :class="['conversation-tag', getTagClass(selectedMessage.category)]">
                        {{ selectedMessage.category }}
                      </span>
                    </div>
                    <p class="conversation-location">
                      {{
                        selectedMessage.type === 'group'
                          ? participantNamesDisplay(selectedMessage.participant_names)
                          : directConversationLocation(selectedMessage)
                      }}
                    </p>
                  </div>
                </div>
              </div>
              <div class="conversation-messages">
                <div
                  v-for="msg in conversationMessages"
                  :key="msg.id"
                  :class="['conversation-message-wrapper', { sent: msg.sent }]"
                >
                  <div v-if="!msg.sent" class="conversation-message incoming">
                    <div class="message-avatar-small incoming-avatar">
                      <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                      </svg>
                    </div>
                    <div class="message-content-wrapper">
                      <div class="message-bubble incoming-bubble">
                        {{ msg.content }}
                      </div>
                      <div class="message-meta">
                        <span class="message-role">{{ selectedMessage.senderRole }}</span>
                        <span class="message-date">{{ formatMessageDate(msg.timestamp) }}</span>
                      </div>
                    </div>
                  </div>
                  <div v-else class="conversation-message sent">
                    <div class="message-bubble sent-bubble">
                      {{ msg.content }}
                    </div>
                    <div class="message-avatar-small">
                      <span>{{ getInitials(authStore.user) }}</span>
                    </div>
                    <div class="message-meta sent-meta">
                      <span class="message-time">{{ formatMessageTime(msg.timestamp) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="conversation-input">
                <input
                  v-model="replyText"
                  type="text"
                  placeholder="Type your message..."
                  @keyup.enter="sendReply"
                />
                <button class="send-button" @click="sendReply">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                  >
                    <line x1="22" y1="2" x2="11" y2="13" />
                    <polygon points="22 2 15 22 11 13 2 9 22 2" />
                  </svg>
                </button>
              </div>
            </div>
            <div v-else class="empty-conversation">
              <div class="empty-icon">
                <svg
                  width="80"
                  height="80"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                >
                  <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
              </div>
              <p>Select a message to view conversation</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <v-dialog
      v-model="showCreateGroupDialog"
      max-width="500"
      @click:outside="closeCreateGroupDialog"
    >
      <v-card>
        <v-card-title>Create Group Chat</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="groupName"
            label="Group Name"
            variant="outlined"
            :disabled="createGroupLoading"
          ></v-text-field>
          <v-textarea
            v-model="groupDescription"
            label="Description"
            variant="outlined"
            rows="3"
            :disabled="createGroupLoading"
          ></v-textarea>
          <v-select
            v-model="groupParticipantIds"
            :items="usersForSelect"
            item-title="name"
            item-value="id"
            label="Add members"
            variant="outlined"
            multiple
            chips
            :disabled="createGroupLoading"
          ></v-select>
          <p v-if="groupError" class="dialog-error">{{ groupError }}</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" :disabled="createGroupLoading" @click="closeCreateGroupDialog"
            >Cancel</v-btn
          >
          <v-btn color="primary" :loading="createGroupLoading" @click="createGroup">Create</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showBroadcastDialog" max-width="500" @click:outside="closeBroadcastDialog">
      <v-card>
        <v-card-title>Send Broadcast</v-card-title>
        <v-card-text>
          <v-text-field
            v-model="broadcastSubject"
            label="Subject"
            variant="outlined"
            :disabled="sendBroadcastLoading"
          ></v-text-field>
          <v-textarea
            v-model="broadcastContent"
            label="Message"
            variant="outlined"
            rows="5"
            :disabled="sendBroadcastLoading"
          ></v-textarea>
          <v-select
            v-model="broadcastRecipientIds"
            :items="usersForSelect"
            item-title="name"
            item-value="id"
            label="Recipients"
            variant="outlined"
            multiple
            chips
            :disabled="sendBroadcastLoading"
          ></v-select>
          <p v-if="broadcastError" class="dialog-error">{{ broadcastError }}</p>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn variant="text" :disabled="sendBroadcastLoading" @click="closeBroadcastDialog"
            >Cancel</v-btn
          >
          <v-btn color="primary" :loading="sendBroadcastLoading" @click="sendBroadcast">Send</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useAuthStore } from '@/store/auth'
import apiClient from '@/services/api/client'
import SearchField from '@/components/SearchField.vue'

interface Message {
  id: number
  conversation_id: number
  type: string
  participant_count: number
  participant_names: string[]
  subject?: string
  senderName: string
  senderRole: string
  content: string
  category: string
  date: string
  building?: string
  apartment?: string
}

interface ConversationMessage {
  id: number
  content: string
  timestamp: string
  sent: boolean
}

interface ManagementUser {
  id: number
  first_name: string
  last_name: string
  email: string
  name: string
}

const authStore = useAuthStore()
const messages = ref<Message[]>([])
const isLoading = ref(false)
const selectedMessage = ref<Message | null>(null)
const conversationMessages = ref<ConversationMessage[]>([])
const searchQuery = ref('')
const selectedFilter = ref('All')
const activeTab = ref<'messages' | 'groups'>('messages')
const replyText = ref('')
const showCreateGroupDialog = ref(false)
const showBroadcastDialog = ref(false)
const users = ref<ManagementUser[]>([])
const groupName = ref('')
const groupDescription = ref('')
const groupParticipantIds = ref<number[]>([])
const broadcastSubject = ref('')
const broadcastContent = ref('')
const broadcastRecipientIds = ref<number[]>([])
const createGroupLoading = ref(false)
const sendBroadcastLoading = ref(false)
const groupError = ref('')
const broadcastError = ref('')

const filters = [
  'All',
  'Suggestions and complaints',
  'Legal Matter',
  'Maintenance',
  'Financial',
  'General'
]

const SEARCH_DEBOUNCE_MS = 700
let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null
const debouncedSearchText = ref('')

const usersForSelect = computed(() => {
  const currentId = authStore.user?.id
  if (!currentId) return users.value
  return users.value.filter((u) => u.id !== currentId)
})

const buildListParams = (): Record<string, string> => {
  const params: Record<string, string> = {
    tab: activeTab.value === 'groups' ? 'groups' : 'messages'
  }
  if (selectedFilter.value !== 'All') {
    params.category = selectedFilter.value
  }
  if (debouncedSearchText.value !== '') {
    params.searchFieldText = debouncedSearchText.value
  }
  return params
}

const participantNamesDisplay = (names: string[] | undefined): string => {
  if (!names?.length) return ''
  return names.join(', ')
}

const emDash = '—'

const directConversationLocation = (m: Message): string => {
  const parts = [m.building, m.apartment].filter(
    (p) => p != null && String(p).trim() !== ''
  ) as string[]
  return parts.length ? parts.join(' · ') : emDash
}

const getTagClass = (category: string) => {
  const classes: Record<string, string> = {
    'Suggestions and complaints': 'tag-blue',
    'Legal Matter': 'tag-purple',
    Maintenance: 'tag-yellow',
    Financial: 'tag-green',
    General: 'tag-gray'
  }
  return classes[category] || 'tag-gray'
}

const truncateContent = (content: string) => {
  return content.length > 80 ? content.substring(0, 80) + '...' : content
}

const formatDate = (date: string) => {
  const d = new Date(date)
  return `${String(d.getDate()).padStart(2, '0')}.${String(d.getMonth() + 1).padStart(2, '0')}.${String(d.getFullYear()).slice(2)}`
}

const formatTime = (timestamp: string) => {
  return new Date(timestamp).toLocaleTimeString('en-US', {
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatMessageDate = (timestamp: string) => {
  const date = new Date(timestamp)
  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = String(date.getFullYear()).slice(2)
  return `${day}.${month}.${year}`
}

const formatMessageTime = (timestamp: string) => {
  const date = new Date(timestamp)
  const now = new Date()
  const isToday = date.toDateString() === now.toDateString()

  if (isToday) {
    return `Today ${date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false })}`
  }
  return formatMessageDate(timestamp)
}

const getInitials = (user: any) => {
  if (!user) return 'A'
  const firstName = user.firstName || user.name?.split(' ')[0] || ''
  const lastName = user.lastName || user.name?.split(' ')[1] || ''
  return `${firstName.charAt(0)}${lastName.charAt(0)}`.toUpperCase() || 'A'
}

const selectFilter = (filter: string) => {
  selectedFilter.value = filter
  debouncedSearchText.value = searchQuery.value.trim()
  if (searchDebounceTimer !== null) {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = null
  }
  fetchMessages()
}

const selectMessage = (message: Message) => {
  selectedMessage.value = message
  fetchConversationByConversationId(message.conversation_id)
}

const fetchMessages = async () => {
  isLoading.value = true
  try {
    const inboxParams = buildListParams()
    const { data } = await apiClient.get<Message[]>('management/messages', {
      params: inboxParams
    })
    messages.value = data ?? []
    if (
      selectedMessage.value &&
      !messages.value.some((m) => m.conversation_id === selectedMessage.value!.conversation_id)
    ) {
      selectedMessage.value = null
      conversationMessages.value = []
    }
  } catch (error) {
    console.error('Fetch messages error:', error)
    messages.value = []
  } finally {
    isLoading.value = false
  }
}

watch(searchQuery, () => {
  if (searchDebounceTimer !== null) {
    clearTimeout(searchDebounceTimer)
  }
  searchDebounceTimer = setTimeout(() => {
    searchDebounceTimer = null
    debouncedSearchText.value = searchQuery.value.trim()
    fetchMessages()
  }, SEARCH_DEBOUNCE_MS)
})

watch(activeTab, () => {
  debouncedSearchText.value = searchQuery.value.trim()
  if (searchDebounceTimer !== null) {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = null
  }
  fetchMessages()
})

interface ConversationApiMessage {
  id: string
  content: string
  senderId: string
  timestamp: string
}

const fetchConversationByConversationId = async (conversationId: number) => {
  try {
    const { data } = await apiClient.get<ConversationApiMessage[]>(
      `management/conversations/${conversationId}/messages`
    )
    const currentUserId = String(authStore.user?.id ?? '')
    conversationMessages.value = (data ?? []).map((m) => ({
      id: Number(m.id),
      content: m.content,
      timestamp: m.timestamp,
      sent: String(m.senderId) === currentUserId
    }))
  } catch (error) {
    console.error('Fetch conversation error:', error)
    conversationMessages.value = []
  }
}

const sendReply = async () => {
  if (!replyText.value.trim() || !selectedMessage.value) return

  const conversationId = selectedMessage.value.conversation_id
  try {
    await apiClient.post(`management/conversations/${conversationId}/messages`, {
      content: replyText.value.trim()
    })
    replyText.value = ''
    await fetchConversationByConversationId(conversationId)
  } catch (error) {
    console.error('Send reply error:', error)
  }
}

const fetchUsers = async () => {
  try {
    const { data } = await apiClient.get<ManagementUser[]>('management/users')
    users.value = data ?? []
  } catch (error) {
    console.error('Fetch users error:', error)
    users.value = []
  }
}

const openCreateGroupDialog = async () => {
  groupError.value = ''
  groupName.value = ''
  groupDescription.value = ''
  groupParticipantIds.value = []
  if (users.value.length === 0) await fetchUsers()
  showCreateGroupDialog.value = true
}

const openBroadcastDialog = async () => {
  broadcastError.value = ''
  broadcastSubject.value = ''
  broadcastContent.value = ''
  broadcastRecipientIds.value = []
  if (users.value.length === 0) await fetchUsers()
  showBroadcastDialog.value = true
}

const closeCreateGroupDialog = () => {
  showCreateGroupDialog.value = false
  groupError.value = ''
}

const closeBroadcastDialog = () => {
  showBroadcastDialog.value = false
  broadcastError.value = ''
}

const createGroup = async () => {
  const name = groupName.value.trim()
  if (!name) {
    groupError.value = 'Group name is required.'
    return
  }
  if (groupParticipantIds.value.length === 0) {
    groupError.value = 'Select at least one member.'
    return
  }
  groupError.value = ''
  createGroupLoading.value = true
  try {
    await apiClient.post('management/conversations', {
      name,
      description: groupDescription.value.trim() || undefined,
      type: 'group',
      participant_ids: groupParticipantIds.value
    })
    await fetchMessages()
    closeCreateGroupDialog()
  } catch (error: unknown) {
    const msg =
      error && typeof error === 'object' && 'response' in error
        ? (error as { response?: { data?: { message?: string } } }).response?.data?.message
        : null
    groupError.value = msg || 'Failed to create group.'
  } finally {
    createGroupLoading.value = false
  }
}

const sendBroadcast = async () => {
  const subject = broadcastSubject.value.trim()
  const content = broadcastContent.value.trim()
  if (!subject) {
    broadcastError.value = 'Subject is required.'
    return
  }
  if (!content) {
    broadcastError.value = 'Message is required.'
    return
  }
  if (broadcastRecipientIds.value.length === 0) {
    broadcastError.value = 'Select at least one recipient.'
    return
  }
  broadcastError.value = ''
  sendBroadcastLoading.value = true
  try {
    await apiClient.post('management/broadcasts', {
      subject,
      content,
      recipient_ids: broadcastRecipientIds.value
    })
    await fetchMessages()
    closeBroadcastDialog()
  } catch (error: unknown) {
    const msg =
      error && typeof error === 'object' && 'response' in error
        ? (error as { response?: { data?: { message?: string } } }).response?.data?.message
        : null
    broadcastError.value = msg || 'Failed to send broadcast.'
  } finally {
    sendBroadcastLoading.value = false
  }
}

onMounted(() => {
  fetchMessages()
})

onUnmounted(() => {
  if (searchDebounceTimer !== null) {
    clearTimeout(searchDebounceTimer)
    searchDebounceTimer = null
  }
})
</script>

<style scoped>
.dialog-error {
  color: #b00020;
  font-size: 0.875rem;
  margin-top: 0.5rem;
  margin-bottom: 0;
}

.messages-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 90rem;
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

.header-actions {
  display: flex;
  gap: 1rem;
}

.action-button {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  border: 0.125rem solid #204ef6;
}

.action-button.outline {
  background-color: #ffffff;
  color: #204ef6;
}

.action-button.outline:hover {
  background-color: #e3f2fd;
}

.action-button.primary {
  background-color: #204ef6;
  color: #ffffff;
  border: none;
}

.action-button.primary:hover {
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

.messages-layout {
  display: grid;
  grid-template-columns: 1fr 1.5fr;
  gap: 1.5rem;
  background-color: #ffffff;
  border-radius: 0.75rem;
  overflow: hidden;
  min-height: 40rem;
}

.left-panel {
  border-right: 0.0625rem solid #e0e0e0;
  display: flex;
  flex-direction: column;
}

.tabs {
  display: flex;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.tab {
  flex: 1;
  padding: 1rem;
  background: transparent;
  border: none;
  border-bottom: 0.1875rem solid transparent;
  font-weight: 600;
  font-size: 0.875rem;
  color: #757575;
  cursor: pointer;
  transition: all 0.2s;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  text-transform: uppercase;
}

.tab:hover {
  color: #204ef6;
}

.tab.active {
  color: #204ef6;
  border-bottom-color: #204ef6;
}

.messages-list {
  flex: 1;
  overflow-y: auto;
}

.message-item {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  border-bottom: 0.0625rem solid #f5f5f5;
  cursor: pointer;
  transition: background-color 0.2s;
}

.message-item:hover {
  background-color: #f9f9f9;
}

.message-item.active {
  background-color: #e3f2fd;
}

.message-avatar {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #757575;
  flex-shrink: 0;
}

.message-content {
  flex: 1;
  min-width: 0;
}

.message-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
  gap: 0.5rem;
}

.message-name {
  font-weight: 600;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  font-size: 0.75rem;
  font-weight: 500;
  white-space: nowrap;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.tag-blue {
  background-color: #c8daff;
  color: #1e40af;
}

.tag-purple {
  background-color: #cabeff;
  color: #5b21b6;
}

.tag-yellow {
  background-color: #ffd572;
  color: #92400e;
}

.tag-green {
  background-color: #dcfce7;
  color: #166534;
}

.tag-gray {
  background-color: #f3f4f6;
  color: #374151;
}

.message-participants {
  font-size: 0.75rem;
  color: #757575;
  margin: 0.25rem 0 0 0;
  line-height: 1.4;
}

.message-snippet {
  font-size: 0.875rem;
  color: #616161;
  line-height: 1.5;
  margin: 0 0 0.5rem 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.75rem;
  color: #204ef6;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-role {
  color: #204ef6;
}

.message-date {
  color: #757575;
}

.empty-list {
  padding: 2rem;
  text-align: center;
  color: #9e9e9e;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.right-panel {
  display: flex;
  flex-direction: column;
}

.empty-conversation {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #9e9e9e;
  padding: 3rem;
}

.empty-icon {
  margin-bottom: 1rem;
  color: #bdbdbd;
}

.empty-conversation p {
  font-size: 1rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.conversation-view {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.conversation-header {
  padding: 1.5rem;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.conversation-user {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.conversation-avatar-large {
  width: 4rem;
  height: 4rem;
  border-radius: 50%;
  background-color: #e0e0e0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #757575;
  flex-shrink: 0;
}

.conversation-info {
  flex: 1;
}

.conversation-name-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.25rem;
}

.conversation-info h3 {
  margin: 0;
  font-size: 1.125rem;
  font-weight: 600;
  color: #212121;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.conversation-location {
  margin: 0;
  font-size: 0.875rem;
  color: #757575;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.conversation-tag {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 500;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.conversation-messages {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  background-color: #fafafa;
}

.conversation-message-wrapper {
  display: flex;
  flex-direction: column;
}

.conversation-message-wrapper.sent {
  align-items: flex-end;
}

.conversation-message {
  display: flex;
  flex-direction: column;
  max-width: 70%;
}

.conversation-message.incoming {
  align-items: flex-start;
  flex-direction: row;
  gap: 0.5rem;
}

.conversation-message.sent {
  align-items: flex-end;
  flex-direction: row;
  align-items: flex-end;
  gap: 0.5rem;
}

.message-bubble {
  padding: 0.75rem 1rem;
  border-radius: 0.75rem;
  font-size: 0.875rem;
  line-height: 1.5;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  word-wrap: break-word;
}

.incoming-bubble {
  background-color: #e0e0e0;
  color: #212121;
}

.sent-bubble {
  background-color: #1976d2;
  color: #ffffff;
  box-shadow: 0 1px 2px rgba(25, 118, 210, 0.25);
}

.message-meta {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin-top: 0.5rem;
  font-size: 0.75rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.message-role {
  color: #204ef6;
  font-weight: 500;
}

.message-date {
  color: #757575;
}

.message-time {
  color: #757575;
}

.sent-meta {
  margin-top: 0.5rem;
}

.message-avatar-small {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background-color: #1976d2;
  color: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 600;
  flex-shrink: 0;
}

.message-avatar-small.incoming-avatar {
  background-color: #e0e0e0;
  color: #757575;
}

.message-content-wrapper {
  display: flex;
  flex-direction: column;
  flex: 1;
}

.conversation-input {
  display: flex;
  gap: 0.5rem;
  padding: 1rem 1.5rem;
  border-top: 0.0625rem solid #e0e0e0;
  background-color: #ffffff;
  align-items: center;
}

.conversation-input input {
  flex: 1;
  padding: 0.75rem 1rem;
  border: 0.0625rem solid #e0e0e0;
  border-radius: 1.5rem;
  outline: none;
  font-size: 0.875rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.conversation-input input:focus {
  border-color: #204ef6;
}

.conversation-input input::placeholder {
  color: #9e9e9e;
}

.send-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background-color: #204ef6;
  color: #ffffff;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s;
  flex-shrink: 0;
}

.send-button:hover {
  background-color: #1976d2;
}

.send-button svg {
  stroke: #ffffff;
}

@media (max-width: 48rem) {
  .messages-layout {
    grid-template-columns: 1fr;
  }

  .right-panel {
    display: none;
  }

  .header-section {
    flex-direction: column;
    gap: 1rem;
  }

  .header-actions {
    width: 100%;
    flex-direction: column;
  }

  .action-button {
    width: 100%;
    justify-content: center;
  }
}
</style>
