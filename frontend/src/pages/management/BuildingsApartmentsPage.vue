<template>
  <v-container fluid class="pa-0">
    <div class="buildings-apartments-page">
      <div class="page-container">
        <div class="header-section">
          <div class="header-content">
            <h1 class="page-title">Buildings & Apartments</h1>
            <p class="page-subtitle">Manage building structure and apartment information</p>
          </div>
        </div>

        <div class="building-tabs">
          <button
            v-for="building in buildings"
            :key="building.id"
            :class="['building-tab', { active: selectedBuilding?.id === building.id }]"
            @click="selectBuilding(building)"
          >
            {{ building.name }}
          </button>
        </div>

        <div class="panels-layout">
          <div class="panel left-panel">
            <h3 class="panel-title">Entrances</h3>
            <div class="entrances-list">
              <div
                v-for="entrance in entrances"
                :key="entrance.id"
                :class="['entrance-item', { active: selectedEntrance?.id === entrance.id }]"
                @click="selectEntrance(entrance)"
              >
                <span class="entrance-name">{{ entrance.name }}</span>
                <div class="entrance-info">
                  <span class="entrance-count">{{ entrance.apartmentCount }}</span>
                  <svg
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <polyline points="9 18 15 12 9 6" />
                  </svg>
                </div>
              </div>
              <div v-if="entrances.length === 0" class="empty-state">
                <p>No entrances found</p>
              </div>
            </div>
          </div>

          <div class="panel middle-panel">
            <h3 class="panel-title">
              {{
                selectedEntrance ? `${selectedEntrance.name} - Apartments` : 'Select an entrance'
              }}
            </h3>
            <div v-if="selectedEntrance" class="apartments-grid">
              <div
                v-for="apartment in apartments"
                :key="apartment.id"
                :class="['apartment-card', { active: selectedApartment?.id === apartment.id }]"
                @click="selectApartment(apartment)"
              >
                <div class="apartment-card-header">
                  <div class="apartment-number-large">{{ apartment.number }}</div>
                  <div class="apartment-floor">Floor {{ apartment.floor }}</div>
                </div>
                <div v-if="apartment.pendingCount > 0" class="apartment-pending">
                  {{ apartment.pendingCount }} pending
                </div>
              </div>
              <div v-if="apartments.length === 0" class="empty-state">
                <p>No apartments found</p>
              </div>
            </div>
            <div v-else class="empty-state">
              <p>Select an entrance to view apartments</p>
            </div>
          </div>

          <div class="panel right-panel">
            <div v-if="selectedApartment" class="apartment-details-view">
              <div class="info-section-card apartment-header-card">
                <div class="apartment-header">
                  <h3 class="apartment-title">Apartment {{ selectedApartment.number }}</h3>
                  <span class="apartment-floor-badge">Floor {{ selectedApartment.floor }}</span>
                </div>
              </div>

              <div class="info-section-card">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  class="info-section-avatar"
                >
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                  <circle cx="12" cy="7" r="4" />
                </svg>
                <div class="info-section-right">
                  <div class="info-section-header">
                    <h4 class="info-section-title">Owner</h4>
                  </div>
                  <div class="info-section-content">
                    <div class="owner-name">{{ selectedApartment.ownerName || 'John Smith' }}</div>
                    <div class="owner-contact">
                      <span>{{ selectedApartment.ownerPhone || '+38 099 999 99 99' }}</span>
                    </div>
                    <div class="owner-contact">
                      <span>{{ selectedApartment.ownerEmail || 'owner@example.com' }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="info-section-card">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  class="info-section-avatar"
                >
                  <path
                    d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"
                  />
                </svg>
                <div class="info-section-right">
                  <div class="info-section-header">
                    <h4 class="info-section-title">Requests</h4>
                  </div>
                  <div class="requests-stats">
                    <div class="stat-box total">
                      <div class="stat-value">{{ selectedApartment.requestsTotal || 12 }}</div>
                      <div class="stat-label">Total</div>
                    </div>
                    <div class="stat-box pending">
                      <div class="stat-value">{{ selectedApartment.requestsPending || 3 }}</div>
                      <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-box resolved">
                      <div class="stat-value">{{ selectedApartment.requestsResolved || 1 }}</div>
                      <div class="stat-label">Resolved</div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="info-section-card">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  class="info-section-avatar"
                >
                  <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                </svg>
                <div class="info-section-right">
                  <div class="info-section-header">
                    <h4 class="info-section-title">Utility Meters</h4>
                  </div>
                  <div class="utility-meters">
                    <div class="utility-item">
                      <span class="utility-label">Water:</span>
                      <span class="utility-value"
                        >{{ selectedApartment.waterMeter || '2.3' }}m³</span
                      >
                    </div>
                    <div class="utility-item">
                      <span class="utility-label">Electricity:</span>
                      <span class="utility-value"
                        >{{ selectedApartment.electricityMeter || '224.4' }} kWh</span
                      >
                    </div>
                    <div class="utility-item">
                      <span class="utility-label">Gas:</span>
                      <span class="utility-value">{{ selectedApartment.gasMeter || '7.5' }}m³</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="info-section-card">
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  class="info-section-avatar"
                >
                  <line x1="12" y1="1" x2="12" y2="23" />
                  <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                <div class="info-section-right">
                  <div class="info-section-header">
                    <h4 class="info-section-title">Billing Status</h4>
                  </div>
                  <div class="billing-content">
                    <div class="billing-details">
                      <div class="billing-item">
                        <span class="billing-label">Amount:</span>
                        <span class="billing-value"
                          >${{ selectedApartment.billingAmount || '101' }}</span
                        >
                      </div>
                      <div class="billing-item">
                        <span class="billing-label">Due Date:</span>
                        <span class="billing-value">{{
                          selectedApartment.dueDate || '2024-12-01'
                        }}</span>
                      </div>
                    </div>
                  </div>
                  <span class="paid-tag">Paid</span>
                </div>
              </div>
            </div>
            <div v-else class="empty-state">
              <p>Select an apartment to view details</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </v-container>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import type { Building, Entrance, Apartment } from '@/types/building'

const buildings = ref<Building[]>([])
const selectedBuilding = ref<Building | null>(null)
const entrances = ref<Entrance[]>([])
const selectedEntrance = ref<Entrance | null>(null)
const apartments = ref<Apartment[]>([])
const selectedApartment = ref<Apartment | null>(null)

const selectBuilding = (building: Building) => {
  selectedBuilding.value = building
  selectedEntrance.value = null
  selectedApartment.value = null
  fetchEntrances(building.id)
}

const selectEntrance = (entrance: Entrance) => {
  selectedEntrance.value = entrance
  selectedApartment.value = null
  fetchApartments(entrance.id)
}

const selectApartment = (apartment: Apartment) => {
  selectedApartment.value = apartment
}

const fetchBuildings = async () => {
  buildings.value = []
}

const fetchEntrances = async (_buildingId: number) => {
  entrances.value = []
}

const fetchApartments = async (_entranceId: number) => {
  apartments.value = []
}

const editApartment = () => {
  console.log('Edit apartment', selectedApartment.value)
}

const viewHistory = () => {
  console.log('View history', selectedApartment.value)
}

onMounted(() => {
  fetchBuildings()
})
</script>

<style scoped>
.buildings-apartments-page {
  min-height: 100vh;
  background-color: #f5f5f5;
  padding: 2rem 1rem;
}

.page-container {
  max-width: 90rem;
  margin: 0 auto;
}

.header-section {
  margin-bottom: 2rem;
}

.page-title {
  font-size: 2rem;
  font-weight: 500;
  color: #212121;
  margin: 0 0 0.5rem 0;
}

.page-subtitle {
  font-size: 1rem;
  color: #757575;
  margin: 0;
}

.building-tabs {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
}

.building-tab {
  padding: 0.75rem 1.5rem;
  border: 0.125rem solid var(--color-brand);
  background-color: #ffffff;
  color: var(--color-brand);
  border-radius: 20px;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.building-tab:hover {
  background-color: #e3f2fd;
}

.building-tab.active {
  background-color: var(--color-brand);
  color: #ffffff;
}

.panels-layout {
  display: grid;
  grid-template-columns: 1fr 1fr 1.5fr;
  gap: 1.5rem;
}

.panel {
  background-color: #ffffff;
  border-radius: 0.75rem;
  padding: 1.5rem;
  box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
  min-height: 30rem;
  display: flex;
  flex-direction: column;
}

.right-panel {
  background-color: #f5f5f5;
  padding: 0;
}

.panel-title {
  font-size: 1.125rem;
  font-weight: 700;
  color: #212121;
  margin: 0 0 1rem 0;
}

.entrances-list {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  overflow-y: auto;
}

.apartments-grid {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem 1rem;
  row-gap: 0.75rem;
  overflow-y: auto;
}

.entrance-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  background-color: #ffffff;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.2s;
  border: 0.0625rem solid transparent;
}

.entrance-item:hover {
  background-color: #f5f5f5;
}

.entrance-item.active {
  background: linear-gradient(
    to bottom,
    #ffffff 0%,
    rgba(185, 208, 255, 0.925) 50%,
    rgba(62, 126, 255, 0.8) 100%
  );
  border-color: transparent;
}

.apartment-card {
  background-color: #ffffff;
  border: 0.125rem solid #e0e0e0;
  border-radius: 0.5rem;
  padding: 1rem;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  height: fit-content;
}

.apartment-card:hover {
  border-color: var(--color-brand);
}

.apartment-card.active {
  border-color: var(--color-brand);
  background-color: #e3f2fd;
}

.apartment-card-header {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.apartment-number-large {
  font-size: 1.125rem;
  font-weight: 600;
  color: #212121;
}

.apartment-floor {
  font-size: 0.875rem;
  color: #757575;
}

.apartment-pending {
  padding: 0.25rem 0.75rem;
  border: 0.125rem solid #f44336;
  border-radius: 0.25rem;
  background-color: #ffffff;
  color: #f44336;
  font-size: 0.75rem;
  font-weight: 500;
  width: fit-content;
}

.entrance-name {
  font-size: 1rem;
  font-weight: 500;
  color: #212121;
}

.entrance-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #757575;
}

.entrance-count {
  font-size: 0.875rem;
}

.empty-state {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9e9e9e;
}

.apartment-details-view {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  overflow-y: auto;
  padding: 1rem;
}

.apartment-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.apartment-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #212121;
  margin: 0;
}

.apartment-floor-badge {
  padding: 0.25rem 0.75rem;
  background-color: #e3f2fd;
  color: #1565c0;
  border-radius: 1rem;
  font-size: 0.875rem;
  font-weight: 500;
}

.info-section-card {
  background-color: #ffffff;
  border-radius: 0.5rem;
  padding: 1.25rem;
  display: flex;
  flex-direction: row;
  gap: 1rem;
  box-shadow: 0 0.0625rem 0.1875rem rgba(0, 0, 0, 0.1);
}

.apartment-header-card {
  padding: 1.25rem;
}

.info-section {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-section-avatar {
  color: #757575;
  flex-shrink: 0;
  height: 1.5rem;
  width: 1.5rem;
  margin-top: 0;
}

.info-section-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-section-header {
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
}

.info-section-title {
  font-size: 1rem;
  font-weight: 600;
  color: #212121;
  margin: 0;
  line-height: 1.5rem;
}

.info-section-content {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.owner-name {
  font-size: 0.9375rem;
  font-weight: 500;
  color: #212121;
}

.owner-contact {
  font-size: 0.875rem;
  color: #757575;
}

.requests-stats {
  display: flex;
  gap: 0.75rem;
}

.stat-box {
  flex: 1;
  padding: 0.75rem;
  border-radius: 0.5rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.25rem;
}

.stat-box.total {
  background-color: #e0e0e0;
}

.stat-box.pending {
  background-color: #fff9c4;
}

.stat-box.resolved {
  background-color: #c8e6c9;
}

.stat-value {
  font-size: 1.125rem;
  font-weight: 700;
  color: #212121;
}

.stat-label {
  font-size: 0.75rem;
  color: #757575;
}

.utility-meters {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.utility-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.utility-label {
  font-size: 0.875rem;
  color: #757575;
}

.utility-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.billing-content {
  margin-bottom: 1rem;
}

.billing-details {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.billing-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.billing-label {
  font-size: 0.875rem;
  color: #757575;
}

.billing-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.paid-tag {
  padding: 0.5rem 1rem;
  background-color: #dcfce7;
  color: #166534;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  display: inline-block;
  width: fit-content;
  align-self: flex-start;
}

.detail-section {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 0.0625rem solid #e0e0e0;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  font-size: 0.875rem;
  color: #757575;
}

.detail-value {
  font-size: 0.875rem;
  font-weight: 500;
  color: #212121;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
}

.status-badge.occupied {
  background-color: #c8e6c9;
  color: #2e7d32;
}

.status-badge.available {
  background-color: #bbdefb;
  color: #1565c0;
}

.status-badge.maintenance {
  background-color: #ffe0b2;
  color: #e65100;
}

.section-subtitle {
  font-size: 1rem;
  font-weight: 600;
  color: #212121;
  margin: 0 0 0.75rem 0;
}

.amenities-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.amenity-tag {
  padding: 0.5rem 1rem;
  background-color: #e3f2fd;
  color: #1565c0;
  border-radius: 1rem;
  font-size: 0.875rem;
}

.actions-section {
  display: flex;
  gap: 1rem;
  padding-top: 1rem;
  border-top: 0.0625rem solid #e0e0e0;
}

.action-button {
  flex: 1;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.action-button.primary {
  background-color: var(--color-brand);
  color: #ffffff;
}

.action-button.primary:hover {
  background-color: #1976d2;
}

.action-button.secondary {
  background-color: #e0e0e0;
  color: #212121;
}

.action-button.secondary:hover {
  background-color: #bdbdbd;
}

@media (max-width: 75rem) {
  .panels-layout {
    grid-template-columns: 1fr;
  }

  .panel {
    min-height: auto;
  }
}
</style>
