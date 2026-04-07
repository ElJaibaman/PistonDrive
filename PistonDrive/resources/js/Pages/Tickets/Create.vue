<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ orden: Object });

const cotizacion = computed(() => props.orden?.cotizacion ?? null);

const subtotalRepuestos = computed(() => parseFloat(cotizacion.value?.total_piezas ?? 0));
const subtotalServicios = computed(() => parseFloat(cotizacion.value?.total_mano_obra ?? 0));
const totalAuto         = computed(() => subtotalRepuestos.value + subtotalServicios.value);

const form = useForm({
    orden_id:           props.orden.id,
    subtotal_servicios: subtotalServicios.value,
    subtotal_repuestos: subtotalRepuestos.value,
    total:              totalAuto.value,
    metodo_pago:        'efectivo',
    notas:              '',
});

const metodoPagoConfig = {
    efectivo:      { label: 'Efectivo',      icon: '💵' },
    tarjeta:       { label: 'Tarjeta',       icon: '💳' },
    transferencia: { label: 'Transferencia', icon: '🏦' },
};

function submit() {
    form.post(route('tickets.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('ordenes.index')" class="breadcrumb-link">← Órdenes</Link>
                <span class="breadcrumb-sep">/</span>
                <Link :href="route('ordenes.show', orden.id)" class="breadcrumb-link">Orden #{{ orden.id }}</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">Generar ticket</span>
            </div>

            <!-- Header -->
            <div class="pd-header">
                <div class="header-left">
                    <div class="header-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            <polyline points="14,2 14,8 20,8" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            <line x1="16" y1="13" x2="8" y2="13" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            <line x1="16" y1="17" x2="8" y2="17" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="header-title">Generar ticket — Orden #{{ orden.id }}</h1>
                        <p class="header-sub">{{ orden.vehiculo?.marca }} {{ orden.vehiculo?.modelo }} · {{ orden.vehiculo?.cliente?.nombre }}</p>
                    </div>
                </div>
                <Link :href="route('ordenes.show', orden.id)">
                    <button class="btn-back">← Volver</button>
                </Link>
            </div>
            <div class="header-line" />

            <div class="pd-content">
                <div class="main-grid">

                    <!-- Formulario -->
                    <div class="col-form">

                        <!-- Resumen de la orden -->
                        <div class="info-card mb-5">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#C0192A"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
                                </div>
                                <span class="info-card-title">Datos del servicio</span>
                            </div>
                            <div class="info-grid">
                                <div class="info-item"><span class="info-key">Cliente</span><span class="info-val bold">{{ orden.vehiculo?.cliente?.nombre }}</span></div>
                                <div class="info-item"><span class="info-key">Teléfono</span><span class="info-val">{{ orden.vehiculo?.cliente?.telefono ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Vehículo</span><span class="info-val bold">{{ orden.vehiculo?.marca }} {{ orden.vehiculo?.modelo }}</span></div>
                                <div class="info-item"><span class="info-key">Placa</span>
                                    <span v-if="orden.vehiculo?.placa" class="placa-badge">{{ orden.vehiculo.placa }}</span>
                                    <span v-else class="info-val">—</span>
                                </div>
                                <div class="info-item"><span class="info-key">Mecánico</span><span class="info-val">{{ orden.mecanico?.nombre ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Fecha ingreso</span><span class="info-val mono">{{ orden.fecha_ingreso }}</span></div>
                            </div>
                        </div>

                        <!-- Totales de la cotización -->
                        <div class="cotiz-resumen mb-5" v-if="cotizacion">
                            <div class="cotiz-resumen-bar" />
                            <div class="cotiz-resumen-body">
                                <div class="cotiz-resumen-title">Cotización aprobada</div>
                                <div class="cotiz-resumen-rows">
                                    <div class="cotiz-resumen-row">
                                        <span>Piezas / Repuestos</span>
                                        <span class="mono">L. {{ subtotalRepuestos.toFixed(2) }}</span>
                                    </div>
                                    <div class="cotiz-resumen-row">
                                        <span>Mano de obra</span>
                                        <span class="mono">L. {{ subtotalServicios.toFixed(2) }}</span>
                                    </div>
                                    <div class="cotiz-sep" />
                                    <div class="cotiz-resumen-row cotiz-total-row">
                                        <span>Total calculado</span>
                                        <span class="mono">L. {{ totalAuto.toFixed(2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario ticket -->
                        <div class="form-card">

                            <!-- Método de pago -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-dot" />
                                    <span class="section-title">Método de pago</span>
                                </div>
                                <div class="pago-grid">
                                    <button
                                        v-for="(cfg, key) in metodoPagoConfig"
                                        :key="key"
                                        type="button"
                                        class="pago-btn"
                                        :class="{ 'pago-btn-active': form.metodo_pago === key }"
                                        @click="form.metodo_pago = key"
                                    >
                                        <span class="pago-icon">{{ cfg.icon }}</span>
                                        <span class="pago-label">{{ cfg.label }}</span>
                                    </button>
                                </div>
                                <span v-if="form.errors.metodo_pago" class="error-msg">{{ form.errors.metodo_pago }}</span>
                            </div>

                            <div class="form-sep" />

                            <!-- Ajuste de totales -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-dot" style="background:#4A5568" />
                                    <span class="section-title">Totales</span>
                                    <span class="section-optional">Ajustables si es necesario</span>
                                </div>
                                <div class="form-grid-3">
                                    <div class="field-group">
                                        <label class="field-label">Subtotal repuestos (L.)</label>
                                        <div class="field-wrapper" :class="{ 'field-error': form.errors.subtotal_repuestos }">
                                            <span class="field-prefix">L.</span>
                                            <input v-model="form.subtotal_repuestos" type="number" step="0.01" min="0" class="pd-input" />
                                        </div>
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Subtotal servicios (L.)</label>
                                        <div class="field-wrapper" :class="{ 'field-error': form.errors.subtotal_servicios }">
                                            <span class="field-prefix">L.</span>
                                            <input v-model="form.subtotal_servicios" type="number" step="0.01" min="0" class="pd-input" />
                                        </div>
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Total a cobrar (L.) <span class="required">*</span></label>
                                        <div class="field-wrapper field-total" :class="{ 'field-error': form.errors.total }">
                                            <span class="field-prefix">L.</span>
                                            <input v-model="form.total" type="number" step="0.01" min="0" class="pd-input" />
                                        </div>
                                        <span v-if="form.errors.total" class="error-msg">{{ form.errors.total }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-sep" />

                            <!-- Notas -->
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-dot" style="background:#CBD5E0" />
                                    <span class="section-title">Notas del ticket</span>
                                    <span class="section-optional">Opcional</span>
                                </div>
                                <div class="note-wrapper">
                                    <textarea v-model="form.notas" class="note-input" rows="3" placeholder="Observaciones para el cliente, garantías adicionales..." />
                                </div>
                            </div>

                            <div class="form-sep" />

                            <!-- Garantía info -->
                            <div class="garantia-banner">
                                <div class="garantia-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="#48BB78" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <div>
                                    <p class="garantia-title">Garantía automática de 1 mes</p>
                                    <p class="garantia-sub">Se generará automáticamente al crear el ticket.</p>
                                </div>
                            </div>

                            <div class="form-sep" />

                            <!-- Acciones -->
                            <div class="form-actions">
                                <button class="btn-generar" :disabled="form.processing" @click="submit">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="1.8" stroke-linecap="round"/><polyline points="14,2 14,8 20,8" stroke="white" stroke-width="1.8" stroke-linecap="round"/><path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                    {{ form.processing ? 'Generando...' : 'Generar ticket y marcar entregado' }}
                                </button>
                                <Link :href="route('ordenes.show', orden.id)">
                                    <button class="btn-cancel" type="button">Cancelar</button>
                                </Link>
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

.pd-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 32px 0; }
.header-left { display: flex; align-items: center; gap: 14px; }
.header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.header-title { font-size: 1.3rem; font-weight: 800; color: #1A202C; margin: 0; }
.header-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }
.btn-back { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 16px; height: 40px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.btn-back:hover { border-color: #C0192A; color: #C0192A; }
.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); margin: 16px 32px 0; border-radius: 2px; }

.pd-content { padding: 24px 32px 40px; }
.main-grid { max-width: 760px; }
.mb-5 { margin-bottom: 20px; }

/* Info card */
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
.placa-badge { background: #EDF2F7; color: #2D3748; font-family: monospace; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 5px; letter-spacing: 1px; display: inline-block; }

/* Cotizacion resumen */
.cotiz-resumen { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.cotiz-resumen-bar { height: 3px; background: linear-gradient(to right, #48BB78, #276749); }
.cotiz-resumen-body { padding: 16px 20px; }
.cotiz-resumen-title { font-size: 0.78rem; font-weight: 700; color: #2D3748; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; }
.cotiz-resumen-rows { display: flex; flex-direction: column; gap: 8px; }
.cotiz-resumen-row { display: flex; justify-content: space-between; font-size: 0.875rem; color: #4A5568; }
.cotiz-sep { height: 1px; background: #EDF2F7; }
.cotiz-total-row { font-size: 1rem; font-weight: 800; color: #C0192A; }

/* Form */
.form-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; box-shadow: 0 4px 24px rgba(0,0,0,0.05); overflow: hidden; }
.form-section { padding: 20px 24px; }
.form-sep { height: 1px; background: #EDF2F7; }
.section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.section-dot { width: 8px; height: 8px; border-radius: 50%; background: #C0192A; flex-shrink: 0; }
.section-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4A5568; }
.section-optional { font-size: 0.7rem; color: #CBD5E0; }

/* Método de pago */
.pago-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.pago-btn { display: flex; flex-direction: column; align-items: center; gap: 6px; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 12px; padding: 14px 10px; cursor: pointer; font-family: inherit; transition: all 0.15s; }
.pago-btn:hover { border-color: #C0192A; background: rgba(192,25,42,0.03); }
.pago-btn-active { border-color: #C0192A; background: rgba(192,25,42,0.06); box-shadow: 0 0 0 3px rgba(192,25,42,0.1); }
.pago-icon { font-size: 1.4rem; }
.pago-label { font-size: 0.8rem; font-weight: 700; color: #2D3748; }

/* Campos */
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
.field-group { display: flex; flex-direction: column; }
.field-label { font-size: 0.78rem; font-weight: 600; color: #4A5568; margin-bottom: 6px; }
.required { color: #C0192A; }
.field-wrapper { display: flex; align-items: center; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; height: 44px; overflow: hidden; transition: border-color 0.15s; }
.field-wrapper:focus-within { border-color: #C0192A; background: white; }
.field-wrapper.field-error { border-color: #E53E3E; }
.field-total { border-color: rgba(192,25,42,0.3); background: rgba(192,25,42,0.03); }
.field-total:focus-within { border-color: #C0192A; background: white; }
.field-prefix { padding: 0 10px; font-size: 0.78rem; font-weight: 700; color: #A0AEC0; background: #EDF2F7; height: 100%; display: flex; align-items: center; border-right: 1px solid #E2E8F0; white-space: nowrap; }
.pd-input { flex: 1; background: transparent; border: none; outline: none; padding: 0 10px; font-size: 0.875rem; color: #2D3748; font-family: inherit; }
.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; }

/* Nota */
.note-wrapper { background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; overflow: hidden; transition: border-color 0.15s; }
.note-wrapper:focus-within { border-color: #4A5568; background: white; }
.note-input { width: 100%; border: none; outline: none; padding: 12px 14px; font-size: 0.875rem; color: #2D3748; font-family: inherit; resize: none; background: transparent; line-height: 1.6; }
.note-input::placeholder { color: #CBD5E0; }

/* Garantía banner */
.garantia-banner { display: flex; align-items: center; gap: 14px; margin: 0 24px; padding: 14px 18px; background: rgba(72,187,120,0.06); border: 1px solid rgba(72,187,120,0.2); border-radius: 12px; }
.garantia-icon { width: 38px; height: 38px; background: rgba(72,187,120,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.garantia-title { font-size: 0.875rem; font-weight: 700; color: #276749; margin: 0 0 2px; }
.garantia-sub { font-size: 0.78rem; color: #68D391; margin: 0; }

/* Acciones */
.form-actions { padding: 20px 24px; display: flex; align-items: center; gap: 12px; background: #FAFBFC; border-top: 1px solid #EDF2F7; }
.btn-generar { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 10px; padding: 0 24px; height: 46px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s; }
.btn-generar:hover:not(:disabled) { transform: translateY(-1px); }
.btn-generar:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-cancel { background: transparent; color: #718096; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 20px; height: 46px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.btn-cancel:hover { border-color: #C0192A; color: #C0192A; }

@media (max-width: 768px) { .info-grid, .form-grid-3, .pago-grid { grid-template-columns: 1fr; } .pd-page { padding-bottom: 32px; } }
</style>