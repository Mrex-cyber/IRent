import apiClient from './client'
import type { LoginCredentials, RegisterData, User, AuthResponse } from '@/types/auth'

export const authApi = {
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    const response = await apiClient.post('/auth/login', credentials)
    return response.data
  },

  register: async (userData: RegisterData): Promise<AuthResponse> => {
    const response = await apiClient.post('/auth/register', userData)
    return response.data
  },

  getCurrentUser: async (): Promise<User> => {
    const response = await apiClient.get('/auth/me')
    return response.data
  },

  refreshToken: async (): Promise<{ token: string }> => {
    const response = await apiClient.post('/auth/refresh')
    return response.data
  },

  logout: async (): Promise<void> => {
    await apiClient.post('/auth/logout')
  }
}
