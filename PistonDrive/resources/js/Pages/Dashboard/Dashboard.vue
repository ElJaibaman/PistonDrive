<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
  vehiculosActivos:       Array,
  ganancias:              Array,
  garantiasVigentes:      Array,
  cotizacionesPendientes: Array,
  resumen:                Object,
});

const totalGanancias = props.ganancias.reduce((s, g) => s + parseFloat(g.ingreso_total), 0);

function diasRestantes(fecha) {
  return Math.max(0, Math.ceil((new Date(fecha) - new Date()) / 86400000));
}

const fechaHoy = (() => {
  const now = new Date();
  const dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
  const meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
  return `${dias[now.getDay()]}, ${now.getDate()} de ${meses[now.getMonth()]} ${now.getFullYear()}`;
})();

const horaHoy = new Date().toLocaleTimeString('es-HN', { hour: '2-digit', minute: '2-digit' }) + ' hrs';

// ── Paginación vehículos (15 por página) ──────────────
const PER_VEH = 10;
const pageVeh = ref(1);
const totalPagesVeh = computed(() => Math.ceil(props.vehiculosActivos.length / PER_VEH));
const vehiculosPaginados = computed(() => {
  const start = (pageVeh.value - 1) * PER_VEH;
  return props.vehiculosActivos.slice(start, start + PER_VEH);
});

// ── Paginación garantías (5 por página) ───────────────
const PER_GAR = 5;
const pageGar = ref(1);
const totalPagesGar = computed(() => Math.ceil(props.garantiasVigentes.length / PER_GAR));
const garantiasPaginadas = computed(() => {
  const start = (pageGar.value - 1) * PER_GAR;
  return props.garantiasVigentes.slice(start, start + PER_GAR);
});

// ── Paginación cotizaciones (5 por página) ────────────
const PER_COT = 5;
const pageCot = ref(1);
const totalPagesCot = computed(() => Math.ceil(props.cotizacionesPendientes.length / PER_COT));
const cotizacionesPaginadas = computed(() => {
  const start = (pageCot.value - 1) * PER_COT;
  return props.cotizacionesPendientes.slice(start, start + PER_COT);
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="dash-page">

      <!-- Top bar -->
      <div class="topbar">
        <div class="topbar-left">
          <h1 class="page-title">Panel principal</h1>
          <p class="page-date">{{ fechaHoy }}</p>
        </div>
        <div class="topbar-right">
          <div class="badge-hora">{{ horaHoy }}</div>
        </div>
      </div>

      <div class="header-line" />

      <!-- Stats -->
      <div class="stat-grid">
        <div class="stat-card grey">
          <div class="stat-icon grey">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="10" stroke="#9CA3AF" stroke-width="2"/>
              <polyline points="12,6 12,12 16,14" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="stat-num">{{ resumen.pendientes }}</div>
          <div class="stat-label">Pendientes</div>
        </div>

        <div class="stat-card blue">
          <div class="stat-icon blue">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <circle cx="12" cy="12" r="3" stroke="#2563EB" stroke-width="2"/>
              <path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14" stroke="#2563EB" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="stat-num blue">{{ resumen.en_proceso }}</div>
          <div class="stat-label">En proceso</div>
        </div>

        <div class="stat-card orange">
          <div class="stat-icon orange">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <rect x="3" y="4" width="18" height="16" rx="2" stroke="#D97706" stroke-width="2"/>
              <path d="M16 2v4M8 2v4M3 10h18" stroke="#D97706" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="stat-num orange">{{ resumen.esperando_aprobacion }}</div>
          <div class="stat-label">Esp. aprobación</div>
        </div>

        <div class="stat-card teal">
          <div class="stat-icon teal">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
              <path d="M4 12l5 5L20 7" stroke="#0D9488" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="stat-num teal">{{ resumen.terminados }}</div>
          <div class="stat-label">Terminados</div>
        </div>

        <Link :href="route('secretaria.cotizaciones.index')" class="stat-card-link">
          <div class="stat-card red">
            <div class="stat-icon red">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
                <polyline points="14,2 14,8 20,8" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
                <line x1="12" y1="18" x2="12" y2="12" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
                <line x1="9"  y1="15" x2="15" y2="15" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="stat-num red">{{ resumen.cotizaciones_pendientes }}</div>
            <div class="stat-label">Cotizaciones</div>
          </div>
        </Link>
      </div>

      <!-- Two column layout -->
      <div class="two-col">

        <!-- LEFT -->
        <div>

          <!-- Vehículos en el taller -->
          <div class="card mb-4">
            <div class="card-accent" />
            <div class="card-header">
              <div class="card-title">
                <div class="title-dot" style="background:#C0192A" />
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                  <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3" stroke="#4A5568" stroke-width="2" stroke-linecap="round"/>
                  <rect x="9" y="11" width="14" height="10" rx="2" stroke="#4A5568" stroke-width="2"/>
                </svg>
                Vehículos en el taller
              </div>
              <div class="card-badge">{{ vehiculosActivos.length }} activos</div>
            </div>

            <table class="vtable">
              <thead>
                <tr>
                  <th>Cliente</th>
                  <th>Vehículo</th>
                  <th>Mecánico</th>
                  <th>Estado</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in vehiculosPaginados" :key="orden.id">
                  <td><span class="client-name">{{ orden.vehiculo.cliente.nombre }}</span></td>
                  <td>
                    {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}
                    <span v-if="orden.vehiculo.placa" class="placa">{{ orden.vehiculo.placa }}</span>
                  </td>
                  <td>{{ orden.mecanico?.nombre ?? '—' }}</td>
                  <td>
                    <span class="chip" :class="orden.estado_config.color">
                      {{ orden.estado_config.label }}
                    </span>
                  </td>
                  <td>
                    <Link :href="route('ordenes.show', orden.id)">
                      <button class="eye-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/>
                          <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/>
                        </svg>
                      </button>
                    </Link>
                  </td>
                </tr>
                <tr v-if="vehiculosActivos.length === 0">
                  <td colspan="5" class="empty-state">No hay vehículos activos</td>
                </tr>
              </tbody>
            </table>

            <!-- Paginación vehículos -->
            <div class="mini-pagination" v-if="totalPagesVeh > 1">
              <button class="mini-btn" :disabled="pageVeh === 1" @click="pageVeh--">←</button>
              <button
                v-for="p in totalPagesVeh" :key="p"
                class="mini-num" :class="{ 'mini-active': p === pageVeh }"
                @click="pageVeh = p"
              >{{ p }}</button>
              <button class="mini-btn" :disabled="pageVeh === totalPagesVeh" @click="pageVeh++">→</button>
            </div>
          </div>

          <!-- Cotizaciones pendientes -->
          <div class="card" v-if="cotizacionesPendientes.length > 0">
            <div class="card-accent orange-accent" />
            <div class="card-header">
              <div class="card-title">
                <div class="title-dot" style="background:#D97706" />
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="10" stroke="#4A5568" stroke-width="2"/>
                  <polyline points="12,6 12,12 16,14" stroke="#4A5568" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Cotizaciones pendientes de revisión
              </div>
              <div class="card-badge orange-badge">{{ cotizacionesPendientes.length }} pendientes</div>
            </div>

            <div v-for="c in cotizacionesPaginadas" :key="c.id" class="cot-row">
              <div class="cot-left">
                <span class="cot-orden">#ORD-{{ String(c.orden.id).padStart(4, '0') }}</span>
                <span class="cot-cliente">{{ c.orden.vehiculo.cliente.nombre }}</span>
                <span class="cot-mec">Mec: {{ c.orden.mecanico?.nombre ?? '—' }}</span>
              </div>
              <div class="cot-right">
                <span class="cot-total">L. {{ parseFloat(c.total_general).toFixed(2) }}</span>
                <Link :href="route('secretaria.cotizaciones.show', c.id)">
                  <button class="btn-revisar">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none">
                      <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                      <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    Revisar
                  </button>
                </Link>
              </div>
            </div>

            <!-- Paginación cotizaciones -->
            <div class="mini-pagination" v-if="totalPagesCot > 1">
              <button class="mini-btn" :disabled="pageCot === 1" @click="pageCot--">←</button>
              <button
                v-for="p in totalPagesCot" :key="p"
                class="mini-num" :class="{ 'mini-active': p === pageCot }"
                @click="pageCot = p"
              >{{ p }}</button>
              <button class="mini-btn" :disabled="pageCot === totalPagesCot" @click="pageCot++">→</button>
            </div>
          </div>

        </div>

        <!-- RIGHT -->
        <div>

          <!-- Ingresos -->
          <div class="card mb-4">
            <div class="card-accent green-accent" />
            <div class="card-header">
              <div class="card-title">
                <div class="title-dot" style="background:#059669" />
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                  <polyline points="22,12 18,12 15,21 9,3 6,12 2,12" stroke="#4A5568" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Ingresos (6 meses)
              </div>
            </div>

            <div v-for="g in ganancias" :key="g.mes" class="income-row">
              <span class="income-month">{{ g.mes }}</span>
              <span class="income-val">L. {{ parseFloat(g.ingreso_total).toFixed(2) }}</span>
            </div>

            <div class="income-total-row">
              <span class="income-total-label">Total acumulado</span>
              <span class="income-total-val">L. {{ totalGanancias.toFixed(2) }}</span>
            </div>
          </div>

          <!-- Garantías vigentes -->
          <div class="card">
            <div class="card-accent teal-accent" />
            <div class="card-header">
              <div class="card-title">
                <div class="title-dot" style="background:#0D9488" />
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                  <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="#4A5568" stroke-width="2" stroke-linejoin="round"/>
                  <polyline points="9,12 11,14 15,10" stroke="#0D9488" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Garantías vigentes
              </div>
              <div class="card-badge teal-badge">{{ garantiasVigentes.length }} activas</div>
            </div>

            <div v-for="g in garantiasPaginadas" :key="g.id" class="garantia-item">
              <div class="garantia-name">{{ g.ticket?.orden?.vehiculo?.cliente?.nombre }}</div>
              <div class="garantia-sub">Vence: {{ g.fecha_vencimiento }}</div>
              <span class="dias-chip" :class="diasRestantes(g.fecha_vencimiento) <= 5 ? 'dias-orange' : 'dias-green'">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                  <polyline points="12,6 12,12 16,14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                {{ diasRestantes(g.fecha_vencimiento) }} días restantes
              </span>
            </div>

            <div v-if="garantiasVigentes.length === 0" class="empty-state">Sin garantías activas</div>

            <!-- Paginación garantías -->
            <div class="mini-pagination" v-if="totalPagesGar > 1">
              <button class="mini-btn" :disabled="pageGar === 1" @click="pageGar--">←</button>
              <button
                v-for="p in totalPagesGar" :key="p"
                class="mini-num" :class="{ 'mini-active': p === pageGar }"
                @click="pageGar = p"
              >{{ p }}</button>
              <button class="mini-btn" :disabled="pageGar === totalPagesGar" @click="pageGar++">→</button>
            </div>
          </div>

        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.dash-page {
  min-height: 100vh;
  background: #F7F8FA;
  padding: 28px 32px 48px;
  font-family: 'DM Sans', system-ui, sans-serif;
}
.topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0; }
.page-title { font-size: 1.5rem; font-weight: 800; color: #1A202C; letter-spacing: -0.4px; margin: 0; }
.page-date { font-size: 0.78rem; color: #9CA3AF; margin-top: 3px; font-family: 'JetBrains Mono', monospace; }
.badge-hora { background: white; border: 1.5px solid #E5E7EB; border-radius: 10px; padding: 7px 14px; font-size: 0.78rem; color: #6B7280; font-weight: 600; }
.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); border-radius: 2px; margin: 16px 0 28px; }

.stat-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 14px; margin-bottom: 28px; }
.stat-card-link { text-decoration: none; }
.stat-card { background: white; border-radius: 14px; padding: 16px; border: 1px solid #F0F0F2; box-shadow: 0 2px 8px rgba(0,0,0,0.04); position: relative; overflow: hidden; cursor: pointer; transition: transform 0.15s, box-shadow 0.15s; }
.stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
.stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 14px 14px 0 0; }
.stat-card.grey::before  { background: #9CA3AF; }
.stat-card.blue::before  { background: #3B82F6; }
.stat-card.orange::before{ background: #F59E0B; }
.stat-card.teal::before  { background: #14B8A6; }
.stat-card.red::before   { background: #C0192A; }
.stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.stat-icon.grey   { background: #F3F4F6; }
.stat-icon.blue   { background: #EFF6FF; }
.stat-icon.orange { background: #FFFBEB; }
.stat-icon.teal   { background: #F0FDFA; }
.stat-icon.red    { background: #FEF2F2; }
.stat-num { font-size: 2rem; font-weight: 800; color: #1A202C; line-height: 1; margin-bottom: 4px; font-family: 'JetBrains Mono', monospace; }
.stat-num.blue   { color: #2563EB; }
.stat-num.orange { color: #D97706; }
.stat-num.teal   { color: #0D9488; }
.stat-num.red    { color: #C0192A; }
.stat-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #9CA3AF; }

.two-col { display: grid; grid-template-columns: 1fr 380px; gap: 20px; align-items: start; }
.mb-4 { margin-bottom: 18px; }

.card { background: white; border-radius: 14px; border: 1px solid #F0F0F2; box-shadow: 0 2px 8px rgba(0,0,0,0.04); overflow: hidden; }
.card-accent { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 40%, transparent); }
.card-accent.orange-accent { background: linear-gradient(to right, #D97706, #B45309 40%, transparent); }
.card-accent.green-accent  { background: linear-gradient(to right, #059669, #047857 40%, transparent); }
.card-accent.teal-accent   { background: linear-gradient(to right, #0D9488, #0F766E 40%, transparent); }
.card-header { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #F7F8FA; }
.card-title { display: flex; align-items: center; gap: 8px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #4A5568; }
.title-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.card-badge { font-size: 0.68rem; background: #F3F4F6; color: #6B7280; padding: 3px 9px; border-radius: 20px; font-weight: 600; }
.card-badge.orange-badge { background: #FFFBEB; color: #D97706; }
.card-badge.teal-badge   { background: #F0FDFA; color: #0D9488; }

.vtable { width: 100%; border-collapse: collapse; }
.vtable th { font-size: 0.67rem; text-transform: uppercase; letter-spacing: 0.6px; color: #A0AEC0; font-weight: 700; padding: 10px 20px; text-align: left; background: #FAFBFC; border-bottom: 1px solid #F0F0F2; }
.vtable td { padding: 12px 20px; font-size: 0.83rem; color: #374151; border-bottom: 1px solid #F9FAFB; }
.vtable tr:last-child td { border-bottom: none; }
.vtable tr:hover td { background: #FAFBFC; }
.client-name { font-weight: 600; color: #1A202C; }
.placa { background: #EDF2F7; color: #2D3748; font-family: 'JetBrains Mono', monospace; font-size: 0.68rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; letter-spacing: 1px; margin-left: 5px; }
.chip { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.3px; background: #F3F4F6; color: #6B7280; }
.eye-btn { width: 28px; height: 28px; background: #F3F4F6; border: none; border-radius: 7px; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; color: #6B7280; transition: background 0.12s, color 0.12s; }
.eye-btn:hover { background: #DBEAFE; color: #2563EB; }
.empty-state { text-align: center; padding: 28px 20px; font-size: 0.83rem; color: #A0AEC0; }

.cot-row { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-bottom: 1px solid #F9FAFB; }
.cot-row:last-child { border-bottom: none; }
.cot-left { display: flex; flex-direction: column; gap: 2px; }
.cot-right { display: flex; align-items: center; gap: 12px; }
.cot-orden { font-size: 0.7rem; font-family: 'JetBrains Mono', monospace; color: #9CA3AF; font-weight: 600; }
.cot-cliente { font-size: 0.85rem; font-weight: 700; color: #1A202C; }
.cot-mec { font-size: 0.72rem; color: #9CA3AF; }
.cot-total { font-size: 0.95rem; font-weight: 800; color: #C0192A; font-family: 'JetBrains Mono', monospace; }
.btn-revisar { display: flex; align-items: center; gap: 5px; background: rgba(192,25,42,0.07); color: #C0192A; border: none; border-radius: 8px; padding: 7px 12px; font-size: 0.75rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: background 0.12s; }
.btn-revisar:hover { background: rgba(192,25,42,0.14); }

.income-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; border-bottom: 1px solid #F9FAFB; }
.income-month { font-size: 0.8rem; color: #4A5568; font-family: 'JetBrains Mono', monospace; font-weight: 500; }
.income-val { font-size: 0.85rem; font-weight: 700; color: #059669; }
.income-total-row { display: flex; justify-content: space-between; align-items: center; padding: 13px 20px; background: #FAFBFC; border-top: 2px solid #F0F0F2; }
.income-total-label { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #4A5568; }
.income-total-val { font-size: 1rem; font-weight: 800; color: #C0192A; font-family: 'JetBrains Mono', monospace; }

.garantia-item { padding: 14px 20px; border-bottom: 1px solid #F9FAFB; }
.garantia-item:last-child { border-bottom: none; }
.garantia-name { font-size: 0.85rem; font-weight: 700; color: #1A202C; }
.garantia-sub { font-size: 0.72rem; color: #9CA3AF; margin: 2px 0 7px; font-family: 'JetBrains Mono', monospace; }
.dias-chip { display: inline-flex; align-items: center; gap: 4px; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; }
.dias-green  { background: #F0FDF4; color: #16A34A; }
.dias-orange { background: #FFFBEB; color: #D97706; }

/* ── Mini paginación ── */
.mini-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 4px;
  padding: 12px 20px;
  border-top: 1px solid #F0F0F2;
}
.mini-btn {
  width: 30px; height: 30px;
  border-radius: 7px;
  border: 1.5px solid #E2E8F0;
  background: white;
  color: #4A5568;
  font-size: 0.82rem;
  font-weight: 700;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.12s;
}
.mini-btn:hover:not(:disabled) { border-color: #C0192A; color: #C0192A; }
.mini-btn:disabled { opacity: 0.35; cursor: not-allowed; }
.mini-num {
  width: 30px; height: 30px;
  border-radius: 7px;
  border: 1.5px solid #E2E8F0;
  background: white;
  color: #4A5568;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.12s;
}
.mini-num:hover { border-color: #C0192A; color: #C0192A; }
.mini-active { background: linear-gradient(135deg, #C0192A, #9B1422); border-color: #C0192A; color: white !important; box-shadow: 0 3px 10px rgba(192,25,42,0.3); }

@media (max-width: 960px) {
  .stat-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
  .two-col   { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .dash-page { padding: 16px; }
  .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
</style>