// @ts-check
import {defineConfig, envField} from 'astro/config';
import vue from '@astrojs/vue';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    integrations: [vue()],
    env: {
        schema: {
            CORE_API_TOKEN: envField.string({ context: "server", access: "secret" }),
            CLIENT_CORE_API_TOKEN: envField.string({ context: "client", access: "public" }),
        }
    },
    vite: {
        plugins: [tailwindcss()]
    }
});