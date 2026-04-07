<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({ cotizacion: Object });

const dialogAprobar  = ref(false);
const dialogRechazar = ref(false);

const formAprobar  = useForm({ nota_secretaria: '' });
const formRechazar = useForm({ nota_secretaria: '' });

function aprobar() {
    formAprobar.patch(route('secretaria.cotizaciones.aprobar', props.cotizacion.id), {
        onSuccess: () => { dialogAprobar.value = false; },
    });
}
function rechazar() {
    formRechazar.patch(route('secretaria.cotizaciones.rechazar', props.cotizacion.id), {
        onSuccess: () => { dialogRechazar.value = false; },
    });
}

const esPendiente = computed(() => props.cotizacion.estado === 'pendiente');
const orden = computed(() => props.cotizacion.orden);

const estadoConfig = {
    pendiente: { bg: 'rgba(237,137,54,0.08)', border: 'rgba(237,137,54,0.3)', color: '#C05621', dot: '#ED8936', label: 'Pendiente de revisión' },
    aprobada:  { bg: 'rgba(72,187,120,0.08)', border: 'rgba(72,187,120,0.3)', color: '#276749', dot: '#48BB78', label: 'Aprobada'              },
    rechazada: { bg: 'rgba(192,25,42,0.08)',  border: 'rgba(192,25,42,0.25)', color: '#9B1422', dot: '#C0192A', label: 'Rechazada'             },
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('secretaria.cotizaciones.index')" class="breadcrumb-link">← Cotizaciones</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">Cotización #{{ cotizacion.id }}</span>
            </div>

            <!-- Header -->
            <div class="pd-header">
                <div class="header-left">
                    <div class="header-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            <polyline points="14,2 14,8 20,8" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="header-title">Cotización #{{ cotizacion.id }} — Orden #{{ orden.id }}</h1>
                        <p class="header-sub">{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }} · {{ orden.vehiculo.cliente.nombre }}</p>
                    </div>
                </div>
                <Link :href="route('secretaria.cotizaciones.index')">
                    <button class="btn-back">← Volver</button>
                </Link>
            </div>
            <div class="header-line" />

            <!-- Banner de estado -->
            <div class="pd-alerts">
                <div class="estado-banner" :style="{ background: estadoConfig[cotizacion.estado]?.bg, border: '1px solid ' + estadoConfig[cotizacion.estado]?.border }">
                    <div class="banner-dot" :style="{ background: estadoConfig[cotizacion.estado]?.dot }" />
                    <div class="banner-body">
                        <span class="banner-title" :style="{ color: estadoConfig[cotizacion.estado]?.color }">
                            Cotización {{ estadoConfig[cotizacion.estado]?.label }}
                        </span>
                        <span class="banner-note" v-if="cotizacion.nota_secretaria && cotizacion.estado !== 'pendiente'">
                            · {{ cotizacion.nota_secretaria }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="pd-content">
                <div class="main-grid">

                    <!-- Columna izquierda -->
                    <div class="col-left">

                        <!-- Datos del servicio -->
                        <div class="info-card mb-5">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(43,108,176,0.1)">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#2B6CB0"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
                                </div>
                                <span class="info-card-title">Datos del servicio</span>
                            </div>
                            <div class="service-grid">
                                <div class="service-item">
                                    <span class="service-key">Cliente</span>
                                    <span class="service-val font-bold">{{ orden.vehiculo.cliente.nombre }}</span>
                                </div>
                                <div class="service-item">
                                    <span class="service-key">Teléfono</span>
                                    <span class="service-val">{{ orden.vehiculo.cliente.telefono ?? '—' }}</span>
                                </div>
                                <div class="service-item">
                                    <span class="service-key">Vehículo</span>
                                    <span class="service-val font-bold">{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</span>
                                </div>
                                <div class="service-item">
                                    <span class="service-key">Placa</span>
                                    <span v-if="orden.vehiculo.placa" class="placa-badge">{{ orden.vehiculo.placa }}</span>
                                    <span v-else class="service-val">—</span>
                                </div>
                                <div class="service-item">
                                    <span class="service-key">Mecánico</span>
                                    <span class="service-val">{{ orden.mecanico?.nombre ?? '—' }}</span>
                                </div>
                            </div>
                            <div v-if="cotizacion.nota_mecanico" class="nota-mecanico">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="#A0AEC0"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <span><strong>Nota del mecánico:</strong> {{ cotizacion.nota_mecanico }}</span>
                            </div>
                        </div>

                        <!-- Piezas -->
                        <div class="cotiz-card mb-5">
                            <div class="cotiz-card-bar" style="background:#2B6CB0" />
                            <div class="cotiz-card-header">
                                <div class="cotiz-icon" style="background:rgba(43,108,176,0.1)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#2B6CB0"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                </div>
                                <span class="cotiz-section-title">Piezas / Repuestos</span>
                                <span class="cotiz-count">{{ cotizacion.piezas?.length ?? 0 }} ítem{{ (cotizacion.piezas?.length ?? 0) !== 1 ? 's' : '' }}</span>
                            </div>
                            <table class="cotiz-table">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th class="text-center">Cant.</th>
                                        <th class="text-right">Precio unit.</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in cotizacion.piezas" :key="item.id" class="cotiz-row">
                                        <td>{{ item.descripcion }}</td>
                                        <td class="text-center">{{ item.cantidad }}</td>
                                        <td class="text-right mono">L. {{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                                        <td class="text-right mono font-bold">L. {{ parseFloat(item.subtotal).toFixed(2) }}</td>
                                    </tr>
                                    <tr v-if="!cotizacion.piezas?.length">
                                        <td colspan="4" class="text-center text-empty py-3">Sin piezas registradas</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="foot-row">
                                        <td colspan="3" class="text-right foot-label">Subtotal piezas</td>
                                        <td class="text-right mono" style="color:#2B6CB0;font-weight:800">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Mano de obra -->
                        <div class="cotiz-card mb-5">
                            <div class="cotiz-card-bar" style="background:#C0192A" />
                            <div class="cotiz-card-header">
                                <div class="cotiz-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#C0192A"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                </div>
                                <span class="cotiz-section-title">Mano de obra</span>
                                <span class="cotiz-count">{{ cotizacion.mano_obra?.length ?? 0 }} ítem{{ (cotizacion.mano_obra?.length ?? 0) !== 1 ? 's' : '' }}</span>
                            </div>
                            <table class="cotiz-table">
                                <thead>
                                    <tr>
                                        <th>Servicio</th>
                                        <th class="text-center">Cant.</th>
                                        <th class="text-right">Monto</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in cotizacion.mano_obra" :key="item.id" class="cotiz-row">
                                        <td>{{ item.descripcion }}</td>
                                        <td class="text-center">{{ item.cantidad }}</td>
                                        <td class="text-right mono">L. {{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                                        <td class="text-right mono font-bold">L. {{ parseFloat(item.subtotal).toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="foot-row">
                                        <td colspan="3" class="text-right foot-label">Subtotal mano de obra</td>
                                        <td class="text-right mono" style="color:#C0192A;font-weight:800">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>

                    <!-- Columna derecha -->
                    <div class="col-right">

                        <!-- Total card -->
                        <div class="total-card mb-5">
                            <div class="total-bar" />
                            <div class="total-body">
                                <div class="total-row">
                                    <span>Piezas</span>
                                    <span class="mono">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</span>
                                </div>
                                <div class="total-row">
                                    <span>Mano de obra</span>
                                    <span class="mono">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</span>
                                </div>
                                <div class="total-sep" />
                                <div class="total-row total-main">
                                    <span>TOTAL</span>
                                    <span class="mono">L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="action-card" v-if="esPendiente">
                            <p class="action-title">Decisión</p>
                            <p class="action-sub">Consulta con el cliente y decide si aprobar o rechazar esta cotización.</p>
                            <button class="btn-aprobar" @click="dialogAprobar = true">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2.5" stroke-linecap="round"/></svg>
                                Aprobar cotización
                            </button>
                            <button class="btn-rechazar" @click="dialogRechazar = true">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                Rechazar cotización
                            </button>
                        </div>

                        <!-- Ya decidido -->
                        <div class="decided-card" v-else>
                            <div class="decided-icon" :style="{ background: estadoConfig[cotizacion.estado]?.bg }">
                                <svg v-if="cotizacion.estado === 'aprobada'" width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#48BB78" stroke-width="2.5" stroke-linecap="round"/></svg>
                                <svg v-else width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="#C0192A" stroke-width="2.5" stroke-linecap="round"/></svg>
                            </div>
                            <p class="decided-label" :style="{ color: estadoConfig[cotizacion.estado]?.color }">
                                Cotización {{ cotizacion.estado }}
                            </p>
                            <p class="decided-note" v-if="cotizacion.nota_secretaria">{{ cotizacion.nota_secretaria }}</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Diálogo Aprobar -->
        <v-dialog v-model="dialogAprobar" max-width="440">
            <div class="modal-card">
                <div class="modal-bar" style="background:linear-gradient(to right, #38A169, #276749)" />
                <div class="modal-body">
                    <div class="modal-icon-ring" style="background:rgba(72,187,120,0.08);border-color:rgba(72,187,120,0.2)">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#48BB78" stroke-width="2"/>
                            <path d="M9 12l2 2 4-4" stroke="#48BB78" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">Aprobar cotización</h3>
                    <p class="modal-desc">El mecánico será notificado y podrá continuar con el trabajo.</p>
                    <div class="modal-field">
                        <label class="modal-label">Nota para el mecánico <span style="color:#CBD5E0">(opcional)</span></label>
                        <div class="modal-textarea-wrap">
                            <textarea v-model="formAprobar.nota_secretaria" class="modal-textarea" rows="3" placeholder="Ej. Procede con las piezas aprobadas..." />
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button class="modal-cancel" @click="dialogAprobar = false">Cancelar</button>
                        <button class="modal-confirm modal-confirm-green" :disabled="formAprobar.processing" @click="aprobar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="white" stroke-width="2.5" stroke-linecap="round"/></svg>
                            {{ formAprobar.processing ? 'Aprobando...' : 'Confirmar aprobación' }}
                        </button>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Diálogo Rechazar -->
        <v-dialog v-model="dialogRechazar" max-width="440">
            <div class="modal-card">
                <div class="modal-bar" style="background:linear-gradient(to right, #C0192A, #9B1422)" />
                <div class="modal-body">
                    <div class="modal-icon-ring" style="background:rgba(192,25,42,0.06);border-color:rgba(192,25,42,0.15)">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#C0192A" stroke-width="2"/>
                            <path d="M15 9l-6 6M9 9l6 6" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">Rechazar cotización</h3>
                    <p class="modal-desc">El mecánico deberá enviar una nueva cotización corregida.</p>
                    <div class="modal-field">
                        <label class="modal-label">Motivo del rechazo <span class="required">*</span></label>
                        <div class="modal-textarea-wrap" :class="{ 'modal-textarea-error': formRechazar.errors.nota_secretaria }">
                            <textarea v-model="formRechazar.nota_secretaria" class="modal-textarea" rows="3" placeholder="Explica por qué se rechaza la cotización..." />
                        </div>
                        <span v-if="formRechazar.errors.nota_secretaria" class="error-msg">{{ formRechazar.errors.nota_secretaria }}</span>
                    </div>
                    <div class="modal-actions">
                        <button class="modal-cancel" @click="dialogRechazar = false">Cancelar</button>
                        <button class="modal-confirm modal-confirm-red" :disabled="formRechazar.processing" @click="rechazar">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2.5" stroke-linecap="round"/></svg>
                            {{ formRechazar.processing ? 'Rechazando...' : 'Confirmar rechazo' }}
                        </button>
                    </div>
                </div>
            </div>
        </v-dialog>

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

.pd-alerts { padding: 16px 32px 0; }
.estado-banner { display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 12px; margin-bottom: 4px; }
.banner-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.banner-title { font-size: 0.875rem; font-weight: 700; }
.banner-note { font-size: 0.82rem; color: #718096; margin-left: 4px; }
.banner-body { display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }

.pd-content { padding: 20px 32px 40px; }
.main-grid { display: grid; grid-template-columns: 1fr 280px; gap: 24px; }
.col-left, .col-right { display: flex; flex-direction: column; }
.mb-5 { margin-bottom: 20px; }

/* Info card */
.info-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.info-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-bottom: 1px solid #EDF2F7; }
.info-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.info-card-title { font-size: 0.78rem; font-weight: 700; color: #2D3748; text-transform: uppercase; letter-spacing: 0.5px; }
.service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.service-item { display: flex; flex-direction: column; padding: 10px 20px; border-bottom: 1px solid #F7FAFC; }
.service-key { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
.service-val { font-size: 0.875rem; color: #2D3748; }
.font-bold { font-weight: 700; }
.placa-badge { background: #EDF2F7; color: #2D3748; font-family: monospace; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 5px; letter-spacing: 1px; display: inline-block; }
.nota-mecanico { display: flex; align-items: flex-start; gap: 8px; padding: 12px 20px; background: #FAFBFC; border-top: 1px solid #EDF2F7; font-size: 0.82rem; color: #4A5568; }

/* Cotiz cards */
.cotiz-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.cotiz-card-bar { height: 3px; }
.cotiz-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #EDF2F7; }
.cotiz-icon { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cotiz-section-title { font-size: 0.78rem; font-weight: 700; color: #2D3748; text-transform: uppercase; letter-spacing: 0.5px; flex: 1; }
.cotiz-count { font-size: 0.7rem; background: #EDF2F7; color: #718096; padding: 2px 8px; border-radius: 10px; font-weight: 600; }
.cotiz-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.cotiz-table thead tr { background: #F7FAFC; border-bottom: 1px solid #EDF2F7; }
.cotiz-table th { padding: 9px 16px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #A0AEC0; text-align: left; }
.cotiz-table td { padding: 10px 16px; color: #4A5568; border-bottom: 1px solid #F7FAFC; }
.cotiz-row:hover { background: #FAFBFC; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.mono { font-family: monospace; }
.text-empty { color: #E2E8F0; }
.foot-row td { padding: 10px 16px; background: #F7FAFC; border-top: 1px solid #EDF2F7; }
.foot-label { color: #4A5568; font-weight: 600; font-size: 0.82rem; }

/* Total card */
.total-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.total-bar { height: 4px; background: linear-gradient(to right, #C0192A, #9B1422 50%, #2D3748); }
.total-body { padding: 18px 20px; }
.total-row { display: flex; justify-content: space-between; font-size: 0.875rem; color: #4A5568; margin-bottom: 8px; }
.total-sep { height: 1px; background: #EDF2F7; margin: 8px 0; }
.total-main { font-size: 1rem; font-weight: 800; color: #C0192A; margin-bottom: 0; }

/* Action card */
.action-card { background: white; border-radius: 14px; border: 1.5px solid rgba(192,25,42,0.12); padding: 20px; box-shadow: 0 2px 12px rgba(192,25,42,0.05); }
.action-title { font-size: 0.9rem; font-weight: 800; color: #1A202C; margin: 0 0 6px; }
.action-sub { font-size: 0.78rem; color: #A0AEC0; margin: 0 0 18px; line-height: 1.5; }
.btn-aprobar { width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #38A169, #276749); color: white; border: none; border-radius: 10px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 14px rgba(56,161,105,0.3); transition: transform 0.15s; margin-bottom: 10px; }
.btn-aprobar:hover { transform: translateY(-1px); }
.btn-rechazar { width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: white; color: #C0192A; border: 1.5px solid rgba(192,25,42,0.25); border-radius: 10px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: background 0.15s, border-color 0.15s; }
.btn-rechazar:hover { background: rgba(192,25,42,0.05); border-color: #C0192A; }

.decided-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; padding: 24px 20px; text-align: center; }
.decided-icon { width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px; }
.decided-label { font-size: 0.9rem; font-weight: 700; margin: 0 0 6px; text-transform: capitalize; }
.decided-note { font-size: 0.78rem; color: #718096; margin: 0; }

/* Modales */
.modal-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
.modal-bar { height: 4px; }
.modal-body { padding: 28px 32px 32px; text-align: center; }
.modal-icon-ring { width: 68px; height: 68px; border-radius: 50%; border: 1.5px solid; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
.modal-title { font-size: 1.1rem; font-weight: 800; color: #1A202C; margin: 0 0 8px; }
.modal-desc { font-size: 0.82rem; color: #718096; margin: 0 0 20px; }
.modal-field { text-align: left; margin-bottom: 24px; }
.modal-label { font-size: 0.78rem; font-weight: 600; color: #4A5568; display: block; margin-bottom: 6px; }
.required { color: #C0192A; }
.modal-textarea-wrap { background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden; transition: border-color 0.15s; }
.modal-textarea-wrap:focus-within { border-color: #C0192A; background: white; }
.modal-textarea-error { border-color: #E53E3E !important; }
.modal-textarea { width: 100%; border: none; outline: none; padding: 12px 14px; font-size: 0.875rem; color: #2D3748; font-family: inherit; resize: none; background: transparent; line-height: 1.6; }
.modal-textarea::placeholder { color: #CBD5E0; }
.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; display: block; }
.modal-actions { display: flex; gap: 12px; justify-content: center; }
.modal-cancel { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.modal-cancel:hover { border-color: #4A5568; color: #2D3748; }
.modal-confirm { display: flex; align-items: center; gap: 7px; border: none; border-radius: 10px; padding: 0 22px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: transform 0.15s; }
.modal-confirm:hover:not(:disabled) { transform: translateY(-1px); }
.modal-confirm:disabled { opacity: 0.65; cursor: not-allowed; }
.modal-confirm-green { background: linear-gradient(135deg, #38A169, #276749); color: white; box-shadow: 0 4px 14px rgba(56,161,105,0.3); }
.modal-confirm-red { background: linear-gradient(135deg, #C0192A, #9B1422); color: white; box-shadow: 0 4px 14px rgba(192,25,42,0.35); }

@media (max-width: 768px) { .main-grid { grid-template-columns: 1fr; } .service-grid { grid-template-columns: 1fr; } .pd-page { padding-bottom: 32px; } }
</style>