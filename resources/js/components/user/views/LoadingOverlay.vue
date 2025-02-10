<template>
    <v-overlay
      :model-value="loading"
      class="align-center justify-center"
      persistent
    >
      <v-card
        color="white"
        width="300"
        class="pa-4 rounded-lg"
      >
        <div class="text-center">
          <v-progress-circular
            color="purple-darken-1"
            indeterminate
            size="64"
          ></v-progress-circular>
          <div class="mt-4 text-h6 font-weight-medium text-purple-darken-1">
            Loading your details...
          </div>
        </div>
      </v-card>
    </v-overlay>
  </template>
  
  <script>
  export default {
    props: {
      loading: {
        type: Boolean,
        default: false
      }
    }
  }
  </script>