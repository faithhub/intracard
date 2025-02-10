<!-- @/components/skeleton/dashboard.vue -->
<template>
    <v-row>
      <!-- Welcome Text Skeleton -->
      <v-col cols="12" class="d-flex justify-space-between align-center mb-4">
        <v-skeleton-loader type="text" width="200"></v-skeleton-loader>
        <v-skeleton-loader type="button"></v-skeleton-loader>
      </v-col>
  
      <v-col cols="12">
        <v-row>
          <!-- Calendar Card Skeleton -->
          <v-col cols="12" md="8">
            <v-card class="pa-4" elevation="10">
              <v-skeleton-loader type="heading" class="mb-4"></v-skeleton-loader>
              <!-- Legend Skeleton -->
              <div class="d-flex mb-4">
                <v-skeleton-loader 
                  v-for="i in 4" 
                  :key="i" 
                  type="chip"
                  class="me-2"
                ></v-skeleton-loader>
              </div>
              <!-- Calendar Grid Skeleton -->
              <v-skeleton-loader type="table-heading"></v-skeleton-loader>
              <v-skeleton-loader 
                v-for="i in 5" 
                :key="i"
                type="table-row"
              ></v-skeleton-loader>
            </v-card>
          </v-col>
  
          <!-- Side Cards Skeleton -->
          <v-col cols="12" md="4">
            <!-- Mortgage Amount Card Skeleton -->
            <v-card class="pa-4 mb-4" elevation="10">
              <v-skeleton-loader type="heading"></v-skeleton-loader>
              <v-skeleton-loader type="text" class="mb-2"></v-skeleton-loader>
              <v-skeleton-loader type="paragraph" class="mb-2"></v-skeleton-loader>
            </v-card>
  
            <!-- Credit Score Card Skeleton -->
            <v-card class="pa-4" elevation="10">
              <div class="text-center">
                <v-skeleton-loader
                  type="image"
                  height="200"
                ></v-skeleton-loader>
              </div>
            </v-card>
          </v-col>
        </v-row>
      </v-col>
    </v-row>
  </template>