export interface Message {
  id: string
  content: string
  senderId: string
  senderName: string
  roomId: string
  timestamp: string
  type: 'text' | 'image' | 'file'
}

export interface ChatRoom {
  id: string
  name: string
  description?: string
  participants: ChatParticipant[]
  lastMessage?: Message
  createdAt: string
  updatedAt: string
}

export interface ChatParticipant {
  id: string
  name: string
  email: string
  avatar?: string
  isOnline: boolean
  lastSeen?: string
}
