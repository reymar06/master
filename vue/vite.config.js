//  CORRECT
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  server: {
    // proxy: {
    //   '/api': {
    //     target: 'http://127.0.0.1:8001', // <-- This must be your BACKEND API url, not the frontend Vite port!
    //     // target : 'http://hrdapps70new:7070/data-extract/',
    //     changeOrigin: true,
       
    //   },
    // },
    host: true, // Changes 'localhost' to '127.0.0.1'
    // port: 5173,        // Sets your preferred port
    // strictPort: true,  // Prevents Vite from automatically trying another port if 5173 is busy
  },
});