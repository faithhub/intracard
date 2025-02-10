<template>
    <section class="bg-blue-50 py-16">
      <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6">
        <!-- Left Section: Text & Buttons -->
        <div class="text-center md:text-left md:w-1/2">
          <a
            href="/reports/2024"
            class="inline-flex items-center mb-4 bg-yellow-400 px-4 py-2 rounded-lg text-sm font-semibold text-blue-900"
          >
            <img
              src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/google_icon_9867fc9be6/google_icon_9867fc9be6.svg"
              alt="Report Icon"
              class="w-6 h-6 mr-2"
            />
            The 2024 Intracard Savings Report
          </a>
          <h1 class="text-4xl md:text-5xl font-bold text-blue-600 leading-tight">
            The Better Way to Save & Invest
          </h1>
          <p class="mt-4 text-lg text-gray-600">
            Intracard helps over 5 million customers achieve their financial goals by helping them save and invest with ease.
          </p>
          <div class="mt-6 flex justify-center md:justify-start space-x-4">
            <a
              href="#"
              class="inline-flex items-center bg-black text-white py-3 px-6 rounded-lg shadow-md hover:bg-gray-800"
            >
              <img
                src="https://storage.googleapis.com/new-abeg-avatar-dev/piggyvest-cms-staging/white_Apple_Logo_768cf7ce21/white_Apple_Logo_768cf7ce21.svg"
                alt="Apple Logo"
                class="w-5 h-5 mr-2"
              />
              Get on iPhone
            </a>
            <a
              href="#"
              class="inline-flex items-center bg-black text-white py-3 px-6 rounded-lg shadow-md hover:bg-gray-800"
            >
              <img
                src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/google_icon_9867fc9be6/google_icon_9867fc9be6.svg"
                alt="Google Play Logo"
                class="w-5 h-5 mr-2"
              />
              Get on Android
            </a>
          </div>
        </div>
  
        <!-- Right Section: Image -->
        <div class="mt-12 md:mt-0 md:w-1/2 relative">
          <img
            src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/pexels_ketut_subiyanto_4350099_bc5e069ebe/pexels_ketut_subiyanto_4350099_bc5e069ebe.avif"
            alt="Woman Smiling"
            class="rounded-lg shadow-lg w-full"
          />
          <div class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2">
            <img
              src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/Frame8011_00d37f1517/Frame8011_00d37f1517.png"
              alt="Savings Card"
              class="w-32"
            />
          </div>
          <div class="absolute bottom-0 left-0 transform -translate-x-1/2 translate-y-1/2">
            <img
              src="https://storage.googleapis.com/piggyvestwebsite/piggywebsite2020/housemoney_132c9f5043/housemoney_132c9f5043.svg"
              alt="House Money Card"
              class="w-32"
            />
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script>
  export default {
    name: "Hero",
  };
  </script>
  
  <style scoped>
  /* Add specific styles for the hero section if needed */
  </style>
  