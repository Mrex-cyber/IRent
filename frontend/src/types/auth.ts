export interface User {
  id: string
  email: string
  firstName: string
  lastName: string
  role: 'Tenant' | 'ApartmentOwner' | 'OSBBRepresentative' | 'Realtor' | 'Admin'
  avatar?: string
  createdAt: string
  updatedAt: string
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  email: string
  password: string
  passwordConfirmation: string
  firstName: string
  lastName: string
  role: 'Tenant' | 'ApartmentOwner' | 'OSBBRepresentative' | 'Realtor' | 'Admin'
}

export interface AuthResponse {
  token: string
  user: User
  expiresIn: number
}
