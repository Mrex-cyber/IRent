export interface Announcement {
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
