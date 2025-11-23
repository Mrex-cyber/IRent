<template>
  <v-container fluid class="pa-0">
    <div class="dashboard-wrapper">
      <TopNavbar />
      <v-container>
        <v-row>
          <v-col cols="12" md="3">
            <v-card>
              <v-card-text>
                <div class="text-h6">Total Apartments</div>
                <div class="text-h4 text-primary">{{ stats.totalApartments }}</div>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card>
              <v-card-text>
                <div class="text-h6">Available</div>
                <div class="text-h4 text-success">{{ stats.availableApartments }}</div>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card>
              <v-card-text>
                <div class="text-h6">Rented</div>
                <div class="text-h4 text-warning">{{ stats.rentedApartments }}</div>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="3">
            <v-card>
              <v-card-text>
                <div class="text-h6">Revenue</div>
                <div class="text-h4 text-info">${{ stats.totalRevenue }}</div>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>

        <v-row class="mt-6">
          <v-col cols="12" md="8">
            <v-card>
              <v-card-title>Recent Apartments</v-card-title>
              <v-card-text>
                <v-list>
                  <v-list-item
                    v-for="apartment in recentApartments"
                    :key="apartment.id"
                    :to="`/apartments/${apartment.id}`"
                  >
                    <v-list-item-title>{{ apartment.title }}</v-list-item-title>
                    <v-list-item-subtitle>{{ apartment.address }}</v-list-item-subtitle>
                    <template v-slot:append>
                      <v-chip :color="apartment.isAvailable ? 'success' : 'warning'">
                        {{ apartment.isAvailable ? 'Available' : 'Rented' }}
                      </v-chip>
                    </template>
                  </v-list-item>
                </v-list>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="4">
            <v-card>
              <v-card-title>Quick Actions</v-card-title>
              <v-card-text>
                <v-btn color="primary" block class="mb-2" to="/apartments">
                  View All Apartments
                </v-btn>
                <v-btn color="secondary" block class="mb-2" to="/apartments/new">
                  Add New Apartment
                </v-btn>
                <v-btn color="info" block to="/chat"> Open Chat </v-btn>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useApartmentsStore } from '@/store/apartments'
import TopNavbar from '@/components/TopNavbar.vue'

const apartmentsStore = useApartmentsStore()

const stats = ref({
  totalApartments: 0,
  availableApartments: 0,
  rentedApartments: 0,
  totalRevenue: 0
})

const recentApartments = ref([])

onMounted(async () => {
  await apartmentsStore.fetchApartments()

  stats.value = {
    totalApartments: apartmentsStore.apartments.length,
    availableApartments: apartmentsStore.apartments.filter((apt) => apt.isAvailable).length,
    rentedApartments: apartmentsStore.apartments.filter((apt) => !apt.isAvailable).length,
    totalRevenue: apartmentsStore.apartments.reduce((sum, apt) => sum + apt.price, 0)
  }

  recentApartments.value = apartmentsStore.apartments.slice(0, 5)
})
</script>

<style scoped>
.dashboard-wrapper {
  max-width: 30rem;
  margin: 0 auto;
  padding: 0;
  background-color: #f5f5f5;
  min-height: 100vh;
}
</style>
