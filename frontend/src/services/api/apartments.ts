import apiClient from './client'
import type { Apartment, ApartmentFilters, CreateApartmentData } from '@/types/apartment'

export const apartmentsApi = {
  getAll: async (filters?: ApartmentFilters): Promise<Apartment[]> => {
    const response = await apiClient.get('/apartments', { params: filters })
    return response.data
  },

  getById: async (id: string): Promise<Apartment> => {
    const response = await apiClient.get(`/apartments/${id}`)
    return response.data
  },

  create: async (data: CreateApartmentData): Promise<Apartment> => {
    const response = await apiClient.post('/apartments', data)
    return response.data
  },

  update: async (id: string, data: Partial<CreateApartmentData>): Promise<Apartment> => {
    const response = await apiClient.put(`/apartments/${id}`, data)
    return response.data
  },

  delete: async (id: string): Promise<void> => {
    await apiClient.delete(`/apartments/${id}`)
  },

  uploadImage: async (apartmentId: string, file: File): Promise<{ url: string }> => {
    const formData = new FormData()
    formData.append('file', file)

    const response = await apiClient.post(`/apartments/${apartmentId}/images`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    return response.data
  }
}
