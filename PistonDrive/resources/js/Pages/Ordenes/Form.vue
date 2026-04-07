<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orden:     Object,
    vehiculos: Array,
    mecanicos: Array,
    estados:   Object,
});

const form = useForm({
    vehiculo_id:     props.orden?.vehiculo_id     ?? '',
    mecanico_id:     props.orden?.mecanico_id     ?? '',
    fecha_ingreso:   props.orden?.fecha_ingreso   ?? new Date().toISOString().slice(0, 10),
    fecha_prometida: props.orden?.fecha_prometida ?? '',
    estado:          props.orden?.estado          ?? 'pendiente',
    kilometraje:     props.orden?.kilometraje     ?? '',
    observaciones:   props.orden?.observaciones   ?? '',
});

const isEdit = computed(() => !!props.orden);
const estadosSecretaria = ['pendiente', 'en_proceso'];

const estadosDisponibles = computed(() => {
    const keys = isEdit.value ? Object.keys(props.estados) : estadosSecretaria;
    return keys.map(k => ({ value: k, label: props.estados[k]?.label ?? k }));
});

const vehiculosFormateados = computed(() =>
    props.vehiculos.map(v => ({
        id: v.id,
        label: `${v.cliente.nombre} — ${v.marca} ${v.modelo}${v.placa ? ' (' + v.placa + ')' : ''}`,
    }))
);

const vehiculoSeleccionado = computed(() =>
    props.vehiculos.find(v => v.id == form.vehiculo_id)
);

const mecanicoSeleccionado = computed(() =>
    props.mecanicos.find(m => m.id == form.mecanico_id)
);

const estadoColorMap = {
    pendiente:            { bg: '#F7FAFC', color: '#718096',  dot: '#CBD5E0' },
    en_proceso:           { bg: '#EBF8FF', color: '#2B6CB0',  dot: '#4299E1' },
    esperando_aprobacion: { bg: '#FFFAF0', color: '#C05621',  dot: '#ED8936' },
    aprobado:             { bg: '#F0FFF4', color: '#276749',  dot: '#48BB78' },
    rechazado:            { bg: '#FFF5F5', color: '#9B1422',  dot: '#C0192A' },
    terminado:            { bg: '#E6FFFA', color: '#234E52',  dot: '#38B2AC' },
    entregado:            { bg: '#FAF5FF', color: '#553C9A',  dot: '#805AD5' },
};

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}

function submit() {
    if (isEdit.value) form.put(route('ordenes.update', props.orden.id));
    else form.post(route('ordenes.store'));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('ordenes.index')" class="breadcrumb-link">← Órdenes</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ isEdit ? 'Editar orden #' + orden.id : 'Nueva orden' }}</span>
            </div>

            <div class="pd-layout">

                <!-- Preview sidebar -->
                <div class="pd-sidebar">
                    <div class="preview-card">
                        <div class="preview-hero">
                            <div class="preview-order-num">
                                {{ isEdit ? '#' + orden.id : 'Nueva' }}
                            </div>
                        </div>
                        <div class="preview-body">

                            <!-- Estado -->
                            <div class="preview-estado" :style="{ background: estadoColorMap[form.estado]?.bg, color: estadoColorMap[form.estado]?.color }">
                                <span class="preview-estado-dot" :style="{ background: estadoColorMap[form.estado]?.dot }" />
                                {{ estadosDisponibles.find(e => e.value === form.estado)?.label ?? form.estado }}
                            </div>

                            <div class="preview-divider" />

                            <!-- Vehículo -->
                            <div class="preview-section-label">Vehículo</div>
                            <div v-if="vehiculoSeleccionado" class="preview-item">
                                <div class="preview-item-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#C0192A"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
                                </div>
                                <div>
                                    <div class="preview-item-main">{{ vehiculoSeleccionado.marca }} {{ vehiculoSeleccionado.modelo }}</div>
                                    <div class="preview-item-sub">{{ vehiculoSeleccionado.cliente.nombre }}</div>
                                </div>
                            </div>
                            <div class="preview-placeholder-row" v-else>Sin vehículo seleccionado</div>

                            <div class="preview-divider" />

                            <!-- Mecánico -->
                            <div class="preview-section-label">Mecánico</div>
                            <div v-if="mecanicoSeleccionado" class="preview-item">
                                <div class="preview-mecanico-av">{{ iniciales(mecanicoSeleccionado.nombre) }}</div>
                                <div>
                                    <div class="preview-item-main">{{ mecanicoSeleccionado.nombre }}</div>
                                </div>
                            </div>
                            <div class="preview-placeholder-row" v-else>Sin mecánico asignado</div>

                            <div class="preview-divider" />

                            <!-- Fechas -->
                            <div class="preview-row">
                                <span class="preview-key">Ingreso</span>
                                <span class="preview-val">{{ form.fecha_ingreso || '—' }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-key">Prometida</span>
                                <span class="preview-val" :class="{ 'preview-placeholder': !form.fecha_prometida }">{{ form.fecha_prometida || '—' }}</span>
                            </div>
                            <div class="preview-row" v-if="form.kilometraje">
                                <span class="preview-key">Km</span>
                                <span class="preview-val">{{ form.kilometraje }} km</span>
                            </div>
                        </div>
                        <div class="preview-footer">
                            <span class="preview-badge">Vista previa</span>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div class="pd-form-col">
                    <div class="form-header">
                        <div class="form-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="form-title">{{ isEdit ? 'Editar orden #' + orden.id : 'Nueva orden de trabajo' }}</h1>
                            <p class="form-subtitle">{{ isEdit ? 'Actualiza los datos de la orden' : 'Completa los datos para crear la orden' }}</p>
                        </div>
                    </div>

                    <div class="form-card">

                        <!-- Asignación -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Asignación</span>
                            </div>

                            <div class="field-group mb-4">
                                <label class="field-label">Vehículo / Cliente <span class="required">*</span></label>
                                <div class="field-wrapper select-wrap" :class="{ 'field-error': form.errors.vehiculo_id }">
                                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                                    <select v-model="form.vehiculo_id" class="pd-select">
                                        <option value="">Selecciona un vehículo...</option>
                                        <option v-for="v in vehiculosFormateados" :key="v.id" :value="v.id">{{ v.label }}</option>
                                    </select>
                                    <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <span v-if="form.errors.vehiculo_id" class="error-msg">{{ form.errors.vehiculo_id }}</span>
                            </div>

                            <div class="form-grid-2">
                                <div class="field-group">
                                    <label class="field-label">Mecánico asignado</label>
                                    <div class="field-wrapper select-wrap" :class="{ 'field-error': form.errors.mecanico_id }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <select v-model="form.mecanico_id" class="pd-select">
                                            <option value="">Sin asignar</option>
                                            <option v-for="m in mecanicos" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                                        </select>
                                        <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                    </div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Estado <span class="required">*</span></label>
                                    <div class="field-wrapper select-wrap" :class="{ 'field-error': form.errors.estado }">
                                        <span class="estado-dot-inline" :style="{ background: estadoColorMap[form.estado]?.dot }" />
                                        <select v-model="form.estado" class="pd-select">
                                            <option v-for="e in estadosDisponibles" :key="e.value" :value="e.value">{{ e.label }}</option>
                                        </select>
                                        <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Fechas y km -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" style="background:#4A5568" />
                                <span class="section-title">Fechas y detalles</span>
                            </div>
                            <div class="form-grid-3">
                                <div class="field-group">
                                    <label class="field-label">Fecha de ingreso <span class="required">*</span></label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.fecha_ingreso }">
                                        <input v-model="form.fecha_ingreso" type="date" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.fecha_ingreso" class="error-msg">{{ form.errors.fecha_ingreso }}</span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Fecha prometida</label>
                                    <div class="field-wrapper">
                                        <input v-model="form.fecha_prometida" type="date" class="pd-input" />
                                    </div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Kilometraje</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.kilometraje }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/><path d="M12 8v4l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <input v-model="form.kilometraje" type="number" min="0" placeholder="Ej. 45000" class="pd-input" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Observaciones -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" style="background:#CBD5E0" />
                                <span class="section-title">Observaciones</span>
                                <span class="section-optional">Opcional</span>
                            </div>
                            <div class="field-wrapper textarea-wrap">
                                <textarea v-model="form.observaciones" rows="3" class="pd-textarea" placeholder="Instrucciones o notas para el mecánico..." />
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Acciones -->
                        <div class="form-actions">
                            <button class="btn-save" :disabled="form.processing" @click="submit">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="white" stroke-width="2"/><path d="M17 21v-8H7v8M7 3v5h8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                {{ form.processing ? 'Guardando...' : (isEdit ? 'Actualizar orden' : 'Crear orden') }}
                            </button>
                            <Link :href="route('ordenes.index')">
                                <button class="btn-cancel" type="button">Cancelar</button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.pd-page { min-height: 100vh; background: #F7F8FA; padding: 28px 32px; }

.pd-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 28px; font-size: 0.82rem; }
.breadcrumb-link { color: #C0192A; text-decoration: none; font-weight: 600; transition: opacity 0.15s; }
.breadcrumb-link:hover { opacity: 0.75; }
.breadcrumb-sep { color: #CBD5E0; }
.breadcrumb-current { color: #4A5568; font-weight: 500; }

.pd-layout { display: grid; grid-template-columns: 230px 1fr; gap: 28px; max-width: 960px; margin: 0 auto; }

/* Preview */
.preview-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); position: sticky; top: 24px; }
.preview-hero { height: 70px; background: linear-gradient(135deg, #C0192A 0%, #9B1422 55%, #2D3748 100%); display: flex; align-items: center; justify-content: center; }
.preview-order-num { font-size: 1.4rem; font-weight: 900; color: white; opacity: 0.9; letter-spacing: -1px; }
.preview-body { padding: 16px 18px; }
.preview-estado { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; margin-bottom: 12px; }
.preview-estado-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.preview-divider { height: 1px; background: #EDF2F7; margin: 10px 0; }
.preview-section-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #A0AEC0; margin-bottom: 8px; }
.preview-item { display: flex; align-items: center; gap: 10px; margin-bottom: 4px; }
.preview-item-icon { width: 28px; height: 28px; border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.preview-item-main { font-size: 0.82rem; font-weight: 700; color: #1A202C; }
.preview-item-sub { font-size: 0.72rem; color: #A0AEC0; }
.preview-mecanico-av { width: 28px; height: 28px; border-radius: 7px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.58rem; font-weight: 800; color: white; flex-shrink: 0; }
.preview-placeholder-row { font-size: 0.78rem; color: #E2E8F0; margin-bottom: 4px; }
.preview-row { display: flex; justify-content: space-between; margin-bottom: 6px; }
.preview-key { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; }
.preview-val { font-size: 0.78rem; color: #2D3748; font-weight: 600; font-family: monospace; }
.preview-placeholder { color: #E2E8F0 !important; }
.preview-footer { padding: 10px 18px; border-top: 1px solid #EDF2F7; background: #F7F8FA; }
.preview-badge { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #A0AEC0; }

/* Form */
.form-header { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
.form-header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.form-title { font-size: 1.4rem; font-weight: 800; color: #2D3748; margin: 0; letter-spacing: -0.3px; }
.form-subtitle { font-size: 0.8rem; color: #A0AEC0; margin: 2px 0 0; }

.form-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; box-shadow: 0 4px 24px rgba(0,0,0,0.05); overflow: hidden; }
.form-section { padding: 22px 26px; }
.form-sep { height: 1px; background: #EDF2F7; }
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px; }
.mb-4 { margin-bottom: 16px; }

.section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
.section-dot { width: 8px; height: 8px; border-radius: 50%; background: #C0192A; flex-shrink: 0; }
.section-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4A5568; }
.section-optional { font-size: 0.7rem; color: #CBD5E0; }

.field-group { display: flex; flex-direction: column; }
.field-label { font-size: 0.78rem; font-weight: 600; color: #4A5568; margin-bottom: 6px; }
.required { color: #C0192A; }

.field-wrapper { display: flex; align-items: center; gap: 10px; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 14px; height: 44px; transition: border-color 0.15s, box-shadow 0.15s, background 0.15s; }
.field-wrapper:focus-within { background: white; border-color: #C0192A; box-shadow: 0 0 0 3px rgba(192,25,42,0.1); }
.field-wrapper.field-error { border-color: #E53E3E; background: #FFF5F5; }
.textarea-wrap { height: auto; padding: 12px 14px; align-items: flex-start; }
.select-wrap { padding-right: 10px; }

.field-icon { color: #A0AEC0; flex-shrink: 0; transition: color 0.15s; }
.field-wrapper:focus-within .field-icon { color: #C0192A; }

.pd-input { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; }
.pd-input::placeholder { color: #CBD5E0; }
.pd-select { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; appearance: none; cursor: pointer; }
.select-arrow { flex-shrink: 0; pointer-events: none; }
.pd-textarea { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; resize: none; width: 100%; line-height: 1.6; }
.pd-textarea::placeholder { color: #CBD5E0; }

.estado-dot-inline { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; transition: background 0.2s; }
.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; }

.form-actions { padding: 20px 26px; display: flex; align-items: center; gap: 12px; background: #FAFBFC; border-top: 1px solid #EDF2F7; }
.btn-save { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s, box-shadow 0.15s; font-family: inherit; }
.btn-save:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(192,25,42,0.45); }
.btn-save:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-cancel { background: transparent; color: #718096; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 20px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-cancel:hover { border-color: #C0192A; color: #C0192A; }

@media (max-width: 768px) { .pd-layout { grid-template-columns: 1fr; } .pd-sidebar { display: none; } .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; } .pd-page { padding: 16px; } }
</style>