<template>
  <v-app>
    <v-main>
      <v-container fluid class="fill-height">
        <v-row align="center" justify="center">
          <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
              <v-toolbar dark color="primary">
                <button class="back-button" @click="$router.push('/menu')">
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
                    <polyline points="15 18 9 12 15 6" />
                  </svg>
                </button>
                <v-toolbar-title>IRent</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <router-view />
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
  </v-app>
</template>

<script setup lang="ts"></script>

<style scoped>
.back-button {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  border: 0.09375rem solid rgba(255, 255, 255, 0.5);
  background: transparent;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: #ffffff;
  padding: 0;
  transition: all 0.2s;
  margin-right: 1rem;
}

.back-button:hover {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.8);
}
</style>
