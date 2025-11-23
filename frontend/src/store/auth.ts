import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User, LoginCredentials, RegisterData } from '@/types/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(localStorage.getItem('token'))
  const isLoading = ref(false)

  const isAuthenticated = computed(() => !!token.value && !!user.value)

  const login = async (credentials: LoginCredentials) => {
    isLoading.value = true
    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(credentials)
      })

      if (!response.ok) {
        throw new Error('Login failed')
      }

      const data = await response.json()
      token.value = data.token
      user.value = data.user
      localStorage.setItem('token', data.token)
    } catch (error) {
      console.error('Login error:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const register = async (userData: RegisterData) => {
    isLoading.value = true
    try {
      const response = await fetch('/api/auth/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(userData)
      })

      if (!response.ok) {
        throw new Error('Registration failed')
      }

      const data = await response.json()
      token.value = data.token
      user.value = data.user
      localStorage.setItem('token', data.token)
    } catch (error) {
      console.error('Registration error:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const logout = () => {
    user.value = null
    token.value = null
    localStorage.removeItem('token')
  }

  const fetchUser = async () => {
    if (!token.value) return

    try {
      const response = await fetch('/api/auth/me', {
        headers: {
          Authorization: `Bearer ${token.value}`
        }
      })

      if (response.ok) {
        user.value = await response.json()
      }
    } catch (error) {
      console.error('Fetch user error:', error)
      logout()
    }
  }

  return {
    user,
    token,
    isLoading,
    isAuthenticated,
    login,
    register,
    logout,
    fetchUser
  }
})
