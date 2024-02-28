import { defineConfig } from 'vite'
import { resolve } from 'path'
import vue from '@vitejs/plugin-vue'
import VueDevTools from 'vite-plugin-vue-devtools'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue(), VueDevTools()],
    build: {
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'src/view/adminhtml/web/main.js')
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]'
            }
        },
        outDir: resolve(__dirname, 'src/view/adminhtml/web/dist'),

    }
})
