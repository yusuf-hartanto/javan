<script setup>
import { onMounted } from 'vue'
import { useSales } from './composables/useSales'
import SalesCard from './components/SalesCard.vue'

const { sales, loading, error, searchQuery, defaultSearch, fetchSales } = useSales()

const apiUrl = import.meta.env.VITE_API_URL || 'http://127.0.0.1:5001/api'
const displayApi = apiUrl.replace(/^https?:\/\//, '')

const handleSearch = () => {
  fetchSales(searchQuery.value)
}

const selectQuickTag = (tag) => {
  searchQuery.value = tag
  fetchSales(tag)
}

onMounted(() => {
  fetchSales(defaultSearch)
})
</script>

<template>
  <div class="app-container">
    <header class="navbar">
      <div class="header-inner">
        <div class="logo-area">
          <div class="logo-icon">📊</div>
          <div>
            <h1 class="logo-title">Sales</h1>
          </div>
        </div>
        <div class="api-badge">
          <span class="live-dot"></span>
          <span>API: <code>{{ displayApi }}</code></span>
        </div>
      </div>
    </header>

    <main class="main-content">
      <section class="search-card">
        <form @submit.prevent="handleSearch" class="search-form">
          <div class="input-wrapper">
            <span class="search-icon">🔍</span>
            <input
              v-model="searchQuery"
              type="text"
              class="search-input"
              placeholder="Cari nama"
              :disabled="loading"
            />
            <button
              v-if="searchQuery"
              type="button"
              class="clear-btn"
              @click="searchQuery = ''"
              title="Bersihkan pencarian"
            >
              ✕
            </button>
          </div>
          <button type="submit" class="submit-btn" :disabled="loading">
            <span v-if="loading" class="spinner"></span>
            <span>{{ loading ? 'Memuat...' : 'Cari' }}</span>
          </button>
        </form>
      </section>

      <section v-if="loading" class="skeleton-wrapper">
        <div class="skeleton-card">
          <div class="skeleton-header">
            <div class="skeleton-avatar pulse"></div>
            <div class="skeleton-lines">
              <div class="skeleton-line pulse short"></div>
              <div class="skeleton-line pulse extra-short"></div>
            </div>
          </div>
          <div class="skeleton-body pulse"></div>
        </div>
      </section>

      <section v-else-if="error" class="error-banner">
        <div class="error-icon">⚠️</div>
        <div class="error-content">
          <h4>Gagal Mengambil Data</h4>
          <p>{{ error }}</p>
          <button type="button" class="retry-btn" @click="handleSearch">
            Coba Lagi
          </button>
        </div>
      </section>

      <section v-else-if="sales.length === 0" class="empty-state">
        <div class="empty-icon">📭</div>
        <h3>Tidak Ada Data Sales</h3>
        <p>Data untuk pencarian "{{ searchQuery }}" tidak ditemukan di database maupun API eksternal.</p>
        <button type="button" class="tag-btn" @click="selectQuickTag(defaultSearch)">
          Lihat Data '{{ defaultSearch }}'
        </button>
      </section>

      <section v-else class="results-container">
        <div class="results-header">
          <h2>Hasil Pencarian ({{ sales.length }})</h2>
          <span class="active-query">Query: <code>{{ searchQuery }}</code></span>
        </div>

        <div class="cards-list">
          <SalesCard
            v-for="item in sales"
            :key="item.id"
            :sale="item"
          />
        </div>
      </section>
    </main>

    <footer class="footer">
      <p>@{{ new Date().getFullYear() }} - Javan - Yusuf Hartanto</p>
    </footer>
  </div>
</template>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f8fafc;
  color: #1e293b;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

.navbar {
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  position: sticky;
  top: 0;
  z-index: 20;
}

.header-inner {
  max-width: 1000px;
  margin: 0 auto;
  padding: 16px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 12px;
}

.logo-icon {
  font-size: 28px;
  width: 44px;
  height: 44px;
  background: #eef2ff;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.logo-title {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #0f172a;
}

.logo-subtitle {
  margin: 2px 0 0 0;
  font-size: 12px;
  color: #64748b;
}

.api-badge {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #f1f5f9;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 12px;
  color: #475569;
}

.api-badge code {
  font-weight: 600;
  color: #0f172a;
}

.live-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: #10b981;
  box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
}

.main-content {
  max-width: 1000px;
  width: 100%;
  margin: 32px auto;
  padding: 0 24px;
  box-sizing: border-box;
  flex: 1;
}

.search-card {
  background: #ffffff;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 16px -2px rgba(0, 0, 0, 0.04);
  padding: 24px;
  margin-bottom: 28px;
}

.search-form {
  display: flex;
  gap: 12px;
}

.input-wrapper {
  position: relative;
  flex: 1;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 16px;
  font-size: 16px;
  color: #94a3b8;
  pointer-events: none;
}

.search-input {
  width: 100%;
  padding: 14px 40px 14px 44px;
  font-size: 15px;
  border: 1.5px solid #cbd5e1;
  border-radius: 12px;
  outline: none;
  background: #f8fafc;
  color: #0f172a;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.search-input:focus {
  background: #ffffff;
  border-color: #4f46e5;
  box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
}

.clear-btn {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 6px;
  font-size: 12px;
  border-radius: 50%;
}

.clear-btn:hover {
  color: #475569;
}

.submit-btn {
  background: linear-gradient(135deg, #4f46e5, #4338ca);
  color: #ffffff;
  border: none;
  border-radius: 12px;
  padding: 0 24px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.submit-btn:hover:not(:disabled) {
  opacity: 0.95;
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
}

.submit-btn:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.quick-tags {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 16px;
  flex-wrap: wrap;
}

.tags-label {
  font-size: 13px;
  color: #64748b;
  font-weight: 500;
}

.tag-btn {
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  color: #475569;
  padding: 4px 12px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s ease;
}

.tag-btn:hover {
  background: #e2e8f0;
  color: #1e293b;
}

.tag-btn.active {
  background: #eef2ff;
  border-color: #c7d2fe;
  color: #4f46e5;
}

.skeleton-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
}

.skeleton-header {
  display: flex;
  gap: 16px;
  margin-bottom: 20px;
}

.skeleton-avatar {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  background: #e2e8f0;
}

.skeleton-lines {
  display: flex;
  flex-direction: column;
  gap: 8px;
  justify-content: center;
  flex: 1;
}

.skeleton-line {
  height: 14px;
  background: #e2e8f0;
  border-radius: 6px;
}

.skeleton-line.short {
  width: 40%;
}

.skeleton-line.extra-short {
  width: 20%;
}

.skeleton-body {
  height: 120px;
  background: #f1f5f9;
  border-radius: 12px;
}

.pulse {
  animation: pulse 1.5s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.45; }
}

.error-banner {
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 14px;
  padding: 20px;
  display: flex;
  gap: 16px;
  align-items: flex-start;
}

.error-icon {
  font-size: 24px;
}

.error-content h4 {
  margin: 0 0 6px 0;
  color: #991b1b;
  font-size: 15px;
}

.error-content p {
  margin: 0 0 12px 0;
  color: #b91c1c;
  font-size: 13px;
}

.retry-btn {
  background: #ef4444;
  color: #ffffff;
  border: none;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
}

.empty-state {
  background: #ffffff;
  border: 1px dashed #cbd5e1;
  border-radius: 16px;
  padding: 48px 24px;
  text-align: center;
}

.empty-icon {
  font-size: 40px;
  margin-bottom: 12px;
}

.empty-state h3 {
  margin: 0 0 6px 0;
  color: #0f172a;
  font-size: 18px;
}

.empty-state p {
  margin: 0 0 20px 0;
  color: #64748b;
  font-size: 14px;
}

.results-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.results-header h2 {
  margin: 0;
  font-size: 16px;
  font-weight: 700;
  color: #334155;
}

.active-query {
  font-size: 13px;
  color: #64748b;
}

.active-query code {
  color: #4f46e5;
  font-weight: 600;
}

.cards-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.footer {
  text-align: center;
  padding: 24px;
  border-top: 1px solid #e2e8f0;
  background: #ffffff;
  color: #94a3b8;
  font-size: 13px;
  margin-top: auto;
}

@media (max-width: 640px) {
  .search-form {
    flex-direction: column;
  }
  .submit-btn {
    padding: 14px;
    justify-content: center;
  }
}
</style>
