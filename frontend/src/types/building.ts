export interface Building {
  id: number
  name: string
}

export interface Entrance {
  id: number
  name: string
  buildingId: number
  apartmentCount: number
}

export interface Apartment {
  id: number
  number: string
  type: string
  size: number
  bedrooms: number
  bathrooms: number
  status: string
  floor: number
  owner?: string
  tenant?: string
  amenities?: string[]
  entranceId: number
  pendingCount?: number
  ownerName?: string
  ownerPhone?: string
  ownerEmail?: string
  requestsTotal?: number
  requestsPending?: number
  requestsResolved?: number
  waterMeter?: string
  electricityMeter?: string
  gasMeter?: string
  billingAmount?: number
  dueDate?: string
}
