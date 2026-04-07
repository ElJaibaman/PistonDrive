<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    ordenes:  Array,
    mecanico: Object,
});

const estadoConfig = {
    pendiente:            { bg: '#F7FAFC', color: '#718096',  dot: '#CBD5E0', label: 'Pendiente'            },
    en_proceso:           { bg: '#EBF8FF', color: '#2B6CB0',  dot: '#4299E1', label: 'En proceso'           },
    esperando_aprobacion: { bg: '#FFFAF0', color: '#C05621',  dot: '#ED8936', label: 'Esp. aprobación'      },
    aprobado:             { bg: '#F0FFF4', color: '#276749',  dot: '#48BB78', label: 'Aprobado'             },
    rechazado:            { bg: '#FFF5F5', color: '#9B1422',  dot: '#C0192A', label: 'Rechazado'            },
    terminado:            { bg: '#E6FFFA', color: '#234E52',  dot: '#38B2AC', label: 'Terminado'            },
    entregado:            { bg: '#FAF5FF', color: '#553C9A',  dot: '#805AD5', label: 'Entregado'            },
};

const cotizacionConfig = {
    pendiente: { bg: 'rgba(237,137,54,0.1)',  color: '#C05621', label: 'Pendiente'  },
    aprobada:  { bg: 'rgba(72,187,120,0.1)',  color: '#276749', label: 'Aprobada'   },
    rechazada: { bg: 'rgba(192,25,42,0.1)',   color: '#9B1422', label: 'Rechazada'  },
};

const resumen = computed(() => {
    const r = {};
    for (const k of Object.keys(estadoConfig)) {
        r[k] = props.ordenes.filter(o => o.estado === k).length;
    }
    return r;
});

function canCotizar(orden) {
    return orden.estado === 'en_proceso' ||
        (orden.estado === 'rechazado' && orden.cotizacion?.estado === 'rechazada') ||
        orden.estado === 'aprobado';
}

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Hero del mecánico -->
            <div class="pd-hero">
                <div class="hero-content">
                    <div class="hero-left">
                        <div class="mec-avatar">{{ iniciales(mecanico?.nombre ?? 'M') }}</div>
                        <div>
                            <p class="hero-greeting">Bienvenido,</p>
                            <h1 class="hero-title">{{ mecanico?.nombre ?? 'Mecánico' }}</h1>
                            <p class="hero-sub">{{ ordenes.length }} orden{{ ordenes.length !== 1 ? 'es' : '' }} asignada{{ ordenes.length !== 1 ? 's' : '' }}</p>
                        </div>
                    </div>
                    <div class="hero-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="#C0192A" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        Mecánico
                    </div>
                </div>

                <!-- Mini stats por estado -->
                <div class="hero-stats">
                    <div v-for="(cfg, key) in estadoConfig" :key="key" class="mini-stat" v-show="resumen[key] > 0 || key === 'en_proceso' || key === 'pendiente'">
                        <div class="mini-dot" :style="{ background: cfg.dot }" />
                        <span class="mini-num">{{ resumen[key] }}</span>
                        <span class="mini-label">{{ cfg.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Tabla de órdenes -->
            <div class="pd-content">
                <div class="section-title-row">
                    <h2 class="section-title">Mis órdenes</h2>
                </div>

                <div class="pd-table-wrap">
                    <div class="table-top-bar" />
                    <table class="pd-table">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Placa</th>
                                <th>Ingreso</th>
                                <th>Estado</th>
                                <th>Cotización</th>
                                <th class="col-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="orden in ordenes" :key="orden.id" class="pd-row">
                                <td class="cell-id">
                                    <span class="id-badge">#{{ orden.id }}</span>
                                </td>
                                <td class="cell-data">
                                    <div class="cliente-wrap">
                                        <div class="cliente-av">{{ iniciales(orden.vehiculo.cliente.nombre) }}</div>
                                        <span class="cliente-nombre">{{ orden.vehiculo.cliente.nombre }}</span>
                                    </div>
                                </td>
                                <td class="cell-data">
                                    <div class="vehiculo-wrap">
                                        <div class="vehiculo-icon">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="#9B1422"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                                        </div>
                                        <span class="vehiculo-nombre">{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</span>
                                    </div>
                                </td>
                                <td class="cell-data">
                                    <span class="placa-badge" v-if="orden.vehiculo.placa">{{ orden.vehiculo.placa }}</span>
                                    <span class="data-empty" v-else>S/P</span>
                                </td>
                                <td class="cell-data">
                                    <span class="fecha-text">{{ orden.fecha_ingreso }}</span>
                                </td>
                                <td class="cell-data">
                                    <span class="estado-chip" :style="{ background: estadoConfig[orden.estado]?.bg, color: estadoConfig[orden.estado]?.color }">
                                        <span class="estado-dot" :style="{ background: estadoConfig[orden.estado]?.dot }" />
                                        {{ estadoConfig[orden.estado]?.label }}
                                    </span>
                                </td>
                                <td class="cell-data">
                                    <span v-if="orden.cotizacion" class="cotiz-chip" :style="{ background: cotizacionConfig[orden.cotizacion.estado]?.bg, color: cotizacionConfig[orden.cotizacion.estado]?.color }">
                                        {{ cotizacionConfig[orden.cotizacion.estado]?.label }}
                                    </span>
                                    <span v-else class="data-empty">Sin cotización</span>
                                </td>
                                <td class="cell-center">
                                    <div class="actions-wrap">
                                        <Link :href="route('mecanico.ordenes.show', orden.id)">
                                            <button class="btn-action btn-view" title="Ver detalle">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                                            </button>
                                        </Link>
                                        <Link v-if="canCotizar(orden)" :href="route('mecanico.cotizacion.create', orden.id)">
                                            <button class="btn-action btn-cotiz" title="Enviar cotización">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="12" y1="18" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="9" y1="15" x2="15" y2="15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                            </button>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="ordenes.length === 0">
                                <td colspan="8" class="empty-cell">
                                    <div class="empty-wrap">
                                        <div class="empty-icon-ring">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="#CBD5E0" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        </div>
                                        <p class="empty-title">No tienes órdenes asignadas</p>
                                        <p class="empty-sub">Cuando la secretaria te asigne una orden aparecerá aquí</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.pd-page { min-height: 100vh; background: #F7F8FA; padding: 0; }

/* Hero */
.pd-hero { background: white; border-bottom: 1px solid #EDF2F7; padding: 28px 32px 0; }
.hero-content { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.hero-left { display: flex; align-items: center; gap: 16px; }
.mec-avatar { width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #C0192A, #9B1422); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: white; box-shadow: 0 6px 18px rgba(192,25,42,0.35); flex-shrink: 0; letter-spacing: 1px; }
.hero-greeting { font-size: 0.78rem; color: #A0AEC0; font-weight: 500; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; }
.hero-title { font-size: 1.55rem; font-weight: 800; color: #1A202C; margin: 2px 0; letter-spacing: -0.5px; }
.hero-sub { font-size: 0.8rem; color: #A0AEC0; margin: 0; }
.hero-badge { display: flex; align-items: center; gap: 6px; background: rgba(192,25,42,0.08); color: #C0192A; font-size: 0.78rem; font-weight: 700; padding: 8px 16px; border-radius: 20px; border: 1px solid rgba(192,25,42,0.15); }

.hero-stats { display: flex; align-items: center; gap: 24px; border-top: 1px solid #EDF2F7; padding: 12px 0; flex-wrap: wrap; }
.mini-stat { display: flex; align-items: center; gap: 6px; }
.mini-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.mini-num { font-size: 0.9rem; font-weight: 800; color: #2D3748; }
.mini-label { font-size: 0.75rem; color: #A0AEC0; font-weight: 500; }

/* Content */
.pd-content { padding: 24px 32px 32px; }
.section-title-row { margin-bottom: 16px; }
.section-title { font-size: 1rem; font-weight: 700; color: #2D3748; margin: 0; }

/* Tabla */
.pd-table-wrap { background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.05); }
.table-top-bar { height: 4px; background: linear-gradient(to right, #C0192A 0%, #9B1422 45%, #2D3748 100%); }
.pd-table { width: 100%; border-collapse: collapse; }
.pd-table thead tr { background: #F7FAFC; border-bottom: 1.5px solid #EDF2F7; }
.pd-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.9px; color: #718096; padding: 13px 16px; text-align: left; white-space: nowrap; }
.col-center { text-align: center !important; }
.pd-row { border-bottom: 1px solid #F7FAFC; transition: background 0.12s; }
.pd-row:last-child { border-bottom: none; }
.pd-row:hover { background: #FFF5F5; }
.pd-row:hover .id-badge { color: #C0192A; background: rgba(192,25,42,0.08); }

.cell-id { padding: 14px 16px; }
.id-badge { font-size: 0.75rem; font-weight: 700; color: #CBD5E0; background: #F7FAFC; padding: 3px 8px; border-radius: 6px; transition: all 0.12s; }
.cell-data { padding: 13px 16px; font-size: 0.875rem; color: #4A5568; }
.cell-center { text-align: center; padding: 13px 16px; }
.data-empty { color: #E2E8F0; font-size: 0.82rem; }

.cliente-wrap { display: flex; align-items: center; gap: 10px; }
.cliente-av { width: 30px; height: 30px; border-radius: 8px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.62rem; font-weight: 800; color: white; flex-shrink: 0; }
.cliente-nombre { font-weight: 600; color: #2D3748; font-size: 0.85rem; }

.vehiculo-wrap { display: flex; align-items: center; gap: 8px; }
.vehiculo-icon { width: 28px; height: 28px; background: rgba(192,25,42,0.06); border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vehiculo-nombre { font-weight: 600; color: #1A202C; font-size: 0.85rem; }

.placa-badge { background: #EDF2F7; color: #2D3748; font-size: 0.75rem; font-weight: 700; padding: 3px 8px; border-radius: 5px; letter-spacing: 1px; font-family: monospace; }
.fecha-text { font-size: 0.82rem; color: #718096; }

.estado-chip { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; white-space: nowrap; }
.estado-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

.cotiz-chip { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }

.actions-wrap { display: flex; align-items: center; justify-content: center; gap: 6px; }
.btn-action { width: 32px; height: 32px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.12s, transform 0.1s; }
.btn-action:hover { transform: scale(1.08); }
.btn-view { background: rgba(45,55,72,0.08); color: #4A5568; }
.btn-view:hover { background: rgba(45,55,72,0.15); }
.btn-cotiz { background: rgba(192,25,42,0.08); color: #C0192A; }
.btn-cotiz:hover { background: rgba(192,25,42,0.15); }

.empty-cell { padding: 80px 16px !important; }
.empty-wrap { text-align: center; }
.empty-icon-ring { width: 72px; height: 72px; border-radius: 50%; background: #F7FAFC; border: 1.5px dashed #E2E8F0; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.empty-title { font-size: 0.9rem; font-weight: 600; color: #A0AEC0; margin: 0 0 4px; }
.empty-sub { font-size: 0.8rem; color: #CBD5E0; margin: 0; }
</style>