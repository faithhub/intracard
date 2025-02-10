<template>
    <v-row>
      <v-col cols="12">
        <v-card class="pa-4" elevation="10">
          <v-card-title>
            <v-skeleton-loader type="heading"></v-skeleton-loader>
          </v-card-title>
  
          <v-card-text>
            <!-- Credit Cards Section -->
            <section class="mb-8">
              <v-skeleton-loader
                v-for="i in 2"
                :key="i"
                type="list-item-avatar-three-line"
                class="mb-4"
              ></v-skeleton-loader>
            </section>
  
            <!-- Transactions Section -->
            <section>
              <v-skeleton-loader
                type="table-heading, table-tbody"
                class="mt-4"
              ></v-skeleton-loader>
            </section>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </template>