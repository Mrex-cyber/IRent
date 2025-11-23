export interface Apartment {
  id: string
  title: string
  description: string
  address: string
  city: string
  state: string
  zipCode: string
  country: string
  price: number
  bedrooms: number
  bathrooms: number
  squareFeet: number
  amenities: string[]
  images: string[]
  isAvailable: boolean
  landlordId: string
  landlord: {
    id: string
    firstName: string
    lastName: string
    email: string
  }
  coordinates: {
    lat: number
    lng: number
  }
  createdAt: string
  updatedAt: string
}

export interface ApartmentFilters {
  minPrice?: number | null
  maxPrice?: number | null
  bedrooms?: number | null
  bathrooms?: number | null
  location?: string
  amenities?: string[]
}

export interface CreateApartmentData {
  title: string
  description: string
  address: string
  city: string
  state: string
  zipCode: string
  country: string
  price: number
  bedrooms: number
  bathrooms: number
  squareFeet: number
  amenities: string[]
  coordinates: {
    lat: number
    lng: number
  }
}
