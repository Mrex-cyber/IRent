<template>
  <v-card :to="`/apartments/${apartment.id}`" hover class="apartment-card">
    <v-img :src="apartment.images[0] || '/placeholder-apartment.jpg'" height="200" cover>
      <template v-slot:placeholder>
        <v-row class="fill-height ma-0" align="center" justify="center">
          <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
        </v-row>
      </template>
    </v-img>

    <v-card-title class="text-h6">{{ apartment.title }}</v-card-title>
    <v-card-subtitle>{{ apartment.address }}</v-card-subtitle>

    <v-card-text>
      <div class="text-h6 text-primary mb-2">${{ apartment.price }}/month</div>
      <div class="text-body-2 text-grey">
        {{ apartment.bedrooms }} bed • {{ apartment.bathrooms }} bath •
        {{ apartment.squareFeet }} sq ft
      </div>
      <div class="text-body-2 text-grey mt-1">{{ apartment.city }}, {{ apartment.state }}</div>
    </v-card-text>

    <v-card-actions>
      <v-chip :color="apartment.isAvailable ? 'success' : 'warning'" size="small">
        {{ apartment.isAvailable ? 'Available' : 'Rented' }}
      </v-chip>
      <v-spacer></v-spacer>
      <v-btn icon="mdi-heart-outline" variant="text" @click.stop="toggleFavorite"></v-btn>
      <v-btn icon="mdi-share-variant" variant="text" @click.stop="shareApartment"></v-btn>
    </v-card-actions>
  </v-card>
</template>

<script setup lang="ts">
import type { Apartment } from '@/types/apartment'

interface Props {
  apartment: Apartment
}

defineProps<Props>()

const toggleFavorite = () => {
  console.log('Toggle favorite')
}

const shareApartment = () => {
  console.log('Share apartment')
}
</script>

<style scoped>
.apartment-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

.apartment-card .v-card-text {
  flex-grow: 1;
}
</style>
