<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ vehiculo: Object, clientes: Array });

const form = useForm({
    cliente_id:    props.vehiculo?.cliente_id    ?? '',
    placa:         props.vehiculo?.placa         ?? '',
    marca:         props.vehiculo?.marca         ?? '',
    modelo:        props.vehiculo?.modelo        ?? '',
    anio:          props.vehiculo?.anio          ?? '',
    color:         props.vehiculo?.color         ?? '',
    observaciones: props.vehiculo?.observaciones ?? '',
});

const isEdit = computed(() => !!props.vehiculo);
const anioActual = new Date().getFullYear();
const anios = Array.from({ length: 40 }, (_, i) => anioActual - i);

function submit() {
    if (isEdit.value) form.put(route('vehiculos.update', props.vehiculo.id));
    else form.post(route('vehiculos.store'));
}

const clienteSeleccionado = computed(() =>
    props.clientes.find(c => c.id == form.cliente_id)
);

const colorMap = {
    'negro': '#1A202C', 'blanco': '#F7FAFC', 'gris': '#718096', 'rojo': '#C0192A',
    'azul': '#2B6CB0', 'verde': '#276749', 'plateado': '#A0AEC0', 'beige': '#D4A574',
    'amarillo': '#D69E2E', 'naranja': '#C05621', 'cafe': '#744210', 'marrón': '#744210',
};
const dotColor = computed(() => {
    if (!form.color) return '#E2E8F0';
    const key = form.color.toLowerCase();
    for (const [k, v] of Object.entries(colorMap)) {
        if (key.includes(k)) return v;
    }
    return '#A0AEC0';
});

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('vehiculos.index')" class="breadcrumb-link">
                    <span>←</span> Vehículos
                </Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ isEdit ? 'Editar vehículo' : 'Nuevo vehículo' }}</span>
            </div>

            <div class="pd-layout">

                <!-- Preview sidebar -->
                <div class="pd-sidebar">
                    <div class="preview-card">
                        <div class="preview-hero">
                            <div class="car-silhouette">
                                <svg width="80" height="45" viewBox="0 0 80 45" fill="none">
                                    <path d="M10 30 L15 18 Q20 12 28 11 L52 11 Q60 12 65 18 L70 30 L72 30 Q74 30 74 32 L74 36 Q74 38 72 38 L68 38 Q67 41 64 41 Q61 41 60 38 L20 38 Q19 41 16 41 Q13 41 12 38 L8 38 Q6 38 6 36 L6 32 Q6 30 8 30 Z" fill="white" fill-opacity="0.15" stroke="white" stroke-opacity="0.4" stroke-width="1.5"/>
                                    <circle cx="16" cy="39" r="5" fill="white" fill-opacity="0.3" stroke="white" stroke-opacity="0.6" stroke-width="1.5"/>
                                    <circle cx="64" cy="39" r="5" fill="white" fill-opacity="0.3" stroke="white" stroke-opacity="0.6" stroke-width="1.5"/>
                                    <path d="M20 18 L24 13 L55 13 L60 18 Z" fill="white" fill-opacity="0.2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="preview-body">
                            <div class="preview-marca">{{ form.marca || 'Marca' }} {{ form.modelo || 'Modelo' }}</div>
                            <div class="preview-placa">{{ form.placa || 'Sin placa' }}</div>

                            <div class="preview-divider" />

                            <div class="preview-row">
                                <span class="preview-key">Año</span>
                                <span class="preview-val">{{ form.anio || '—' }}</span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-key">Color</span>
                                <div class="preview-color-wrap" v-if="form.color">
                                    <div class="preview-color-dot" :style="{ background: dotColor }" />
                                    <span class="preview-val">{{ form.color }}</span>
                                </div>
                                <span class="preview-val" v-else>—</span>
                            </div>
                            <div class="preview-row" v-if="clienteSeleccionado">
                                <span class="preview-key">Propietario</span>
                                <div class="preview-owner">
                                    <div class="owner-av">{{ iniciales(clienteSeleccionado.nombre) }}</div>
                                    <span class="preview-val">{{ clienteSeleccionado.nombre }}</span>
                                </div>
                            </div>
                            <div class="preview-row" v-else>
                                <span class="preview-key">Propietario</span>
                                <span class="preview-val preview-placeholder">Sin asignar</span>
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
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z" stroke="white" stroke-width="1.8" fill="none"/>
                                <circle cx="6.5" cy="15.5" r="1.5" fill="white"/>
                                <circle cx="17.5" cy="15.5" r="1.5" fill="white"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="form-title">{{ isEdit ? 'Editar vehículo' : 'Nuevo vehículo' }}</h1>
                            <p class="form-subtitle">{{ isEdit ? 'Actualiza los datos del vehículo' : 'Registra un nuevo vehículo en el sistema' }}</p>
                        </div>
                    </div>

                    <div class="form-card">

                        <!-- Propietario -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Propietario</span>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Cliente <span class="required">*</span></label>
                                <div class="field-wrapper select-wrapper" :class="{ 'field-error': form.errors.cliente_id }">
                                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                    <select v-model="form.cliente_id" class="pd-select">
                                        <option value="">Selecciona un cliente...</option>
                                        <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                                    </select>
                                    <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                </div>
                                <span v-if="form.errors.cliente_id" class="error-msg">{{ form.errors.cliente_id }}</span>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Datos del vehículo -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Datos del vehículo</span>
                            </div>
                            <div class="form-grid-2 mb-4">
                                <div class="field-group">
                                    <label class="field-label">Marca <span class="required">*</span></label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.marca }">
                                        <input v-model="form.marca" type="text" placeholder="Ej. Toyota, Honda" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.marca" class="error-msg">{{ form.errors.marca }}</span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Modelo <span class="required">*</span></label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.modelo }">
                                        <input v-model="form.modelo" type="text" placeholder="Ej. Corolla, Civic" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.modelo" class="error-msg">{{ form.errors.modelo }}</span>
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="field-group">
                                    <label class="field-label">Placa</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.placa }">
                                        <input v-model="form.placa" type="text" placeholder="AAA-000" class="pd-input placa-input" style="text-transform:uppercase;letter-spacing:2px;font-family:monospace" />
                                    </div>
                                    <span v-if="form.errors.placa" class="error-msg">{{ form.errors.placa }}</span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Año</label>
                                    <div class="field-wrapper select-wrapper" :class="{ 'field-error': form.errors.anio }">
                                        <select v-model="form.anio" class="pd-select">
                                            <option value="">Año...</option>
                                            <option v-for="a in anios" :key="a" :value="a">{{ a }}</option>
                                        </select>
                                        <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M6 9l6 6 6-6" stroke="#A0AEC0" stroke-width="2" stroke-linecap="round"/></svg>
                                    </div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Color</label>
                                    <div class="field-wrapper" style="gap:8px">
                                        <div class="color-preview-dot" :style="{ background: dotColor }" />
                                        <input v-model="form.color" type="text" placeholder="Ej. Negro, Rojo" class="pd-input" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Observaciones -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Observaciones</span>
                            </div>
                            <div class="field-group">
                                <div class="field-wrapper textarea-wrapper">
                                    <textarea v-model="form.observaciones" rows="3" placeholder="Daños visibles, condición general, notas importantes..." class="pd-textarea" />
                                </div>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Acciones -->
                        <div class="form-actions">
                            <button class="btn-save" :disabled="form.processing" @click="submit">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="white" stroke-width="2"/><path d="M17 21v-8H7v8M7 3v5h8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                {{ form.processing ? 'Guardando...' : (isEdit ? 'Actualizar vehículo' : 'Guardar vehículo') }}
                            </button>
                            <Link :href="route('vehiculos.index')">
                                <button class="btn-cancel">Cancelar</button>
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
.breadcrumb-link { color: #C0192A; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 4px; transition: opacity 0.15s; }
.breadcrumb-link:hover { opacity: 0.75; }
.breadcrumb-sep { color: #CBD5E0; }
.breadcrumb-current { color: #4A5568; font-weight: 500; }

.pd-layout { display: grid; grid-template-columns: 240px 1fr; gap: 28px; max-width: 960px; margin: 0 auto; }

/* Preview */
.preview-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); position: sticky; top: 24px; }
.preview-hero { height: 90px; background: linear-gradient(135deg, #C0192A 0%, #9B1422 55%, #2D3748 100%); display: flex; align-items: center; justify-content: center; }
.car-silhouette { opacity: 0.9; }
.preview-body { padding: 16px 20px; }
.preview-marca { font-size: 1rem; font-weight: 800; color: #1A202C; margin-bottom: 2px; }
.preview-placa { font-size: 0.75rem; font-weight: 700; color: #A0AEC0; letter-spacing: 1.5px; font-family: monospace; margin-bottom: 14px; }
.preview-divider { height: 1px; background: #EDF2F7; margin-bottom: 12px; }
.preview-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.preview-key { font-size: 0.72rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.preview-val { font-size: 0.82rem; color: #2D3748; font-weight: 600; }
.preview-placeholder { color: #E2E8F0 !important; }
.preview-color-wrap { display: flex; align-items: center; gap: 6px; }
.preview-color-dot { width: 12px; height: 12px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,0.1); }
.preview-owner { display: flex; align-items: center; gap: 6px; }
.owner-av { width: 22px; height: 22px; border-radius: 6px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.55rem; font-weight: 800; color: white; }
.preview-footer { padding: 10px 20px; border-top: 1px solid #EDF2F7; background: #F7F8FA; }
.preview-badge { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #A0AEC0; }

/* Form */
.form-header { display: flex; align-items: center; gap: 14px; margin-bottom: 20px; }
.form-header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.form-title { font-size: 1.4rem; font-weight: 800; color: #2D3748; margin: 0; letter-spacing: -0.3px; }
.form-subtitle { font-size: 0.8rem; color: #A0AEC0; margin: 2px 0 0; }

.form-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; box-shadow: 0 4px 24px rgba(0,0,0,0.05); overflow: hidden; }
.form-section { padding: 22px 26px; }
.form-sep { height: 1px; background: #EDF2F7; }

.section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
.section-dot { width: 8px; height: 8px; border-radius: 50%; background: #C0192A; flex-shrink: 0; }
.section-title { font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #4A5568; }

.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.mb-4 { margin-bottom: 16px; }

.field-group { display: flex; flex-direction: column; }
.field-label { font-size: 0.78rem; font-weight: 600; color: #4A5568; margin-bottom: 6px; }
.required { color: #C0192A; }

.field-wrapper { display: flex; align-items: center; gap: 10px; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 14px; height: 44px; transition: border-color 0.15s, box-shadow 0.15s, background 0.15s; }
.field-wrapper:focus-within { background: white; border-color: #C0192A; box-shadow: 0 0 0 3px rgba(192,25,42,0.1); }
.field-wrapper.field-error { border-color: #E53E3E; background: #FFF5F5; }
.textarea-wrapper { height: auto; padding: 12px 14px; align-items: flex-start; }
.select-wrapper { padding-right: 10px; }

.field-icon { color: #A0AEC0; flex-shrink: 0; transition: color 0.15s; }
.field-wrapper:focus-within .field-icon { color: #C0192A; }

.pd-input { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; }
.pd-input::placeholder { color: #CBD5E0; }

.pd-select { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; appearance: none; cursor: pointer; }
.select-arrow { flex-shrink: 0; pointer-events: none; }

.pd-textarea { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; resize: none; width: 100%; line-height: 1.6; }
.pd-textarea::placeholder { color: #CBD5E0; }

.color-preview-dot { width: 16px; height: 16px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,0.1); flex-shrink: 0; transition: background 0.2s; }

.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; }

.form-actions { padding: 20px 26px; display: flex; align-items: center; gap: 12px; background: #FAFBFC; border-top: 1px solid #EDF2F7; }
.btn-save { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s, box-shadow 0.15s; font-family: inherit; }
.btn-save:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(192,25,42,0.45); }
.btn-save:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-cancel { background: transparent; color: #718096; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 20px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-cancel:hover { border-color: #C0192A; color: #C0192A; }

@media (max-width: 768px) { .pd-layout { grid-template-columns: 1fr; } .pd-sidebar { display: none; } .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; } .pd-page { padding: 16px; } }
</style>