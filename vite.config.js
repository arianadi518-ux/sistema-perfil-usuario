import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    outDir: "assets",
    emptyOutDir: false,
    rollupOptions: {
      input: "src/style.css",
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith(".css")) {
            return "css/style.css";
          }

          return "build/[name]-[hash][extname]";
        },
        entryFileNames: "build/[name].js"
      }
    }
  }
});
