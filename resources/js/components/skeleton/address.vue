<!-- @/components/skeleton/address.vue -->
<template>
  <v-row>
    <v-col cols="12">
      <v-card class="pa-4" elevation="10">
        <!-- Header Skeleton -->
        <v-card-title class="d-flex justify-space-between">
          <v-skeleton-loader type="heading"></v-skeleton-loader>
          <v-skeleton-loader type="button"></v-skeleton-loader>
        </v-card-title>

        <v-card-text>
          <v-row>
            <!-- Personal & Account Info Skeletons -->
            <v-col cols="12" md="6" v-for="i in 2" :key="i">
              <v-card elevation="2">
                <v-card-title class="bg-purple-lighten-5">
                  <v-skeleton-loader type="heading"></v-skeleton-loader>
                </v-card-title>
                <v-card-text>
                  <v-skeleton-loader 
                    v-for="j in 3" 
                    :key="j"
                    type="list-item-avatar"
                    class="mb-2"
                  ></v-skeleton-loader>
                </v-card-text>
              </v-card>
            </v-col>

            <!-- Credit & Address Info Skeletons -->
            <v-col cols="12" md="6" v-for="i in 2" :key="`extra-${i}`">
              <v-card elevation="2">
                <v-card-title class="bg-purple-lighten-5">
                  <v-skeleton-loader type="heading"></v-skeleton-loader>
                </v-card-title>
                <v-card-text>
                  <v-skeleton-loader 
                    v-for="j in 3" 
                    :key="j"
                    type="list-item"
                  ></v-skeleton-loader>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>