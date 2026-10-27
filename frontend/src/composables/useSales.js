import { ref } from 'vue'

const API_BASE_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:5001/api'
const DEFAULT_SEARCH = import.meta.env.VITE_DEFAULT_SEARCH || 'budi'

export function useSales() {
  const sales = ref([])
  const loading = ref(false)
  const error = ref(null)
  const searchQuery = ref(DEFAULT_SEARCH)

  const fetchSales = async (name = searchQuery.value) => {
    loading.value = true
    error.value = null

    try {
      const trimmed = (name || '').trim()
      const url = `${API_BASE_URL}/sales${trimmed ? `?search=${encodeURIComponent(trimmed)}` : ''}`
      const response = await fetch(url, {
        headers: {
          Accept: 'application/json',
        },
      })

      if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status} ${response.statusText}`)
      }

      const json = await response.json()
      if (json.success) {
        sales.value = json.data || []
      } else {
        throw new Error(json.message || 'Gagal memuat data sales')
      }
    } catch (err) {
      error.value = err.message || 'Terjadi kesalahan saat memuat data'
      sales.value = []
    } finally {
      loading.value = false
    }
  }

  return {
    sales,
    loading,
    error,
    searchQuery,
    defaultSearch: DEFAULT_SEARCH,
    fetchSales,
  }
}
