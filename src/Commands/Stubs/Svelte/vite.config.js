import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { svelte } from "@sveltejs/vite-plugin-svelte";

export default defineConfig({
  plugins: [
    laravel({
      publicDirectory: 'storage/public',
      input: ["storage/inertia/js/app.js"],
      refresh: true,
      ssr: 'storage/inertia/js/ssr.js',
    }),

    svelte({
      compilerOptions: {
        hydratable: true,
      },
    }),
  ],
});
