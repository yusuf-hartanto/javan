import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '')

  const port = parseInt(env.VITE_PORT || '5173', 10)
  const apiUrl = env.VITE_API_URL || 'http://127.0.0.1:5001/api'
  const backendOrigin = apiUrl.replace(/\/api\/?$/, '')

  return {
    plugins: [vue()],
    server: {
      port,
      proxy: {
        '/api': {
          target: backendOrigin,
          changeOrigin: true,
        },
      },
    },
  }
})
