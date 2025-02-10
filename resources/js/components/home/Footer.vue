<template>
    <footer class="bg-blue-600 text-white py-8">
      <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
        <!-- Footer Links -->
        <ul class="flex space-x-4 text-sm">
          <li><router-link to="/privacy" class="hover:text-blue-300">Privacy Policy</router-link></li>
          <li><router-link to="/terms" class="hover:text-blue-300">Terms of Service</router-link></li>
        </ul>
  
        <!-- Social Media -->
        <div class="mt-4 md:mt-0 flex space-x-4">
          <a href="https://facebook.com" target="_blank" class="hover:text-blue-300">Facebook</a>
          <a href="https://twitter.com" target="_blank" class="hover:text-blue-300">Twitter</a>
          <a href="https://instagram.com" target="_blank" class="hover:text-blue-300">Instagram</a>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: "Footer",
  };
  </script>
  
  <style scoped>
  /* Footer-specific styles */
  </style>
  