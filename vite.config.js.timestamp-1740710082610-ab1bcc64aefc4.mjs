// vite.config.js
import { defineConfig } from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/vite/dist/node/index.js";
import vue from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import laravel from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/laravel-vite-plugin/dist/index.js";
import path from "path";
import vuetify from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/vite-plugin-vuetify/dist/index.mjs";
import compression from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/vite-plugin-compression/dist/index.mjs";
import commonjs from "file:///C:/Users/Faith%20Oluwadara%20Amao/Documents/intracard/node_modules/@rollup/plugin-commonjs/dist/es/index.js";
var __vite_injected_original_dirname = "C:\\Users\\Faith Oluwadara Amao\\Documents\\intracard";
var vite_config_default = defineConfig({
  server: {
    host: "localhost",
    port: 5174,
    strictPort: true,
    // Ensures that if the port is taken, it won't automatically switch
    watch: {
      usePolling: true
      // Useful for environments like WSL or Docker
    },
    hmr: {
      host: "localhost"
    }
    // Proxy settings can be added here if needed for API requests in development
    // proxy: {
    //     "/api": {
    //         target: "http://localhost:8000", // Laravel backend
    //         changeOrigin: true,
    //         secure: false,
    //     },
    // },
  },
  build: {
    outDir: "public/build",
    // Output directory for production builds
    commonjsOptions: {
      include: [/jquery/, /jquery-confirm/]
    }
  },
  optimizeDeps: {
    include: ["jquery", "jquery-confirm", "vue-toastification"]
  },
  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js",
        "resources/js/admin-support.js"
      ],
      refresh: true
      // Automatically refresh the browser on changes
    }),
    vue(),
    // Vue plugin for Vite
    vuetify({
      autoImport: true
      // styles: { configFile: 'src/scss/variables.scss' }
    }),
    compression(),
    commonjs()
    // Add the CommonJS plugin
  ],
  resolve: {
    alias: {
      "jquery": "jquery/dist/jquery.js",
      vue: "vue/dist/vue.esm-bundler.js",
      // Use Vue's ES module build with template compiler
      "@assets": "/public/assets",
      // Alias for assets
      "@": path.resolve(__vite_injected_original_dirname, "./resources/js"),
      // Alias for JavaScript resources
      "~": "/node_modules/"
      // Alias ~ to node_modules
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "@mdi/font/css/materialdesignicons.css";`
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxGYWl0aCBPbHV3YWRhcmEgQW1hb1xcXFxEb2N1bWVudHNcXFxcaW50cmFjYXJkXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxGYWl0aCBPbHV3YWRhcmEgQW1hb1xcXFxEb2N1bWVudHNcXFxcaW50cmFjYXJkXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9Vc2Vycy9GYWl0aCUyME9sdXdhZGFyYSUyMEFtYW8vRG9jdW1lbnRzL2ludHJhY2FyZC92aXRlLmNvbmZpZy5qc1wiO2ltcG9ydCB7IGRlZmluZUNvbmZpZyB9IGZyb20gXCJ2aXRlXCI7XG5pbXBvcnQgdnVlIGZyb20gXCJAdml0ZWpzL3BsdWdpbi12dWVcIjtcbmltcG9ydCBsYXJhdmVsIGZyb20gXCJsYXJhdmVsLXZpdGUtcGx1Z2luXCI7XG5pbXBvcnQgcGF0aCBmcm9tIFwicGF0aFwiO1xuaW1wb3J0IHZ1ZXRpZnkgZnJvbSAndml0ZS1wbHVnaW4tdnVldGlmeSc7XG5pbXBvcnQgY29tcHJlc3Npb24gZnJvbSAndml0ZS1wbHVnaW4tY29tcHJlc3Npb24nO1xuaW1wb3J0IGNvbW1vbmpzIGZyb20gJ0Byb2xsdXAvcGx1Z2luLWNvbW1vbmpzJzsgLy8gSW1wb3J0IHRoZSBDb21tb25KUyBwbHVnaW5cblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBzZXJ2ZXI6IHtcbiAgICAgICAgaG9zdDogXCJsb2NhbGhvc3RcIixcbiAgICAgICAgcG9ydDogNTE3NCxcbiAgICAgICAgc3RyaWN0UG9ydDogdHJ1ZSwgLy8gRW5zdXJlcyB0aGF0IGlmIHRoZSBwb3J0IGlzIHRha2VuLCBpdCB3b24ndCBhdXRvbWF0aWNhbGx5IHN3aXRjaFxuICAgICAgICB3YXRjaDoge1xuICAgICAgICAgICAgdXNlUG9sbGluZzogdHJ1ZSwgLy8gVXNlZnVsIGZvciBlbnZpcm9ubWVudHMgbGlrZSBXU0wgb3IgRG9ja2VyXG4gICAgICAgIH0sXG4gICAgICAgIGhtcjoge1xuICAgICAgICAgICAgaG9zdDogXCJsb2NhbGhvc3RcIixcbiAgICAgICAgfSxcbiAgICAgICAgLy8gUHJveHkgc2V0dGluZ3MgY2FuIGJlIGFkZGVkIGhlcmUgaWYgbmVlZGVkIGZvciBBUEkgcmVxdWVzdHMgaW4gZGV2ZWxvcG1lbnRcbiAgICAgICAgLy8gcHJveHk6IHtcbiAgICAgICAgLy8gICAgIFwiL2FwaVwiOiB7XG4gICAgICAgIC8vICAgICAgICAgdGFyZ2V0OiBcImh0dHA6Ly9sb2NhbGhvc3Q6ODAwMFwiLCAvLyBMYXJhdmVsIGJhY2tlbmRcbiAgICAgICAgLy8gICAgICAgICBjaGFuZ2VPcmlnaW46IHRydWUsXG4gICAgICAgIC8vICAgICAgICAgc2VjdXJlOiBmYWxzZSxcbiAgICAgICAgLy8gICAgIH0sXG4gICAgICAgIC8vIH0sXG4gICAgfSxcbiAgICBidWlsZDoge1xuICAgICAgICBvdXREaXI6IFwicHVibGljL2J1aWxkXCIsIC8vIE91dHB1dCBkaXJlY3RvcnkgZm9yIHByb2R1Y3Rpb24gYnVpbGRzXG4gICAgICAgIGNvbW1vbmpzT3B0aW9uczoge1xuICAgICAgICAgICAgaW5jbHVkZTogWy9qcXVlcnkvLCAvanF1ZXJ5LWNvbmZpcm0vXSxcbiAgICAgICAgICB9LFxuICAgIH0sXG4gICAgb3B0aW1pemVEZXBzOiB7XG4gICAgICBpbmNsdWRlOiBbJ2pxdWVyeScsICdqcXVlcnktY29uZmlybScsICd2dWUtdG9hc3RpZmljYXRpb24nXVxuICAgIH0sXG4gICAgcGx1Z2luczogW1xuICAgICAgICBsYXJhdmVsKHtcbiAgICAgICAgICAgIGlucHV0OiBbXCJyZXNvdXJjZXMvY3NzL2FwcC5jc3NcIiwgXCJyZXNvdXJjZXMvanMvYXBwLmpzXCIsIFxuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvanMvYWRtaW4tc3VwcG9ydC5qcyddLFxuICAgICAgICAgICAgcmVmcmVzaDogdHJ1ZSwgLy8gQXV0b21hdGljYWxseSByZWZyZXNoIHRoZSBicm93c2VyIG9uIGNoYW5nZXNcbiAgICAgICAgfSksXG4gICAgICAgIHZ1ZSgpLCAvLyBWdWUgcGx1Z2luIGZvciBWaXRlXG4gICAgICAgIHZ1ZXRpZnkoe1xuICAgICAgICAgICAgYXV0b0ltcG9ydDogdHJ1ZSxcbiAgICAgICAgICAgIC8vIHN0eWxlczogeyBjb25maWdGaWxlOiAnc3JjL3Njc3MvdmFyaWFibGVzLnNjc3MnIH1cbiAgICAgICAgfSksXG4gICAgICAgIGNvbXByZXNzaW9uKCksXG4gICAgICAgIGNvbW1vbmpzKCksIC8vIEFkZCB0aGUgQ29tbW9uSlMgcGx1Z2luXG4gICAgXSxcbiAgICByZXNvbHZlOiB7XG4gICAgICAgIGFsaWFzOiB7XG4gICAgICAgICAgICAnanF1ZXJ5JzogJ2pxdWVyeS9kaXN0L2pxdWVyeS5qcycsXG4gICAgICAgICAgICB2dWU6IFwidnVlL2Rpc3QvdnVlLmVzbS1idW5kbGVyLmpzXCIsIC8vIFVzZSBWdWUncyBFUyBtb2R1bGUgYnVpbGQgd2l0aCB0ZW1wbGF0ZSBjb21waWxlclxuICAgICAgICAgICAgXCJAYXNzZXRzXCI6IFwiL3B1YmxpYy9hc3NldHNcIiwgLy8gQWxpYXMgZm9yIGFzc2V0c1xuICAgICAgICAgICAgXCJAXCI6IHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsIFwiLi9yZXNvdXJjZXMvanNcIiksIC8vIEFsaWFzIGZvciBKYXZhU2NyaXB0IHJlc291cmNlc1xuICAgICAgICAgICAgJ34nOiAnL25vZGVfbW9kdWxlcy8nLCAvLyBBbGlhcyB+IHRvIG5vZGVfbW9kdWxlc1xuICAgICAgICB9LFxuICAgIH0sXG4gICAgY3NzOiB7XG4gICAgICBwcmVwcm9jZXNzb3JPcHRpb25zOiB7XG4gICAgICAgIHNjc3M6IHtcbiAgICAgICAgICBhZGRpdGlvbmFsRGF0YTogYEBpbXBvcnQgXCJAbWRpL2ZvbnQvY3NzL21hdGVyaWFsZGVzaWduaWNvbnMuY3NzXCI7YFxuICAgICAgICB9XG4gICAgICB9XG4gICAgfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQW1WLFNBQVMsb0JBQW9CO0FBQ2hYLE9BQU8sU0FBUztBQUNoQixPQUFPLGFBQWE7QUFDcEIsT0FBTyxVQUFVO0FBQ2pCLE9BQU8sYUFBYTtBQUNwQixPQUFPLGlCQUFpQjtBQUN4QixPQUFPLGNBQWM7QUFOckIsSUFBTSxtQ0FBbUM7QUFRekMsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsUUFBUTtBQUFBLElBQ0osTUFBTTtBQUFBLElBQ04sTUFBTTtBQUFBLElBQ04sWUFBWTtBQUFBO0FBQUEsSUFDWixPQUFPO0FBQUEsTUFDSCxZQUFZO0FBQUE7QUFBQSxJQUNoQjtBQUFBLElBQ0EsS0FBSztBQUFBLE1BQ0QsTUFBTTtBQUFBLElBQ1Y7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUEsRUFTSjtBQUFBLEVBQ0EsT0FBTztBQUFBLElBQ0gsUUFBUTtBQUFBO0FBQUEsSUFDUixpQkFBaUI7QUFBQSxNQUNiLFNBQVMsQ0FBQyxVQUFVLGdCQUFnQjtBQUFBLElBQ3RDO0FBQUEsRUFDTjtBQUFBLEVBQ0EsY0FBYztBQUFBLElBQ1osU0FBUyxDQUFDLFVBQVUsa0JBQWtCLG9CQUFvQjtBQUFBLEVBQzVEO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsUUFBQztBQUFBLFFBQXlCO0FBQUEsUUFDN0I7QUFBQSxNQUErQjtBQUFBLE1BQ25DLFNBQVM7QUFBQTtBQUFBLElBQ2IsQ0FBQztBQUFBLElBQ0QsSUFBSTtBQUFBO0FBQUEsSUFDSixRQUFRO0FBQUEsTUFDSixZQUFZO0FBQUE7QUFBQSxJQUVoQixDQUFDO0FBQUEsSUFDRCxZQUFZO0FBQUEsSUFDWixTQUFTO0FBQUE7QUFBQSxFQUNiO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDSCxVQUFVO0FBQUEsTUFDVixLQUFLO0FBQUE7QUFBQSxNQUNMLFdBQVc7QUFBQTtBQUFBLE1BQ1gsS0FBSyxLQUFLLFFBQVEsa0NBQVcsZ0JBQWdCO0FBQUE7QUFBQSxNQUM3QyxLQUFLO0FBQUE7QUFBQSxJQUNUO0FBQUEsRUFDSjtBQUFBLEVBQ0EsS0FBSztBQUFBLElBQ0gscUJBQXFCO0FBQUEsTUFDbkIsTUFBTTtBQUFBLFFBQ0osZ0JBQWdCO0FBQUEsTUFDbEI7QUFBQSxJQUNGO0FBQUEsRUFDRjtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
