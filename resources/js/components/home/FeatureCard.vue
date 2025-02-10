<template>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <div class="mb-4 text-4xl">{{ icon }}</div>
      <h3 class="text-xl font-bold mb-2">{{ title }}</h3>
      <p class="text-gray-600">{{ description }}</p>
    </div>
  </template>
  
  <script>
  import { defineComponent } from 'vue';
  
  export default defineComponent({
    name: 'FeatureCard',
    props: {
      icon: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      description: {
        type: String,
        required: true
      }
    }
  });
  </script>