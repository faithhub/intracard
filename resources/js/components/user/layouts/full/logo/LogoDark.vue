<template>
    <div class="logo-container">
      <!-- Logo Text -->
      <h1 class="logo-text">
        <span class="primary">INTRA</span>
        <span class="secondary">CARD</span>
      </h1>
      <!-- Tagline -->
      <p class="tagline">Empowering your financial journey</p>
    </div>
  </template>
  
  <style scoped>
  /* Import the exact font */
 /* Import Nasalization Rg font */
/* @font-face {
  font-family: 'Nasalization';
  src: url('/path/to/nasalization-rg.ttf') format('truetype');
  font-weight: normal;
  font-style: normal;
} */
  /* Logo Container */
  .logo-container {
    text-align: center;
    background-color: transparent; /* Optional: Light background for contrast */
    padding: 20px 0;
    border-radius: 8px; /* Optional: Add some rounding */
  }
  
  /* Logo Text */
  .logo-text {
    font-family: 'Nasalization', sans-serif; /* Apply Nasalization font */
    font-size: 36px; /* Adjust size as needed */
    font-weight: 600; /* Bold weight for emphasis */
    margin: 0;
    line-height: 0.5; /* Adjust spacing between lines */
  }
  
  .primary {
    color: #210035; /* Dark purple */
  }
  
  .secondary {
    color: #48d2bc; /* Mint green */
  }
  
  /* Tagline */
  .tagline {
    font-family: 'Nasalization', sans-serif; /* Apply Nasalization font */
    font-size: 10px;
    color: #210035; /* Use the same dark purple */
    margin-top: 8px;
    font-weight: 400; /* Regular weight for subtle appearance */
  }
  </style>
  