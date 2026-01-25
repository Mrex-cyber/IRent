import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { Apartment, ApartmentFilters } from '@/types/apartment'

const mockApartments: Apartment[] = [
  {
    id: '1',
    title: 'Modern 2BR Apartment',
    description: 'Beautiful modern apartment in the heart of the city',
    address: '123 Main Street',
    city: 'New York',
    state: 'NY',
    zipCode: '10001',
    country: 'USA',
    price: 2500,
    bedrooms: 2,
    bathrooms: 1.5,
    squareFeet: 1200,
    amenities: ['WiFi', 'Parking', 'Gym', 'Pool'],
    images: ['https://via.placeholder.com/400x300'],
    isAvailable: true,
    landlordId: '1',
    landlord: {
      id: '1',
      firstName: 'John',
      lastName: 'Doe',
      email: 'john@example.com'
    },
    coordinates: { lat: 40.7128, lng: -74.006 },
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  },
  {
    id: '2',
    title: 'Spacious 3BR Loft',
    description: 'Large loft with high ceilings and modern amenities',
    address: '456 Oak Avenue',
    city: 'Los Angeles',
    state: 'CA',
    zipCode: '90001',
    country: 'USA',
    price: 3500,
    bedrooms: 3,
    bathrooms: 2,
    squareFeet: 1800,
    amenities: ['WiFi', 'Parking', 'Gym', 'Balcony'],
    images: ['https://via.placeholder.com/400x300'],
    isAvailable: true,
    landlordId: '2',
    landlord: {
      id: '2',
      firstName: 'Jane',
      lastName: 'Smith',
      email: 'jane@example.com'
    },
    coordinates: { lat: 34.0522, lng: -118.2437 },
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  },
  {
    id: '3',
    title: 'Cozy 1BR Studio',
    description: 'Perfect for singles or couples, fully furnished',
    address: '789 Pine Street',
    city: 'Chicago',
    state: 'IL',
    zipCode: '60601',
    country: 'USA',
    price: 1500,
    bedrooms: 1,
    bathrooms: 1,
    squareFeet: 800,
    amenities: ['WiFi', 'Furnished'],
    images: ['https://via.placeholder.com/400x300'],
    isAvailable: false,
    landlordId: '1',
    landlord: {
      id: '1',
      firstName: 'John',
      lastName: 'Doe',
      email: 'john@example.com'
    },
    coordinates: { lat: 41.8781, lng: -87.6298 },
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }
]

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
    await new Promise((resolve) => setTimeout(resolve, 300))
    apartments.value = [...mockApartments]
    isLoading.value = false
  }

  const fetchApartment = async (id: string) => {
    isLoading.value = true
    await new Promise((resolve) => setTimeout(resolve, 300))
    const apartment = mockApartments.find((apt) => apt.id === id)
    if (apartment) {
      currentApartment.value = { ...apartment }
    }
    isLoading.value = false
  }

  const createApartment = async (apartmentData: Partial<Apartment>) => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    const newApartment: Apartment = {
      id: String(mockApartments.length + 1),
      title: apartmentData.title || 'New Apartment',
      description: apartmentData.description || '',
      address: apartmentData.address || '',
      city: apartmentData.city || '',
      state: apartmentData.state || '',
      zipCode: apartmentData.zipCode || '',
      country: apartmentData.country || 'USA',
      price: apartmentData.price || 0,
      bedrooms: apartmentData.bedrooms || 1,
      bathrooms: apartmentData.bathrooms || 1,
      squareFeet: apartmentData.squareFeet || 0,
      amenities: apartmentData.amenities || [],
      images: apartmentData.images || [],
      isAvailable: true,
      landlordId: '1',
      landlord: {
        id: '1',
        firstName: 'John',
        lastName: 'Doe',
        email: 'john@example.com'
      },
      coordinates: apartmentData.coordinates || { lat: 0, lng: 0 },
      createdAt: new Date().toISOString(),
      updatedAt: new Date().toISOString()
    }
    apartments.value.push(newApartment)
    return newApartment
  }

  const updateApartment = async (id: string, apartmentData: Partial<Apartment>) => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    const index = apartments.value.findIndex((apt) => apt.id === id)
    if (index !== -1) {
      apartments.value[index] = { ...apartments.value[index], ...apartmentData }
      return apartments.value[index]
    }
    return null
  }

  const deleteApartment = async (id: string) => {
    await new Promise((resolve) => setTimeout(resolve, 300))
    apartments.value = apartments.value.filter((apt) => apt.id !== id)
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
