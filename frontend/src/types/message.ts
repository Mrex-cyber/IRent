export interface Message {
  id: number
  senderName: string
  senderRole: string
  content: string
  category: string
  date: string
  building?: string
  apartment?: string
}

export interface ConversationMessage {
  id: number
  content: string
  timestamp: string
  sent: boolean
}
