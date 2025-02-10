<template>
    <header class="bg-blue-600 text-white py-4 shadow-md">
      <nav class="container mx-auto flex justify-between items-center px-4">
        <!-- Logo -->
        <router-link to="/" class="text-2xl font-bold">
          <img src="@assets/logos/intracard.png" alt="Logo" class="h-8">
        </router-link>
  
        <!-- Navigation Links -->
        <ul class="hidden md:flex space-x-6 text-sm">
          <li><router-link to="/features" class="hover:text-blue-300">Features</router-link></li>
          <li><router-link to="/savings" class="hover:text-blue-300">Savings</router-link></li>
          <li><router-link to="/about" class="hover:text-blue-300">About</router-link></li>
          <li><router-link to="/login" class="hover:text-blue-300">Login</router-link></li>
        </ul>
  
        <!-- CTA Button -->
        <router-link
          to="/signup"
          class="bg-white text-blue-600 px-4 py-2 rounded-md shadow hover:bg-blue-100"
        >
          Get Started
        </router-link>
      </nav>
    </header>
  </template>
  
  <script>
  export default {
    name: "Navbar",
  };
  </script>
  
  <style scoped>
  /* Add any specific styles here */
  </style>
  