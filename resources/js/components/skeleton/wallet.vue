<template>
  <v-row>
    <v-col cols="12" md="12">
      <v-card class="pa-4" elevation="10">
        <!-- Wallet Balance Skeleton -->
        <div class="py-5 px-5">
          <v-card class="mb-5">
            <v-card-text>
              <div class="d-flex justify-space-between align-center">
                <div>
                  <v-skeleton-loader type="text" width="120" class="mb-2"></v-skeleton-loader>
                  <v-skeleton-loader type="heading" width="200"></v-skeleton-loader>
                </div>
                <div class="d-flex gap-2">
                  <v-skeleton-loader type="button" width="100"></v-skeleton-loader>
                  <v-skeleton-loader type="button" width="100"></v-skeleton-loader>
                </div>
              </div>
            </v-card-text>
          </v-card>

          <!-- Table Skeleton -->
          <v-card>
            <v-card-text>
              <v-skeleton-loader type="table-heading"></v-skeleton-loader>
              <v-skeleton-loader 
                v-for="i in 5" 
                :key="i"
                type="table-row-divider"
              ></v-skeleton-loader>
            </v-card-text>
          </v-card>
        </div>
      </v-card>
    </v-col>
  </v-row>
</template>