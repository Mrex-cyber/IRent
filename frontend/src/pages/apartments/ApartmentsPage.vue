<template>
  <v-container fluid class="pa-0">
    <div class="apartments-wrapper">
      <TopNavbar />
      <v-container>
        <v-row>
          <v-col cols="12">
            <div class="page-header">
              <h1 class="page-title">Apartments</h1>
              <div class="header-actions">
                <button class="filter-button" @click="showFilters = true">
                  <svg
                    width="20"
                    height="20"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                  </svg>
                </button>
              </div>
            </div>
          </v-col>

          <v-col cols="12">
            <v-row>
              <v-col
                v-for="apartment in filteredApartments"
                :key="apartment.id"
                cols="12"
                sm="6"
                md="4"
              >
                <v-card :to="`/apartments/${apartment.id}`" hover>
                  <v-img
                    :src="apartment.images[0] || '/placeholder-apartment.jpg'"
                    height="200"
                    cover
                  ></v-img>

                  <v-card-title>{{ apartment.title }}</v-card-title>
                  <v-card-subtitle>{{ apartment.address }}</v-card-subtitle>

                  <v-card-text>
                    <div class="text-h6 text-primary">${{ apartment.price }}/month</div>
                    <div class="text-body-2">
                      {{ apartment.bedrooms }} bed • {{ apartment.bathrooms }} bath •
                      {{ apartment.squareFeet }} sq ft
                    </div>
                  </v-card-text>

                  <v-card-actions>
                    <v-chip :color="apartment.isAvailable ? 'success' : 'warning'" size="small">
                      {{ apartment.isAvailable ? 'Available' : 'Rented' }}
                    </v-chip>
                    <v-spacer></v-spacer>
                    <v-btn icon="mdi-heart-outline" variant="text"></v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>
            </v-row>

            <v-row v-if="filteredApartments.length === 0">
              <v-col cols="12" class="text-center">
                <v-icon size="64" color="grey">mdi-home-search</v-icon>
                <h3 class="mt-4">No apartments found</h3>
                <p>Try adjusting your filters or check back later.</p>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-container>

      <v-navigation-drawer v-model="showFilters" temporary location="top" class="filters-drawer">
        <div class="drawer-header">
          <h2>Filters</h2>
          <button class="close-button" @click="showFilters = false">
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        </div>
        <div class="drawer-content">
          <v-text-field
            v-model="filters.location"
            label="Location"
            prepend-inner-icon="mdi-map-marker"
            class="mb-4"
          ></v-text-field>

          <v-text-field
            v-model.number="filters.minPrice"
            label="Min Price"
            type="number"
            prepend-inner-icon="mdi-currency-usd"
            class="mb-4"
          ></v-text-field>

          <v-text-field
            v-model.number="filters.maxPrice"
            label="Max Price"
            type="number"
            prepend-inner-icon="mdi-currency-usd"
            class="mb-4"
          ></v-text-field>

          <v-select
            v-model="filters.bedrooms"
            label="Bedrooms"
            :items="bedroomOptions"
            clearable
            class="mb-4"
          ></v-select>

          <v-select
            v-model="filters.bathrooms"
            label="Bathrooms"
            :items="bathroomOptions"
            clearable
            class="mb-4"
          ></v-select>

          <v-btn color="primary" block @click="applyFilters"> Apply Filters </v-btn>
        </div>
      </v-navigation-drawer>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useApartmentsStore } from '@/store/apartments'
import TopNavbar from '@/components/TopNavbar.vue'

const apartmentsStore = useApartmentsStore()
const showFilters = ref(false)

const filters = ref({
  location: '',
  minPrice: null as number | null,
  maxPrice: null as number | null,
  bedrooms: null as number | null,
  bathrooms: null as number | null
})

const bedroomOptions = [1, 2, 3, 4, 5, 6]
const bathroomOptions = [1, 1.5, 2, 2.5, 3, 3.5, 4]

const filteredApartments = computed(() => {
  return apartmentsStore.apartments.filter((apartment) => {
    if (
      filters.value.location &&
      !apartment.address.toLowerCase().includes(filters.value.location.toLowerCase())
    ) {
      return false
    }
    if (filters.value.minPrice && apartment.price < filters.value.minPrice) {
      return false
    }
    if (filters.value.maxPrice && apartment.price > filters.value.maxPrice) {
      return false
    }
    if (filters.value.bedrooms && apartment.bedrooms !== filters.value.bedrooms) {
      return false
    }
    if (filters.value.bathrooms && apartment.bathrooms !== filters.value.bathrooms) {
      return false
    }
    return true
  })
})

const applyFilters = () => {
  apartmentsStore.setFilters(filters.value)
}

onMounted(async () => {
  await apartmentsStore.fetchApartments()
})
</script>

<style scoped>
.apartments-wrapper {
  max-width: 30rem;
  margin: 0 auto;
  padding: 0;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  padding: 0 1rem;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  text-transform: uppercase;
  letter-spacing: 0.0625rem;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.filter-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  border: 0.09375rem solid #1976d2;
  background: #e3f2fd;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #1976d2;
  padding: 0;
  transition: all 0.2s;
}

.filter-button:hover {
  background: #bbdefb;
}

:deep(.filters-drawer) {
  width: calc(100% - 2rem) !important;
  max-height: 50vh;
  left: 1rem !important;
  right: 1rem !important;
  top: 0 !important;
  border-radius: 0 0 1rem 1rem;
}

.drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.drawer-header h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #000;
  margin: 0;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.close-button {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  border: none;
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #666;
  padding: 0;
  transition: all 0.2s;
}

.close-button:hover {
  background-color: #f5f5f5;
}

.drawer-content {
  padding: 1.5rem;
}
</style>
