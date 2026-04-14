<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    cotizaciones: { type: Object, default: () => ({ data: [] }) },
    totalCotizaciones: Number,
    totalPendientes: Number,
    totalAprobadas: Number,
    totalRechazadas: Number,
});

const busqueda = ref('');

const estadoCot = {
    pendiente: { bg: 'rgba(237,137,54,0.1)',  color: '#C05621', dot: '#ED8936', label: 'Pendiente' },
    aprobada:  { bg: 'rgba(72,187,120,0.1)',  color: '#276749', dot: '#48BB78', label: 'Aprobada'  },
    rechazada: { bg: 'rgba(192,25,42,0.1)',   color: '#9B1422', dot: '#C0192A', label: 'Rechazada' },
};

const filtrados = computed(() => {
    if (!busqueda.value) return props.cotizaciones.data;
    const q = busqueda.value.toLowerCase();
    return props.cotizaciones.data.filter(c =>
        c.orden.vehiculo.cliente.nombre.toLowerCase().includes(q) ||
        c.orden.vehiculo.marca.toLowerCase().includes(q) ||
        (c.orden.mecanico?.nombre?.toLowerCase().includes(q)) ||
        String(c.id).includes(q)
    );
});

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">
            <div class="pd-hero">
                <div class="hero-content">
                    <div class="hero-left">
                        <div class="hero-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                                <polyline points="14,2 14,8 20,8" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                                <line x1="16" y1="13" x2="8" y2="13" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                                <line x1="16" y1="17" x2="8" y2="17" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="hero-title">Cotizaciones</h1>
                            <p class="hero-sub">Revisión de cotizaciones enviadas por mecánicos</p>
                        </div>
                    </div>
                    <div class="pending-badge" v-if="totalPendientes > 0">
                        <div class="pending-dot" />
                        {{ totalPendientes }} pendiente{{ totalPendientes !== 1 ? 's' : '' }} de revisión
                    </div>
                </div>
                <div class="hero-stats">
                    <div class="stat-pill"><div class="stat-dot" style="background:#C0192A" /><span class="stat-num">{{ totalCotizaciones }}</span><span class="stat-label">Total</span></div>
                    <div class="stat-divider" />
                    <div class="stat-pill"><div class="stat-dot" style="background:#ED8936" /><span class="stat-num">{{ totalPendientes }}</span><span class="stat-label">Pendientes</span></div>
                    <div class="stat-divider" />
                    <div class="stat-pill"><div class="stat-dot" style="background:#48BB78" /><span class="stat-num">{{ totalAprobadas }}</span><span class="stat-label">Aprobadas</span></div>
                    <div class="stat-divider" />
                    <div class="stat-pill"><div class="stat-dot" style="background:#C0192A" /><span class="stat-num">{{ totalRechazadas }}</span><span class="stat-label">Rechazadas</span></div>
                </div>
            </div>

            <div class="pd-toolbar">
                <div class="search-wrap">
                    <svg class="search-ico" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="8" stroke="#A0AEC0" stroke-width="1.8"/><path d="m21 21-4.35-4.35" stroke="#A0AEC0" stroke-width="1.8" stroke-linecap="round"/></svg>
                    <input v-model="busqueda" class="search-input" placeholder="Buscar por cliente, vehículo, mecánico o ID..." />
                    <button v-if="busqueda" class="search-clear" @click="busqueda = ''">✕</button>
                </div>
                <div class="results-badge" v-if="busqueda">{{ filtrados.length }} resultado{{ filtrados.length !== 1 ? 's' : '' }}</div>
            </div>

            <div class="pd-table-wrap">
                <div class="table-top-bar" />
                <table class="pd-table">
                    <thead>
                        <tr>
                            <th>#</th><th>Orden</th><th>Cliente</th><th>Vehículo</th><th>Mecánico</th>
                            <th class="text-right">Piezas</th><th class="text-right">Mano obra</th>
                            <th class="text-right">Total</th><th>Estado</th><th class="col-center">Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in filtrados" :key="c.id" class="pd-row" :class="{ 'row-pending': c.estado === 'pendiente' }">
                            <td class="cell-id"><span class="id-badge">#{{ c.id }}</span></td>
                            <td class="cell-data"><span class="orden-badge">#{{ c.orden.id }}</span></td>
                            <td class="cell-data">
                                <div class="cliente-wrap">
                                    <div class="cliente-av">{{ iniciales(c.orden.vehiculo.cliente.nombre) }}</div>
                                    <span class="cliente-nombre">{{ c.orden.vehiculo.cliente.nombre }}</span>
                                </div>
                            </td>
                            <td class="cell-data">
                                <div class="vehiculo-wrap">
                                    <div class="vehiculo-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="#9B1422"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg></div>
                                    <span class="vehiculo-nombre">{{ c.orden.vehiculo.marca }} {{ c.orden.vehiculo.modelo }}</span>
                                </div>
                            </td>
                            <td class="cell-data">
                                <div class="mecanico-wrap" v-if="c.orden.mecanico">
                                    <div class="mecanico-av">{{ iniciales(c.orden.mecanico.nombre) }}</div>
                                    <span class="mecanico-nombre">{{ c.orden.mecanico.nombre }}</span>
                                </div>
                                <span v-else class="data-empty">—</span>
                            </td>
                            <td class="cell-data text-right"><span class="amount-text">L. {{ parseFloat(c.total_piezas).toFixed(2) }}</span></td>
                            <td class="cell-data text-right"><span class="amount-text">L. {{ parseFloat(c.total_mano_obra).toFixed(2) }}</span></td>
                            <td class="cell-data text-right"><span class="total-amount">L. {{ parseFloat(c.total_general).toFixed(2) }}</span></td>
                            <td class="cell-data">
                                <span class="estado-chip" :style="{ background: estadoCot[c.estado]?.bg, color: estadoCot[c.estado]?.color }">
                                    <span class="estado-dot" :style="{ background: estadoCot[c.estado]?.dot }" />
                                    {{ estadoCot[c.estado]?.label }}
                                </span>
                            </td>
                            <td class="cell-center">
                                <Link :href="route('secretaria.cotizaciones.show', c.id)">
                                    <button class="btn-action" :class="c.estado === 'pendiente' ? 'btn-review' : 'btn-view'">
                                        <svg v-if="c.estado === 'pendiente'" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                        <svg v-else width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                                    </button>
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="filtrados.length === 0">
                            <td colspan="10" class="empty-cell">
                                <div class="empty-wrap">
                                    <div class="empty-icon-ring"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#CBD5E0" stroke-width="1.5" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="#CBD5E0" stroke-width="1.5" stroke-linecap="round"/></svg></div>
                                    <p class="empty-title">No hay cotizaciones</p>
                                    <p class="empty-sub">Las cotizaciones de los mecánicos aparecerán aquí</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Paginación -->
                <div class="pagination-wrap" v-if="cotizaciones.last_page > 1">
                    <button class="page-btn" :disabled="cotizaciones.current_page === 1" @click="router.get(cotizaciones.prev_page_url)">← Anterior</button>
                    <div class="page-numbers">
                        <button v-for="p in cotizaciones.last_page" :key="p" class="page-num" :class="{ 'page-num-active': p === cotizaciones.current_page }" @click="router.get(cotizaciones.path + '?page=' + p)">{{ p }}</button>
                    </div>
                    <button class="page-btn" :disabled="cotizaciones.current_page === cotizaciones.last_page" @click="router.get(cotizaciones.next_page_url)">Siguiente →</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.pd-page { min-height: 100vh; background: #F7F8FA; padding: 0; }
.pd-hero { background: white; border-bottom: 1px solid #EDF2F7; padding: 28px 32px 0; }
.hero-content { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.hero-left { display: flex; align-items: center; gap: 16px; }
.hero-icon-wrap { width: 48px; height: 48px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 18px rgba(192,25,42,0.35); flex-shrink: 0; }
.hero-title { font-size: 1.55rem; font-weight: 800; color: #1A202C; margin: 0; letter-spacing: -0.5px; }
.hero-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }
.pending-badge { display: flex; align-items: center; gap: 8px; background: rgba(237,137,54,0.1); border: 1px solid rgba(237,137,54,0.3); color: #C05621; font-size: 0.82rem; font-weight: 700; padding: 8px 16px; border-radius: 20px; }
.pending-dot { width: 8px; height: 8px; border-radius: 50%; background: #ED8936; animation: pulse 1.5s infinite; flex-shrink: 0; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
.hero-stats { display: flex; align-items: center; border-top: 1px solid #EDF2F7; }
.stat-pill { display: flex; align-items: center; gap: 8px; padding: 14px 24px 14px 0; margin-right: 24px; }
.stat-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.stat-num { font-size: 1.1rem; font-weight: 800; color: #2D3748; }
.stat-label { font-size: 0.78rem; color: #A0AEC0; font-weight: 500; }
.stat-divider { width: 1px; height: 20px; background: #EDF2F7; margin-right: 24px; }
.pd-toolbar { padding: 20px 32px; display: flex; align-items: center; gap: 16px; }
.search-wrap { position: relative; display: flex; align-items: center; max-width: 440px; flex: 1; }
.search-ico { position: absolute; left: 14px; pointer-events: none; }
.search-input { width: 100%; height: 44px; background: white; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 0 40px 0 42px; font-size: 0.875rem; color: #2D3748; outline: none; font-family: inherit; transition: border-color 0.15s, box-shadow 0.15s; }
.search-input::placeholder { color: #CBD5E0; }
.search-input:focus { border-color: #C0192A; box-shadow: 0 0 0 3px rgba(192,25,42,0.1); }
.search-clear { position: absolute; right: 12px; background: none; border: none; color: #A0AEC0; cursor: pointer; font-size: 0.75rem; padding: 4px; }
.results-badge { background: rgba(192,25,42,0.08); color: #C0192A; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 8px; white-space: nowrap; }
.pd-table-wrap { margin: 0 32px 32px; background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.05); }
.table-top-bar { height: 4px; background: linear-gradient(to right, #C0192A 0%, #9B1422 45%, #2D3748 100%); }
.pd-table { width: 100%; border-collapse: collapse; }
.pd-table thead tr { background: #F7FAFC; border-bottom: 1.5px solid #EDF2F7; }
.pd-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.9px; color: #718096; padding: 13px 16px; text-align: left; white-space: nowrap; }
.col-center { text-align: center !important; }
.text-right { text-align: right !important; }
.pd-row { border-bottom: 1px solid #F7FAFC; transition: background 0.12s; }
.pd-row:last-child { border-bottom: none; }
.pd-row:hover { background: #FFF5F5; }
.pd-row:hover .id-badge { color: #C0192A; background: rgba(192,25,42,0.08); }
.row-pending { border-left: 3px solid #ED8936; }
.cell-id { padding: 14px 16px; }
.id-badge { font-size: 0.75rem; font-weight: 700; color: #CBD5E0; background: #F7FAFC; padding: 3px 8px; border-radius: 6px; transition: all 0.12s; }
.cell-data { padding: 13px 16px; font-size: 0.875rem; color: #4A5568; }
.cell-center { text-align: center; padding: 13px 16px; }
.data-empty { color: #E2E8F0; font-size: 0.82rem; }
.orden-badge { background: #EDF2F7; color: #2D3748; font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; font-family: monospace; }
.cliente-wrap { display: flex; align-items: center; gap: 10px; }
.cliente-av { width: 30px; height: 30px; border-radius: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); display: flex; align-items: center; justify-content: center; font-size: 0.62rem; font-weight: 800; color: white; flex-shrink: 0; }
.cliente-nombre { font-weight: 600; color: #2D3748; font-size: 0.85rem; }
.vehiculo-wrap { display: flex; align-items: center; gap: 8px; }
.vehiculo-icon { width: 26px; height: 26px; background: rgba(192,25,42,0.06); border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vehiculo-nombre { font-weight: 600; color: #1A202C; font-size: 0.85rem; }
.mecanico-wrap { display: flex; align-items: center; gap: 8px; }
.mecanico-av { width: 26px; height: 26px; border-radius: 6px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.58rem; font-weight: 800; color: white; flex-shrink: 0; }
.mecanico-nombre { font-size: 0.85rem; color: #4A5568; }
.amount-text { font-size: 0.82rem; color: #718096; font-family: monospace; }
.total-amount { font-size: 0.9rem; font-weight: 800; color: #2D3748; font-family: monospace; }
.estado-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
.estado-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.btn-action { width: 32px; height: 32px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.12s, transform 0.1s; margin: 0 auto; }
.btn-action:hover { transform: scale(1.08); }
.btn-view { background: rgba(45,55,72,0.08); color: #4A5568; }
.btn-view:hover { background: rgba(45,55,72,0.15); }
.btn-review { background: rgba(237,137,54,0.1); color: #C05621; }
.btn-review:hover { background: rgba(237,137,54,0.18); }
.empty-cell { padding: 80px 16px !important; }
.empty-wrap { text-align: center; }
.empty-icon-ring { width: 72px; height: 72px; border-radius: 50%; background: #F7FAFC; border: 1.5px dashed #E2E8F0; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.empty-title { font-size: 0.9rem; font-weight: 600; color: #A0AEC0; margin: 0 0 4px; }
.empty-sub { font-size: 0.8rem; color: #CBD5E0; margin: 0; }
.pagination-wrap { display: flex; align-items: center; justify-content: center; gap: 12px; padding: 16px 24px; border-top: 1px solid #EDF2F7; }
.page-btn { background: white; border: 1.5px solid #E2E8F0; color: #4A5568; border-radius: 10px; padding: 0 18px; height: 38px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.page-btn:hover:not(:disabled) { border-color: #C0192A; color: #C0192A; }
.page-btn:disabled { opacity: 0.4; cursor: not-allowed; }
.page-numbers { display: flex; gap: 4px; }
.page-num { width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #E2E8F0; background: white; color: #4A5568; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.page-num:hover { border-color: #C0192A; color: #C0192A; }
.page-num-active { background: linear-gradient(135deg, #C0192A, #9B1422); border-color: #C0192A; color: white; box-shadow: 0 4px 12px rgba(192,25,42,0.35); }
.page-num-active:hover { color: white; }
</style>