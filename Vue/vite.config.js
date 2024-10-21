import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vitejs.dev/config/
export default defineConfig({
  build: {
    sourcemap: false, // Disable source maps for production
  },
  css: {
    preprocessorOptions: {
      css: {
        sourceMap: false, // Disable source maps for CSS
      },
    },
  },

  plugins: [vue()],

  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", import.meta.url)),
    },
  },
});
