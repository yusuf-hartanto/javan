<script setup>
import { formatNumber, formatProbability, getCountryFlag } from '../utils/formatters'

defineProps({
  sale: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <article class="sales-card">
    <div class="card-header">
      <div class="user-meta">
        <div class="avatar" :class="(sale.gender || '').toLowerCase()">
          {{ (sale.name || '?').charAt(0).toUpperCase() }}
        </div>
        <div class="title-wrap">
          <h3 class="name">{{ sale.name }}</h3>
          <div class="badges">
            <span class="badge gender" :class="(sale.gender || '').toLowerCase()">
              {{ sale.gender === 'male' ? '♂ Laki-laki' : sale.gender === 'female' ? '♀ Perempuan' : (sale.gender || 'Tidak Diketahui') }}
            </span>
            <span class="badge count">
              <span class="dot"></span>
              {{ formatNumber(sale.count) }}
            </span>
          </div>
        </div>
      </div>
      <div class="confidence-box">
        <span class="label">Probabilitas</span>
        <span class="value">{{ formatProbability(sale.probability) }}</span>
      </div>
    </div>

    <div class="section-title">
      <h4>Negara & Region</h4>
      <span class="count-pill">{{ (sale.countries || []).length }} negara</span>
    </div>

    <div v-if="sale.countries && sale.countries.length > 0" class="countries-grid">
      <div
        v-for="country in sale.countries"
        :key="country.id"
        class="country-item"
      >
        <div class="country-header">
          <div class="flag-code">
            <span class="flag">{{ getCountryFlag(country.country_id) }}</span>
            <span class="code">{{ country.country_id }}</span>
          </div>
          <span class="probability-tag">{{ formatProbability(country.probability) }}</span>
        </div>

        <div class="progress-bar-bg">
          <div
            class="progress-bar-fill"
            :style="{ width: `${Math.min(100, Math.max(5, parseFloat(String(country.probability)) * 100))}%` }"
          ></div>
        </div>

        <div class="regions-list" v-if="country.regions && country.regions.length > 0">
          <span
            v-for="reg in country.regions"
            :key="reg.id"
            class="region-pill"
          >
            📍 {{ reg.region }}
          </span>
        </div>
      </div>
    </div>

    <div v-else class="no-countries">
      <p>Tidak ada data negara yang terprediksi.</p>
    </div>
  </article>
</template>

<style scoped>
.sales-card {
  background: #ffffff;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
  padding: 24px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.sales-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 28px -4px rgba(0, 0, 0, 0.08);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
  padding-bottom: 20px;
  border-bottom: 1px solid #f1f5f9;
}

.user-meta {
  display: flex;
  align-items: center;
  gap: 16px;
}

.avatar {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  font-weight: 700;
  color: #ffffff;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.avatar.male {
  background: linear-gradient(135deg, #2563eb, #3b82f6);
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

.avatar.female {
  background: linear-gradient(135deg, #ec4899, #f43f5e);
  box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
}

.title-wrap .name {
  margin: 0 0 6px 0;
  font-size: 20px;
  font-weight: 700;
  color: #0f172a;
  text-transform: capitalize;
}

.badges {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 9999px;
  font-size: 12px;
  font-weight: 600;
}

.badge.gender.male {
  background: #eff6ff;
  color: #1d4ed8;
  border: 1px solid #bfdbfe;
}

.badge.gender.female {
  background: #fdf2f8;
  color: #be185d;
  border: 1px solid #fbcfe8;
}

.badge.gender {
  background: #f1f5f9;
  color: #475569;
  border: 1px solid #e2e8f0;
}

.badge.count {
  background: #f8fafc;
  color: #64748b;
  border: 1px solid #e2e8f0;
}

.badge.count .dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #10b981;
}

.confidence-box {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  background: #f8fafc;
  padding: 8px 14px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
}

.confidence-box .label {
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
  color: #94a3b8;
  letter-spacing: 0.5px;
}

.confidence-box .value {
  font-size: 16px;
  font-weight: 700;
  color: #0f172a;
}

.section-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 20px 0 12px 0;
}

.section-title h4 {
  margin: 0;
  font-size: 14px;
  font-weight: 600;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.count-pill {
  font-size: 12px;
  color: #64748b;
  background: #f1f5f9;
  padding: 2px 8px;
  border-radius: 6px;
}

.countries-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 12px;
}

.country-item {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  transition: all 0.15s ease;
}

.country-item:hover {
  background: #ffffff;
  border-color: #cbd5e1;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
}

.country-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.flag-code {
  display: flex;
  align-items: center;
  gap: 8px;
}

.flag {
  font-size: 22px;
  line-height: 1;
}

.code {
  font-size: 15px;
  font-weight: 700;
  color: #1e293b;
}

.probability-tag {
  font-size: 12px;
  font-weight: 700;
  color: #0284c7;
  background: #e0f2fe;
  padding: 2px 8px;
  border-radius: 6px;
}

.progress-bar-bg {
  width: 100%;
  height: 6px;
  background: #e2e8f0;
  border-radius: 999px;
  overflow: hidden;
}

.progress-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #38bdf8, #0284c7);
  border-radius: 999px;
  transition: width 0.4s ease;
}

.regions-list {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 2px;
}

.region-pill {
  font-size: 11px;
  font-weight: 500;
  color: #475569;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  padding: 3px 8px;
  border-radius: 6px;
}

.no-countries {
  background: #f8fafc;
  border-radius: 10px;
  padding: 16px;
  text-align: center;
  color: #94a3b8;
  font-size: 13px;
}

@media (max-width: 640px) {
  .card-header {
    flex-direction: column;
    align-items: flex-start;
  }
  .confidence-box {
    align-items: flex-start;
    width: 100%;
    box-sizing: border-box;
  }
  .countries-grid {
    grid-template-columns: 1fr;
  }
}
</style>
