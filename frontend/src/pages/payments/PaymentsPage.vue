<template>
  <v-container fluid class="pa-0">
    <div class="payments-wrapper">
      <TopNavbar />
      <v-container>
        <v-row>
          <v-col cols="12" md="4">
            <v-card class="mb-4">
              <v-card-title>Payment Status</v-card-title>
              <v-card-text>
                <div class="text-h4 text-primary mb-2">${{ paymentStatus.totalAmount }}</div>
                <div class="text-body-2">
                  <div class="d-flex justify-space-between">
                    <span>Paid:</span>
                    <span class="text-success">${{ paymentStatus.paidAmount }}</span>
                  </div>
                  <div class="d-flex justify-space-between">
                    <span>Pending:</span>
                    <span class="text-warning">${{ paymentStatus.pendingAmount }}</span>
                  </div>
                  <div class="d-flex justify-space-between">
                    <span>Overdue:</span>
                    <span class="text-error">${{ paymentStatus.overdueAmount }}</span>
                  </div>
                </div>
              </v-card-text>
            </v-card>

            <v-card>
              <v-card-title>Quick Actions</v-card-title>
              <v-card-text>
                <v-btn color="primary" block class="mb-2" @click="makePayment">
                  Make Payment
                </v-btn>
                <v-btn color="secondary" block class="mb-2" @click="downloadReceipt">
                  Download Receipt
                </v-btn>
                <v-btn color="info" block @click="viewHistory"> Payment History </v-btn>
              </v-card-text>
            </v-card>
          </v-col>

          <v-col cols="12" md="8">
            <v-card>
              <v-card-title>Recent Payments</v-card-title>
              <v-card-text>
                <v-list>
                  <v-list-item v-for="payment in recentPayments" :key="payment.id">
                    <template v-slot:prepend>
                      <v-icon :color="getPaymentTypeColor(payment.type)" size="24">
                        {{ getPaymentTypeIcon(payment.type) }}
                      </v-icon>
                    </template>

                    <v-list-item-title>
                      {{ payment.description || payment.type }}
                    </v-list-item-title>
                    <v-list-item-subtitle>
                      {{ payment.apartment.title }} • Due: {{ formatDate(payment.dueDate) }}
                    </v-list-item-subtitle>

                    <template v-slot:append>
                      <div class="text-right">
                        <div class="text-h6">${{ payment.amount }}</div>
                        <v-chip :color="getStatusColor(payment.status)" size="small">
                          {{ payment.status }}
                        </v-chip>
                      </div>
                    </template>
                  </v-list-item>
                </v-list>

                <div v-if="recentPayments.length === 0" class="text-center pa-8">
                  <v-icon size="64" color="grey">mdi-credit-card-outline</v-icon>
                  <h3 class="mt-4">No payments found</h3>
                  <p>Your payment history will appear here.</p>
                </div>
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
import TopNavbar from '@/components/TopNavbar.vue'

const paymentStatus = ref({
  totalAmount: 0,
  paidAmount: 0,
  pendingAmount: 0,
  overdueAmount: 0
})

const recentPayments = ref([])

const getPaymentTypeColor = (type: string) => {
  const colors: Record<string, string> = {
    Rent: 'blue',
    Utilities: 'green',
    Maintenance: 'orange',
    Other: 'grey'
  }
  return colors[type] || 'grey'
}

const getPaymentTypeIcon = (type: string) => {
  const icons: Record<string, string> = {
    Rent: 'mdi-home',
    Utilities: 'mdi-flash',
    Maintenance: 'mdi-wrench',
    Other: 'mdi-credit-card'
  }
  return icons[type] || 'mdi-credit-card'
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    Pending: 'orange',
    Paid: 'green',
    Overdue: 'red',
    Cancelled: 'grey'
  }
  return colors[status] || 'grey'
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString()
}

const makePayment = () => {
  console.log('Make payment')
}

const downloadReceipt = () => {
  console.log('Download receipt')
}

const viewHistory = () => {
  console.log('View history')
}

const fetchPaymentStatus = async () => {
  try {
    const response = await fetch('/api/payments/status')
    if (response.ok) {
      paymentStatus.value = await response.json()
    }
  } catch (error) {
    console.error('Fetch payment status error:', error)
  }
}

const fetchRecentPayments = async () => {
  try {
    const response = await fetch('/api/payments/recent')
    if (response.ok) {
      recentPayments.value = await response.json()
    }
  } catch (error) {
    console.error('Fetch recent payments error:', error)
  }
}

onMounted(() => {
  fetchPaymentStatus()
  fetchRecentPayments()
})
</script>

<style scoped>
.payments-wrapper {
  max-width: 30rem;
  margin: 0 auto;
  padding: 0;
  background-color: #f5f5f5;
  min-height: 100vh;
}
</style>
