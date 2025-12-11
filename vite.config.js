import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/recherche.js','resources/js/mes-trajets.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});