<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orden:   Object,
    estados: Object,
});

const cotizacion = computed(() => props.orden?.cotizacion ?? null);
const vehiculo   = computed(() => props.orden?.vehiculo ?? null);
const cliente    = computed(() => props.orden?.vehiculo?.cliente ?? null);
const mecanico   = computed(() => props.orden?.mecanico ?? null);

const estadoColorMap = {
    pendiente:            { bg: '#F7FAFC', color: '#718096',  dot: '#CBD5E0' },
    en_proceso:           { bg: '#EBF8FF', color: '#2B6CB0',  dot: '#4299E1' },
    esperando_aprobacion: { bg: '#FFFAF0', color: '#C05621',  dot: '#ED8936' },
    aprobado:             { bg: '#F0FFF4', color: '#276749',  dot: '#48BB78' },
    rechazado:            { bg: '#FFF5F5', color: '#9B1422',  dot: '#C0192A' },
    terminado:            { bg: '#E6FFFA', color: '#234E52',  dot: '#38B2AC' },
    entregado:            { bg: '#FAF5FF', color: '#553C9A',  dot: '#805AD5' },
};

const cotizColorMap = {
    pendiente: { bg: 'rgba(237,137,54,0.1)',  color: '#C05621', dot: '#ED8936', label: 'Pendiente'  },
    aprobada:  { bg: 'rgba(72,187,120,0.1)',  color: '#276749', dot: '#48BB78', label: 'Aprobada'   },
    rechazada: { bg: 'rgba(192,25,42,0.1)',   color: '#9B1422', dot: '#C0192A', label: 'Rechazada'  },
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page" v-if="orden">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('ordenes.index')" class="breadcrumb-link">← Órdenes</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">Orden #{{ orden.id }}</span>
            </div>

            <!-- Header -->
            <div class="pd-header">
                <div class="header-left">
                    <div class="header-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="header-title">Orden de trabajo #{{ orden.id }}</h1>
                        <p class="header-sub" v-if="vehiculo && cliente">
                            {{ vehiculo.marca }} {{ vehiculo.modelo }} · {{ cliente.nombre }}
                        </p>
                    </div>
                </div>
                <div class="header-actions">
                    <!-- Revisar cotización pendiente -->
                    <Link v-if="cotizacion?.estado === 'pendiente'" :href="route('secretaria.cotizaciones.show', { cotizacion: cotizacion.id })">
                        <button class="btn-warning">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="white" stroke-width="2" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                            Revisar cotización
                        </button>
                    </Link>
                    <!-- Generar ticket -->
                    <Link v-if="orden.estado === 'terminado' && !orden.ticket" 
                      :href="route('tickets.create', { orden_id: orden.id })">
                      <button class="btn-success">Generar ticket</button>
                    </Link>
                    <Link :href="route('ordenes.edit', orden.id)">
                        <button class="btn-edit">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                            Editar
                        </button>
                    </Link>
                    <Link :href="route('ordenes.index')">
                        <button class="btn-back">← Volver</button>
                    </Link>
                </div>
            </div>
            <div class="header-line" />

            <!-- Estado banner -->
            <div class="pd-alerts">
                <div class="estado-banner" :style="{ background: estadoColorMap[orden.estado]?.bg, border: '1px solid ' + estadoColorMap[orden.estado]?.dot + '40' }">
                    <span class="estado-dot-big" :style="{ background: estadoColorMap[orden.estado]?.dot }" />
                    <span class="estado-label" :style="{ color: estadoColorMap[orden.estado]?.color }">
                        {{ estados[orden.estado]?.label ?? orden.estado }}
                    </span>
                    <span class="estado-fecha" v-if="orden.fecha_ingreso">· Ingreso: {{ orden.fecha_ingreso }}</span>
                </div>
            </div>

            <div class="pd-content">
                <div class="main-grid">

                    <!-- Columna izquierda -->
                    <div class="col-left">

                        <!-- Datos vehículo/cliente -->
                        <div class="info-card mb-4" v-if="vehiculo && cliente">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#C0192A"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
                                </div>
                                <span class="info-card-title">Cliente y vehículo</span>
                            </div>
                            <div class="info-grid">
                                <div class="info-item"><span class="info-key">Cliente</span><span class="info-val bold">{{ cliente.nombre }}</span></div>
                                <div class="info-item"><span class="info-key">Teléfono</span><span class="info-val">{{ cliente.telefono ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Vehículo</span><span class="info-val bold">{{ vehiculo.marca }} {{ vehiculo.modelo }}</span></div>
                                <div class="info-item"><span class="info-key">Placa</span>
                                    <span v-if="vehiculo.placa" class="placa-badge">{{ vehiculo.placa }}</span>
                                    <span v-else class="info-val">—</span>
                                </div>
                                <div class="info-item"><span class="info-key">Color</span><span class="info-val">{{ vehiculo.color ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Año</span><span class="info-val">{{ vehiculo.anio ?? '—' }}</span></div>
                            </div>
                        </div>

                        <!-- Cotización -->
                        <div class="cotiz-card" v-if="cotizacion">
                            <div class="cotiz-bar" :style="{ background: cotizColorMap[cotizacion.estado]?.dot }" />
                            <div class="cotiz-header">
                                <div class="cotiz-title-wrap">
                                    <span class="info-card-title">Cotización del mecánico</span>
                                    <span class="cotiz-chip" :style="{ background: cotizColorMap[cotizacion.estado]?.bg, color: cotizColorMap[cotizacion.estado]?.color }">
                                        <span class="cotiz-dot" :style="{ background: cotizColorMap[cotizacion.estado]?.dot }" />
                                        {{ cotizColorMap[cotizacion.estado]?.label }}
                                    </span>
                                </div>
                            </div>
                            <div class="cotiz-totals">
                                <div class="cotiz-total-item">
                                    <span class="cotiz-total-label">Piezas</span>
                                    <span class="cotiz-total-val">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</span>
                                </div>
                                <div class="cotiz-total-div" />
                                <div class="cotiz-total-item">
                                    <span class="cotiz-total-label">Mano de obra</span>
                                    <span class="cotiz-total-val">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</span>
                                </div>
                                <div class="cotiz-total-div" />
                                <div class="cotiz-total-item">
                                    <span class="cotiz-total-label">Total</span>
                                    <span class="cotiz-total-main">L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}</span>
                                </div>
                            </div>
                            <div class="cotiz-notas" v-if="cotizacion.nota_mecanico || cotizacion.nota_secretaria">
                                <div class="nota-row" v-if="cotizacion.nota_mecanico">
                                    <span class="nota-key">Nota mecánico:</span>
                                    <span class="nota-val">{{ cotizacion.nota_mecanico }}</span>
                                </div>
                                <div class="nota-row" v-if="cotizacion.nota_secretaria">
                                    <span class="nota-key">Nota secretaria:</span>
                                    <span class="nota-val">{{ cotizacion.nota_secretaria }}</span>
                                </div>
                            </div>
                            <div class="cotiz-footer">
                                <Link :href="route('secretaria.cotizaciones.show', { cotizacion: cotizacion.id })">
                                    <button class="btn-ver-cotiz">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                                        Ver detalle completo
                                    </button>
                                </Link>
                            </div>
                        </div>

                        <!-- Sin cotización -->
                        <div class="no-cotiz" v-else>
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#CBD5E0" stroke-width="1.5" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="#CBD5E0" stroke-width="1.5" stroke-linecap="round"/></svg>
                            <span>Sin cotización enviada</span>
                        </div>

                    </div>

                    <!-- Columna derecha -->
                    <div class="col-right">
                        <div class="info-card">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(74,85,104,0.08)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="#4A5568" stroke-width="1.5" stroke-linecap="round"/></svg>
                                </div>
                                <span class="info-card-title">Datos de la orden</span>
                            </div>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-key">Mecánico</span>
                                    <div v-if="mecanico" class="mecanico-wrap">
                                        <div class="mecanico-av">{{ mecanico.nombre.split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase() }}</div>
                                        <span class="info-val bold">{{ mecanico.nombre }}</span>
                                    </div>
                                    <span v-else class="info-val placeholder">Sin asignar</span>
                                </div>
                                <div class="info-item"><span class="info-key">Ingreso</span><span class="info-val mono">{{ orden.fecha_ingreso }}</span></div>
                                <div class="info-item"><span class="info-key">Prometido</span><span class="info-val mono" :class="{ placeholder: !orden.fecha_prometida }">{{ orden.fecha_prometida ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Entrega</span><span class="info-val mono" :class="{ placeholder: !orden.fecha_entrega }">{{ orden.fecha_entrega ?? '—' }}</span></div>
                                <div class="info-item" v-if="orden.kilometraje"><span class="info-key">Kilometraje</span><span class="info-val">{{ orden.kilometraje }} km</span></div>
                            </div>
                            <div class="obs-box" v-if="orden.observaciones">
                                <span class="obs-label">Observaciones</span>
                                <p class="obs-text">{{ orden.observaciones }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.pd-page { min-height: 100vh; background: #F7F8FA; padding: 0; }

.pd-breadcrumb { display: flex; align-items: center; gap: 8px; padding: 20px 32px 0; font-size: 0.82rem; }
.breadcrumb-link { color: #C0192A; text-decoration: none; font-weight: 600; transition: opacity 0.15s; }
.breadcrumb-link:hover { opacity: 0.75; }
.breadcrumb-sep { color: #CBD5E0; }
.breadcrumb-current { color: #4A5568; font-weight: 500; }

.pd-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 32px 0; flex-wrap: wrap; gap: 12px; }
.header-left { display: flex; align-items: center; gap: 14px; }
.header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.header-title { font-size: 1.3rem; font-weight: 800; color: #1A202C; margin: 0; letter-spacing: -0.3px; }
.header-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }
.header-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); margin: 16px 32px 0; border-radius: 2px; }

.btn-back { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.btn-back:hover { border-color: #C0192A; color: #C0192A; }
.btn-edit { display: flex; align-items: center; gap: 6px; background: rgba(74,85,104,0.08); color: #4A5568; border: none; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s; }
.btn-edit:hover { background: rgba(74,85,104,0.15); }
.btn-warning { display: flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #ED8936, #C05621); color: white; border: none; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 3px 10px rgba(237,137,54,0.35); transition: transform 0.15s; }
.btn-warning:hover { transform: translateY(-1px); }
.btn-success { display: flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #38A169, #276749); color: white; border: none; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 3px 10px rgba(56,161,105,0.35); transition: transform 0.15s; }
.btn-success:hover { transform: translateY(-1px); }

.pd-alerts { padding: 16px 32px 0; }
.estado-banner { display: inline-flex; align-items: center; gap: 10px; padding: 10px 18px; border-radius: 12px; }
.estado-dot-big { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.estado-label { font-size: 0.875rem; font-weight: 700; }
.estado-fecha { font-size: 0.78rem; color: #A0AEC0; }

.pd-content { padding: 20px 32px 40px; }
.main-grid { display: grid; grid-template-columns: 1fr 300px; gap: 24px; }
.col-left, .col-right { display: flex; flex-direction: column; }
.mb-4 { margin-bottom: 16px; }

.info-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.info-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-bottom: 1px solid #EDF2F7; }
.info-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.info-card-title { font-size: 0.78rem; font-weight: 700; color: #2D3748; text-transform: uppercase; letter-spacing: 0.5px; }
.info-grid { display: grid; grid-template-columns: 1fr 1fr; }
.info-item { display: flex; flex-direction: column; padding: 10px 20px; border-bottom: 1px solid #F7FAFC; }
.info-key { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
.info-val { font-size: 0.875rem; color: #2D3748; }
.bold { font-weight: 700; }
.mono { font-family: monospace; font-size: 0.82rem; }
.placeholder { color: #E2E8F0 !important; }
.placa-badge { background: #EDF2F7; color: #2D3748; font-family: monospace; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 5px; letter-spacing: 1px; display: inline-block; }
.mecanico-wrap { display: flex; align-items: center; gap: 7px; }
.mecanico-av { width: 24px; height: 24px; border-radius: 6px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.55rem; font-weight: 800; color: white; flex-shrink: 0; }
.obs-box { padding: 12px 20px; border-top: 1px solid #EDF2F7; background: #FAFBFC; }
.obs-label { font-size: 0.7rem; color: #A0AEC0; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px; }
.obs-text { font-size: 0.82rem; color: #4A5568; margin: 0; line-height: 1.6; }

.cotiz-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.cotiz-bar { height: 3px; }
.cotiz-header { padding: 14px 20px; border-bottom: 1px solid #EDF2F7; }
.cotiz-title-wrap { display: flex; align-items: center; gap: 10px; }
.cotiz-chip { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
.cotiz-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.cotiz-totals { display: flex; align-items: center; padding: 16px 20px; gap: 0; }
.cotiz-total-item { flex: 1; text-align: center; }
.cotiz-total-label { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px; }
.cotiz-total-val { font-size: 0.875rem; font-weight: 700; color: #2D3748; font-family: monospace; }
.cotiz-total-main { font-size: 1rem; font-weight: 800; color: #C0192A; font-family: monospace; }
.cotiz-total-div { width: 1px; height: 36px; background: #EDF2F7; }
.cotiz-notas { padding: 12px 20px; border-top: 1px solid #EDF2F7; background: #FAFBFC; display: flex; flex-direction: column; gap: 6px; }
.nota-row { display: flex; gap: 8px; font-size: 0.82rem; }
.nota-key { color: #A0AEC0; font-weight: 600; white-space: nowrap; }
.nota-val { color: #4A5568; }
.cotiz-footer { padding: 12px 20px; border-top: 1px solid #EDF2F7; }
.btn-ver-cotiz { display: flex; align-items: center; gap: 6px; background: rgba(74,85,104,0.08); color: #4A5568; border: none; border-radius: 8px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s; }
.btn-ver-cotiz:hover { background: rgba(74,85,104,0.15); }

.no-cotiz { display: flex; align-items: center; gap: 10px; background: white; border-radius: 14px; border: 1.5px dashed #E2E8F0; padding: 20px; color: #CBD5E0; font-size: 0.875rem; }

@media (max-width: 768px) { .main-grid { grid-template-columns: 1fr; } .info-grid { grid-template-columns: 1fr; } .pd-page { padding-bottom: 32px; } }
</style>