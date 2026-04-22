import { defineStore } from 'pinia'
import { ref } from 'vue'
import apiClient from '@/services/api/client'
import type { Message, ChatRoom } from '@/types/chat'

export const useChatStore = defineStore('chat', () => {
  const connection = ref(null)
  const messages = ref<Message[]>([])
  const chatRooms = ref<ChatRoom[]>([])
  const currentRoom = ref<ChatRoom | null>(null)
  const isConnected = ref(false)

  const startConnection = async () => {
    await Promise.resolve()
    isConnected.value = true
  }

  const stopConnection = async () => {
    await Promise.resolve()
    isConnected.value = false
  }

  const joinRoom = async (roomId: string) => {
    const room = chatRooms.value.find((r) => r.id === roomId)
    if (room) {
      currentRoom.value = room
    }
  }

  const leaveRoom = async (roomId: string) => {
    if (currentRoom.value?.id === roomId) {
      currentRoom.value = null
    }
  }

  const sendMessage = async (content: string, roomId: string) => {
    try {
      const { data } = await apiClient.post(`management/conversations/${roomId}/messages`, {
        content: content.trim()
      })
      const msg: Message = {
        id: data.id,
        content: data.content,
        senderId: data.senderId,
        senderName: data.senderName,
        roomId: data.roomId,
        timestamp: data.timestamp,
        type: data.type || 'text'
      }
      messages.value.push(msg)
    } catch {}
  }

  const fetchMessages = async (roomId: string) => {
    try {
      const { data } = await apiClient.get(`management/conversations/${roomId}/messages`)
      messages.value = (Array.isArray(data) ? data : []).map((m: Record<string, unknown>) => ({
        id: String(m.id),
        content: String(m.content),
        senderId: String(m.senderId),
        senderName: String(m.senderName ?? ''),
        roomId: String(m.roomId),
        timestamp: String(m.timestamp),
        type: (m.type as Message['type']) || 'text'
      }))
    } catch {
      messages.value = []
    }
  }

  const fetchChatRooms = async () => {
    try {
      const { data } = await apiClient.get('management/conversations')
      chatRooms.value = (Array.isArray(data) ? data : []).map((r: Record<string, unknown>) => ({
        id: String(r.id),
        name: String(r.name ?? ''),
        description: r.description != null ? String(r.description) : undefined,
        participants: Array.isArray(r.participants)
          ? (r.participants as Record<string, unknown>[]).map((p) => ({
              id: String(p.id),
              name: String(p.name ?? ''),
              email: String(p.email ?? ''),
              isOnline: false
            }))
          : [],
        lastMessage:
          r.lastMessage != null && typeof r.lastMessage === 'object'
            ? {
                id: String((r.lastMessage as Record<string, unknown>).id),
                content: String((r.lastMessage as Record<string, unknown>).content),
                senderId: String((r.lastMessage as Record<string, unknown>).senderId),
                senderName: String((r.lastMessage as Record<string, unknown>).senderName),
                roomId: String((r.lastMessage as Record<string, unknown>).roomId),
                timestamp: String((r.lastMessage as Record<string, unknown>).timestamp),
                type: 'text' as const
              }
            : undefined,
        createdAt: String(r.createdAt ?? ''),
        updatedAt: String(r.updatedAt ?? '')
      }))
    } catch {
      chatRooms.value = []
    }
  }

  return {
    connection,
    messages,
    chatRooms,
    currentRoom,
    isConnected,
    startConnection,
    stopConnection,
    joinRoom,
    leaveRoom,
    sendMessage,
    fetchMessages,
    fetchChatRooms
  }
})
