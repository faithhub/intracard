import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import vuetify from 'vite-plugin-vuetify';
import compression from 'vite-plugin-compression';
import commonjs from '@rollup/plugin-commonjs'; // Import the CommonJS plugin

export default defineConfig({
    server: {
        host: "localhost",
        port: 5174,
        strictPort: true, // Ensures that if the port is taken, it won't automatically switch
        watch: {
            usePolling: true, // Useful for environments like WSL or Docker
        },
        hmr: {
            host: "localhost",
        },
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
        // Add these production optimizations
        minify: 'terser',
        sourcemap: false,
        chunkSizeWarningLimit: 1600,
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', 'vuetify'],
                    // Other manual chunks if needed
                }
            }
        },
        commonjsOptions: {
            include: [/jquery/, /jquery-confirm/],
        },
    },
    optimizeDeps: {
      include: ['jquery', 'jquery-confirm', 'vue-toastification']
    },
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js", 
                'resources/js/admin-support.js'],
            refresh: true, // Automatically refresh the browser on changes
        }),
        vue(), // Vue plugin for Vite
        vuetify({
            autoImport: true,
            // styles: { configFile: 'src/scss/variables.scss' }
        }),
        compression(),
        commonjs(), // Add the CommonJS plugin
    ],
    resolve: {
        alias: {
            'jquery': 'jquery/dist/jquery.js',
            vue: "vue/dist/vue.esm-bundler.js", // Use Vue's ES module build with template compiler
            "@assets": "/public/assets", // Alias for assets
            "@": path.resolve(__dirname, "./resources/js"), // Alias for JavaScript resources
            '~': '/node_modules/', // Alias ~ to node_modules
        },
    },
    css: {
        preprocessorOptions: {
          scss: {
            additionalData: ``
          }
        }
      },
    // css: {
    //   preprocessorOptions: {
    //     scss: {
    //       additionalData: `@import "@mdi/font/css/materialdesignicons.css";`
    //     }
    //   }
    // }
});
