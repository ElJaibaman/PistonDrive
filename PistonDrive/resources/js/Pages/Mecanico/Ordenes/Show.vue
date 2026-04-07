<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orden:             Object,
    estadoConfig:      Object,
    estadosPermitidos: Array,
    todosEstados:      Object,
});

const formEstado = useForm({ estado: props.orden.estado });

function cambiarEstado() {
    formEstado.patch(route('mecanico.ordenes.estado', props.orden.id));
}

const estadoOpciones = computed(() =>
    props.estadosPermitidos.map(e => ({
        value: e,
        label: {
            en_proceso:           'En proceso',
            esperando_aprobacion: 'Enviar a aprobación',
            terminado:            'Marcar como terminado',
        }[e] ?? e,
    }))
);

const cotizacion = computed(() => props.orden.cotizacion);

const puedeCotizar = computed(() =>
    props.orden.estado === 'en_proceso' ||
    (props.orden.estado === 'rechazado' && cotizacion.value?.estado === 'rechazada')
);

const estadoActual = computed(() => props.todosEstados[props.orden.estado]);

const cotizColorMap = {
    pendiente: { bg: 'rgba(237,137,54,0.1)', color: '#C05621', dot: '#ED8936', label: 'Pendiente'  },
    aprobada:  { bg: 'rgba(72,187,120,0.1)', color: '#276749', dot: '#48BB78', label: 'Aprobada'   },
    rechazada: { bg: 'rgba(192,25,42,0.1)',  color: '#9B1422', dot: '#C0192A', label: 'Rechazada'  },
};

const estadoColorMap = {
    pendiente:            { bg: '#F7FAFC', color: '#718096',  dot: '#CBD5E0' },
    en_proceso:           { bg: '#EBF8FF', color: '#2B6CB0',  dot: '#4299E1' },
    esperando_aprobacion: { bg: '#FFFAF0', color: '#C05621',  dot: '#ED8936' },
    aprobado:             { bg: '#F0FFF4', color: '#276749',  dot: '#48BB78' },
    rechazado:            { bg: '#FFF5F5', color: '#9B1422',  dot: '#C0192A' },
    terminado:            { bg: '#E6FFFA', color: '#234E52',  dot: '#38B2AC' },
    entregado:            { bg: '#FAF5FF', color: '#553C9A',  dot: '#805AD5' },
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('mecanico.ordenes.index')" class="breadcrumb-link">← Mis órdenes</Link>
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
                        <p class="header-sub">{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }} · {{ orden.vehiculo.cliente.nombre }}</p>
                    </div>
                </div>
                <Link :href="route('mecanico.ordenes.index')">
                    <button class="btn-back">← Volver</button>
                </Link>
            </div>
            <div class="header-line" />

            <!-- Alertas de cotización -->
            <div class="pd-alerts" v-if="cotizacion">
                <div v-if="cotizacion.estado === 'rechazada'" class="alert alert-error">
                    <div class="alert-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#9B1422" stroke-width="2"/><path d="M15 9l-6 6M9 9l6 6" stroke="#9B1422" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <div class="alert-body">
                        <p class="alert-title">Cotización rechazada</p>
                        <p class="alert-desc" v-if="cotizacion.nota_secretaria">Motivo: <strong>{{ cotizacion.nota_secretaria }}</strong></p>
                        <p class="alert-desc" v-else>Puedes enviar una nueva cotización corregida.</p>
                    </div>
                </div>
                <div v-else-if="cotizacion.estado === 'aprobada'" class="alert alert-success">
                    <div class="alert-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#276749" stroke-width="2"/><path d="M9 12l2 2 4-4" stroke="#276749" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <div class="alert-body">
                        <p class="alert-title">Cotización aprobada</p>
                        <p class="alert-desc" v-if="cotizacion.nota_secretaria">Nota: <strong>{{ cotizacion.nota_secretaria }}</strong></p>
                        <p class="alert-desc" v-else>Puedes continuar con el trabajo.</p>
                    </div>
                </div>
                <div v-else-if="cotizacion.estado === 'pendiente'" class="alert alert-warning">
                    <div class="alert-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="#C05621" stroke-width="2"/><path d="M12 8v4l2 2" stroke="#C05621" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <div class="alert-body">
                        <p class="alert-title">Cotización en revisión</p>
                        <p class="alert-desc">Esperando respuesta de la secretaria.</p>
                    </div>
                </div>
            </div>

            <div class="pd-content">
                <div class="main-grid">

                    <!-- Columna izquierda -->
                    <div class="col-left">

                        <!-- Datos del vehículo -->
                        <div class="info-card mb-5">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(43,108,176,0.1)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#2B6CB0"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                                </div>
                                <span class="info-card-title">Vehículo</span>
                            </div>
                            <div class="info-rows">
                                <div class="info-row"><span class="info-key">Cliente</span><span class="info-val">{{ orden.vehiculo.cliente.nombre }}</span></div>
                                <div class="info-row"><span class="info-key">Teléfono</span><span class="info-val">{{ orden.vehiculo.cliente.telefono ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-key">Vehículo</span><span class="info-val font-bold">{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }} {{ orden.vehiculo.anio }}</span></div>
                                <div class="info-row"><span class="info-key">Placa</span>
                                    <span v-if="orden.vehiculo.placa" class="placa-badge">{{ orden.vehiculo.placa }}</span>
                                    <span v-else class="info-val">—</span>
                                </div>
                                <div class="info-row"><span class="info-key">Color</span><span class="info-val">{{ orden.vehiculo.color ?? '—' }}</span></div>
                                <div class="info-row" v-if="orden.kilometraje"><span class="info-key">Kilometraje</span><span class="info-val">{{ orden.kilometraje }} km</span></div>
                                <div class="info-row" v-if="orden.observaciones"><span class="info-key">Observaciones</span><span class="info-val">{{ orden.observaciones }}</span></div>
                            </div>
                        </div>

                        <!-- Cotización enviada -->
                        <div class="info-card" v-if="cotizacion">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#C0192A"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8" fill="white"/></svg>
                                </div>
                                <span class="info-card-title">Mi cotización</span>
                                <span class="cotiz-badge" :style="{ background: cotizColorMap[cotizacion.estado]?.bg, color: cotizColorMap[cotizacion.estado]?.color }">
                                    <span class="cotiz-dot" :style="{ background: cotizColorMap[cotizacion.estado]?.dot }" />
                                    {{ cotizColorMap[cotizacion.estado]?.label }}
                                </span>
                            </div>

                            <p v-if="cotizacion.nota_mecanico" class="cotiz-nota">{{ cotizacion.nota_mecanico }}</p>

                            <div class="cotiz-table-wrap">
                                <table class="cotiz-table">
                                    <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th class="text-center">Cant.</th>
                                            <th class="text-right">Precio</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in cotizacion.items" :key="item.id">
                                            <td>
                                                <span class="tipo-chip" :class="item.tipo === 'pieza' ? 'tipo-pieza' : 'tipo-mo'">
                                                    {{ item.tipo === 'pieza' ? 'Pieza' : 'Servicio' }}
                                                </span>
                                            </td>
                                            <td class="desc-cell">{{ item.descripcion }}</td>
                                            <td class="text-center">{{ item.cantidad }}</td>
                                            <td class="text-right mono">L. {{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                                            <td class="text-right mono font-bold">L. {{ parseFloat(item.subtotal).toFixed(2) }}</td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr class="foot-row">
                                            <td colspan="4" class="text-right foot-label">Total piezas</td>
                                            <td class="text-right mono">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</td>
                                        </tr>
                                        <tr class="foot-row">
                                            <td colspan="4" class="text-right foot-label">Total mano de obra</td>
                                            <td class="text-right mono">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</td>
                                        </tr>
                                        <tr class="foot-row foot-total">
                                            <td colspan="4" class="text-right">TOTAL GENERAL</td>
                                            <td class="text-right mono">L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-right">

                        <!-- Estado -->
                        <div class="info-card mb-5">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(74,85,104,0.08)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z" stroke="#4A5568" stroke-width="2"/><path d="M12 6v6l4 2" stroke="#4A5568" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <span class="info-card-title">Estado de la orden</span>
                            </div>

                            <div class="estado-actual">
                                <span class="estado-chip-big" :style="{ background: estadoColorMap[orden.estado]?.bg, color: estadoColorMap[orden.estado]?.color }">
                                    <span class="estado-dot" :style="{ background: estadoColorMap[orden.estado]?.dot }" />
                                    {{ estadoActual?.label ?? orden.estado }}
                                </span>
                            </div>

                            <div class="estado-form">
                                <label class="field-label">Cambiar estado</label>
                                <div class="field-wrapper select-wrap">
                                    <select v-model="formEstado.estado" class="pd-select">
                                        <option v-for="op in estadoOpciones" :key="op.value" :value="op.value">{{ op.label }}</option>
                                    </select>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" class="select-arrow"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <button class="btn-update" :disabled="formEstado.processing" @click="cambiarEstado">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="white" stroke-width="2"/><path d="M17 21v-8H7v8M7 3v5h8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                    Actualizar estado
                                </button>
                            </div>
                        </div>

                        <!-- Acción cotizar -->
                        <div class="cotizar-card" v-if="puedeCotizar">
                            <div class="cotizar-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="#C0192A" stroke-width="1.8" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="#C0192A" stroke-width="1.8" stroke-linecap="round"/><line x1="12" y1="18" x2="12" y2="12" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/><line x1="9" y1="15" x2="15" y2="15" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/></svg>
                            </div>
                            <p class="cotizar-title">{{ cotizacion?.estado === 'rechazada' ? 'Enviar nueva cotización' : 'Enviar cotización' }}</p>
                            <p class="cotizar-sub">{{ cotizacion?.estado === 'rechazada' ? 'Tu cotización fue rechazada. Corrígela y envíala de nuevo.' : 'Detalla las piezas y servicios necesarios para el vehículo.' }}</p>
                            <Link :href="route('mecanico.cotizacion.create', orden.id)" style="display:block">
                                <button class="btn-cotizar">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><line x1="22" y1="2" x2="11" y2="13" stroke="white" stroke-width="2" stroke-linecap="round"/><polygon points="22,2 15,22 11,13 2,9" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    {{ cotizacion?.estado === 'rechazada' ? 'Nueva cotización' : 'Crear cotización' }}
                                </button>
                            </Link>
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

.pd-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 32px 0; }
.header-left { display: flex; align-items: center; gap: 14px; }
.header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.header-title { font-size: 1.3rem; font-weight: 800; color: #1A202C; margin: 0; letter-spacing: -0.3px; }
.header-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }
.btn-back { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 16px; height: 40px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-back:hover { border-color: #C0192A; color: #C0192A; }
.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); margin: 16px 32px 0; border-radius: 2px; }

/* Alertas */
.pd-alerts { padding: 16px 32px 0; }
.alert { display: flex; align-items: flex-start; gap: 12px; padding: 14px 18px; border-radius: 12px; border: 1px solid; margin-bottom: 4px; }
.alert-error   { background: rgba(192,25,42,0.05);  border-color: rgba(192,25,42,0.2); }
.alert-success { background: rgba(72,187,120,0.05); border-color: rgba(72,187,120,0.25); }
.alert-warning { background: rgba(237,137,54,0.05); border-color: rgba(237,137,54,0.25); }
.alert-icon { flex-shrink: 0; margin-top: 1px; }
.alert-title { font-size: 0.875rem; font-weight: 700; color: #1A202C; margin: 0 0 3px; }
.alert-desc  { font-size: 0.82rem; color: #718096; margin: 0; }
.alert-body { flex: 1; }

.pd-content { padding: 20px 32px 40px; }
.main-grid { display: grid; grid-template-columns: 1fr 320px; gap: 24px; }
.col-left, .col-right { display: flex; flex-direction: column; }
.mb-5 { margin-bottom: 20px; }

/* Info cards */
.info-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.info-card-header { display: flex; align-items: center; gap: 10px; padding: 16px 20px; border-bottom: 1px solid #EDF2F7; }
.info-icon { width: 30px; height: 30px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.info-card-title { font-size: 0.82rem; font-weight: 700; color: #2D3748; text-transform: uppercase; letter-spacing: 0.5px; flex: 1; }
.info-rows { padding: 8px 0; }
.info-row { display: flex; align-items: flex-start; justify-content: space-between; padding: 8px 20px; gap: 16px; }
.info-row:hover { background: #FAFBFC; }
.info-key { font-size: 0.78rem; color: #A0AEC0; font-weight: 600; flex-shrink: 0; min-width: 100px; }
.info-val { font-size: 0.85rem; color: #2D3748; text-align: right; }
.font-bold { font-weight: 700; }
.placa-badge { background: #EDF2F7; color: #2D3748; font-family: monospace; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 5px; letter-spacing: 1px; }

/* Cotización */
.cotiz-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; }
.cotiz-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.cotiz-nota { font-size: 0.82rem; color: #4A5568; padding: 12px 20px; border-bottom: 1px solid #EDF2F7; margin: 0; background: #FAFBFC; font-style: italic; }
.cotiz-table-wrap { overflow-x: auto; }
.cotiz-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.cotiz-table thead tr { background: #F7FAFC; border-bottom: 1.5px solid #EDF2F7; }
.cotiz-table th { padding: 10px 14px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #A0AEC0; text-align: left; white-space: nowrap; }
.cotiz-table td { padding: 10px 14px; color: #4A5568; border-bottom: 1px solid #F7FAFC; }
.cotiz-table tbody tr:hover { background: #FAFBFC; }
.tipo-chip { padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
.tipo-pieza { background: rgba(43,108,176,0.1); color: #2B6CB0; }
.tipo-mo    { background: rgba(192,25,42,0.1);  color: #9B1422; }
.desc-cell { max-width: 200px; }
.text-center { text-align: center; }
.text-right  { text-align: right; }
.mono { font-family: monospace; font-size: 0.82rem; }
.foot-row td { padding: 8px 14px; border-bottom: 1px solid #EDF2F7; }
.foot-label { color: #4A5568; font-weight: 600; }
.foot-total td { font-size: 0.9rem; font-weight: 800; color: #C0192A; background: rgba(192,25,42,0.04); padding: 12px 14px; }

/* Estado */
.estado-actual { padding: 14px 20px; }
.estado-chip-big { display: inline-flex; align-items: center; gap: 7px; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
.estado-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.estado-form { padding: 0 20px 20px; display: flex; flex-direction: column; gap: 10px; }
.field-label { font-size: 0.75rem; font-weight: 600; color: #4A5568; text-transform: uppercase; letter-spacing: 0.5px; }
.field-wrapper { display: flex; align-items: center; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 12px; height: 42px; }
.field-wrapper:focus-within { border-color: #C0192A; background: white; }
.select-wrap { gap: 4px; }
.pd-select { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; appearance: none; cursor: pointer; }
.select-arrow { flex-shrink: 0; pointer-events: none; }
.btn-update { display: flex; align-items: center; gap: 7px; background: linear-gradient(135deg, #2D3748, #1A202C); color: white; border: none; border-radius: 10px; padding: 0 18px; height: 42px; font-size: 0.82rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: transform 0.15s, opacity 0.15s; }
.btn-update:hover:not(:disabled) { transform: translateY(-1px); }
.btn-update:disabled { opacity: 0.65; cursor: not-allowed; }

/* Cotizar card */
.cotizar-card { background: white; border-radius: 14px; border: 1.5px solid rgba(192,25,42,0.15); overflow: hidden; box-shadow: 0 2px 12px rgba(192,25,42,0.06); padding: 20px; text-align: center; }
.cotizar-icon { width: 52px; height: 52px; background: rgba(192,25,42,0.06); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
.cotizar-title { font-size: 0.9rem; font-weight: 800; color: #1A202C; margin: 0 0 6px; }
.cotizar-sub { font-size: 0.78rem; color: #A0AEC0; margin: 0 0 16px; line-height: 1.5; }
.btn-cotizar { width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 10px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s; }
.btn-cotizar:hover { transform: translateY(-1px); }

@media (max-width: 768px) { .main-grid { grid-template-columns: 1fr; } .pd-page { padding-bottom: 32px; } }
</style>