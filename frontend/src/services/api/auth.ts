import apiClient from './client'
import type { LoginCredentials, RegisterData, User, AuthResponse } from '@/types/auth'

const normalizeUser = (raw: Record<string, unknown>): User => ({
  id: String(raw.id),
  email: String(raw.email),
  firstName: String(raw.first_name ?? raw.firstName ?? ''),
  lastName: String(raw.last_name ?? raw.lastName ?? ''),
  role: (raw.role ?? 'Tenant') as User['role'],
  avatar: raw.avatar as string | undefined,
  createdAt: String(raw.created_at ?? raw.createdAt ?? new Date().toISOString()),
  updatedAt: String(raw.updated_at ?? raw.updatedAt ?? new Date().toISOString())
})

export const authApi = {
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    const response = await apiClient.post('/auth/login', credentials)
    const data = response.data
    return {
      token: data.access_token ?? data.token,
      expiresIn: data.expires_in ?? data.expiresIn ?? 3600,
      user: normalizeUser(data.user)
    }
  },

  register: async (userData: RegisterData): Promise<AuthResponse> => {
    const response = await apiClient.post('/auth/register', {
      firstName: userData.firstName,
      lastName: userData.lastName,
      email: userData.email,
      password: userData.password,
      passwordConfirmation: userData.passwordConfirmation,
      role: userData.role
    })
    const data = response.data
    return {
      token: data.access_token ?? data.token,
      expiresIn: data.expires_in ?? data.expiresIn ?? 3600,
      user: normalizeUser(data.user)
    }
  },

  getCurrentUser: async (): Promise<User> => {
    const response = await apiClient.get('/auth/me')
    return normalizeUser(response.data)
  },

  logout: async (): Promise<void> => {
    await apiClient.post('/auth/logout')
  }
}
