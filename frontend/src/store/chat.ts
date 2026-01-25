import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Message, ChatRoom } from '@/types/chat'

const mockChatRooms: ChatRoom[] = [
  {
    id: '1',
    name: 'General Discussion',
    description: 'General building discussion',
    participants: [],
    lastMessage: {
      id: '1',
      content: 'Hello everyone!',
      senderId: '1',
      senderName: 'John Doe',
      roomId: '1',
      timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
      type: 'text'
    },
    createdAt: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString(),
    updatedAt: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString()
  },
  {
    id: '2',
    name: 'Building Maintenance',
    description: 'Maintenance requests and updates',
    participants: [],
    lastMessage: {
      id: '3',
      content: 'The elevator is fixed',
      senderId: '1',
      senderName: 'Admin',
      roomId: '2',
      timestamp: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString(),
      type: 'text'
    },
    createdAt: new Date(Date.now() - 20 * 24 * 60 * 60 * 1000).toISOString(),
    updatedAt: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString()
  }
]

const mockMessages: Record<string, Message[]> = {
  '1': [
    {
      id: '1',
      content: 'Hello everyone!',
      senderId: '1',
      senderName: 'John Doe',
      roomId: '1',
      timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000).toISOString(),
      type: 'text'
    },
    {
      id: '2',
      content: 'Hi John!',
      senderId: '2',
      senderName: 'Jane Smith',
      roomId: '1',
      timestamp: new Date(Date.now() - 1 * 60 * 60 * 1000).toISOString(),
      type: 'text'
    }
  ],
  '2': [
    {
      id: '3',
      content: 'The elevator is fixed',
      senderId: '1',
      senderName: 'Admin',
      roomId: '2',
      timestamp: new Date(Date.now() - 5 * 60 * 60 * 1000).toISOString(),
      type: 'text'
    }
  ]
}

export const useChatStore = defineStore('chat', () => {
  const connection = ref(null)
  const messages = ref<Message[]>([])
  const chatRooms = ref<ChatRoom[]>([])
  const currentRoom = ref<ChatRoom | null>(null)
  const isConnected = ref(false)

  const startConnection = async () => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    isConnected.value = true
  }

  const stopConnection = async () => {
    await new Promise((resolve) => setTimeout(resolve, 100))
    isConnected.value = false
  }

  const joinRoom = async (roomId: string) => {
    await new Promise((resolve) => setTimeout(resolve, 200))
    const room = chatRooms.value.find((r) => r.id === roomId)
    if (room) {
      currentRoom.value = room
    }
  }

  const leaveRoom = async (roomId: string) => {
    await new Promise((resolve) => setTimeout(resolve, 100))
    if (currentRoom.value?.id === roomId) {
      currentRoom.value = null
    }
  }

  const sendMessage = async (content: string, roomId: string) => {
    await new Promise((resolve) => setTimeout(resolve, 200))
    const newMessage: Message = {
      id: String(Date.now()),
      content,
      senderId: '1',
      senderName: 'You',
      roomId,
      timestamp: new Date().toISOString(),
      type: 'text'
    }
    messages.value.push(newMessage)
  }

  const fetchMessages = async (roomId: string) => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    messages.value = mockMessages[roomId] || []
  }

  const fetchChatRooms = async () => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    chatRooms.value = [...mockChatRooms]
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
