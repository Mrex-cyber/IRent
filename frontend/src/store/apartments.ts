import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Apartment, ApartmentFilters } from '@/types/apartment'

export const useApartmentsStore = defineStore('apartments', () => {
  const apartments = ref<Apartment[]>([])
  const currentApartment = ref<Apartment | null>(null)
  const isLoading = ref(false)
  const filters = ref<ApartmentFilters>({
    minPrice: null,
    maxPrice: null,
    bedrooms: null,
    bathrooms: null,
    location: '',
    amenities: []
  })

  const fetchApartments = async () => {
    isLoading.value = true
    try {
      const response = await fetch('/api/apartments')
      if (response.ok) {
        apartments.value = await response.json()
      }
    } catch (error) {
      console.error('Fetch apartments error:', error)
    } finally {
      isLoading.value = false
    }
  }

  const fetchApartment = async (id: string) => {
    isLoading.value = true
    try {
      const response = await fetch(`/api/apartments/${id}`)
      if (response.ok) {
        currentApartment.value = await response.json()
      }
    } catch (error) {
      console.error('Fetch apartment error:', error)
    } finally {
      isLoading.value = false
    }
  }

  const createApartment = async (apartmentData: Partial<Apartment>) => {
    try {
      const response = await fetch('/api/apartments', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(apartmentData)
      })

      if (response.ok) {
        const newApartment = await response.json()
        apartments.value.push(newApartment)
        return newApartment
      }
    } catch (error) {
      console.error('Create apartment error:', error)
      throw error
    }
  }

  const updateApartment = async (id: string, apartmentData: Partial<Apartment>) => {
    try {
      const response = await fetch(`/api/apartments/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(apartmentData)
      })

      if (response.ok) {
        const updatedApartment = await response.json()
        const index = apartments.value.findIndex((apt) => apt.id === id)
        if (index !== -1) {
          apartments.value[index] = updatedApartment
        }
        return updatedApartment
      }
    } catch (error) {
      console.error('Update apartment error:', error)
      throw error
    }
  }

  const deleteApartment = async (id: string) => {
    try {
      const response = await fetch(`/api/apartments/${id}`, {
        method: 'DELETE'
      })

      if (response.ok) {
        apartments.value = apartments.value.filter((apt) => apt.id !== id)
      }
    } catch (error) {
      console.error('Delete apartment error:', error)
      throw error
    }
  }

  const setFilters = (newFilters: Partial<ApartmentFilters>) => {
    filters.value = { ...filters.value, ...newFilters }
  }

  return {
    apartments,
    currentApartment,
    isLoading,
    filters,
    fetchApartments,
    fetchApartment,
    createApartment,
    updateApartment,
    deleteApartment,
    setFilters
  }
})
