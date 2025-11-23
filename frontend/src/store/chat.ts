import { defineStore } from 'pinia'
import { ref } from 'vue'
import { HubConnection, HubConnectionBuilder } from '@microsoft/signalr'
import type { Message, ChatRoom } from '@/types/chat'

export const useChatStore = defineStore('chat', () => {
  const connection = ref<HubConnection | null>(null)
  const messages = ref<Message[]>([])
  const chatRooms = ref<ChatRoom[]>([])
  const currentRoom = ref<ChatRoom | null>(null)
  const isConnected = ref(false)

  const startConnection = async () => {
    try {
      connection.value = new HubConnectionBuilder()
        .withUrl('/signalr/chat')
        .withAutomaticReconnect()
        .build()

      connection.value.on('ReceiveMessage', (message: Message) => {
        messages.value.push(message)
      })

      connection.value.on('RoomJoined', (room: ChatRoom) => {
        chatRooms.value.push(room)
      })

      connection.value.on('RoomLeft', (roomId: string) => {
        chatRooms.value = chatRooms.value.filter((room) => room.id !== roomId)
      })

      await connection.value.start()
      isConnected.value = true
    } catch (error) {
      console.error('SignalR connection error:', error)
    }
  }

  const stopConnection = async () => {
    if (connection.value) {
      await connection.value.stop()
      isConnected.value = false
    }
  }

  const joinRoom = async (roomId: string) => {
    if (connection.value) {
      await connection.value.invoke('JoinRoom', roomId)
    }
  }

  const leaveRoom = async (roomId: string) => {
    if (connection.value) {
      await connection.value.invoke('LeaveRoom', roomId)
    }
  }

  const sendMessage = async (content: string, roomId: string) => {
    if (connection.value) {
      await connection.value.invoke('SendMessage', content, roomId)
    }
  }

  const fetchMessages = async (roomId: string) => {
    try {
      const response = await fetch(`/api/chat/rooms/${roomId}/messages`)
      if (response.ok) {
        messages.value = await response.json()
      }
    } catch (error) {
      console.error('Fetch messages error:', error)
    }
  }

  const fetchChatRooms = async () => {
    try {
      const response = await fetch('/api/chat/rooms')
      if (response.ok) {
        chatRooms.value = await response.json()
      }
    } catch (error) {
      console.error('Fetch chat rooms error:', error)
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
