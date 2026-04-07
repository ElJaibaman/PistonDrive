<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ cliente: Object });

const form = useForm({
    nombre:           props.cliente?.nombre           ?? '',
    numero_identidad: props.cliente?.numero_identidad ?? '',
    procedencia:      props.cliente?.procedencia      ?? '',
    telefono:         props.cliente?.telefono         ?? '',
    correo:           props.cliente?.correo           ?? '',
});

const isEdit = computed(() => !!props.cliente);

function submit() {
    if (isEdit.value) form.put(route('clientes.update', props.cliente.id));
    else form.post(route('clientes.store'));
}

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Top bar de navegación contextual -->
            <div class="pd-breadcrumb">
                <Link :href="route('clientes.index')" class="breadcrumb-link">
                    <span class="breadcrumb-icon">←</span> Clientes
                </Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ isEdit ? 'Editar' : 'Nuevo cliente' }}</span>
            </div>

            <div class="pd-layout">

                <!-- Columna izquierda: Preview card -->
                <div class="pd-sidebar">
                    <div class="preview-card">
                        <div class="preview-card-accent" />
                        <div class="preview-body">
                            <div class="preview-avatar" :class="{ 'has-name': form.nombre }">
                                {{ form.nombre ? iniciales(form.nombre) : '?' }}
                            </div>
                            <div class="preview-name">{{ form.nombre || 'Nombre del cliente' }}</div>
                            <div class="preview-id">{{ form.numero_identidad || 'Sin identidad' }}</div>

                            <div class="preview-divider" />

                            <div class="preview-info-row" v-if="form.telefono">
                                <span class="pi-icon">📞</span>
                                <span>{{ form.telefono }}</span>
                            </div>
                            <div class="preview-info-row preview-placeholder" v-else>
                                <span class="pi-icon">📞</span>
                                <span>Teléfono</span>
                            </div>

                            <div class="preview-info-row" v-if="form.correo">
                                <span class="pi-icon">✉</span>
                                <span>{{ form.correo }}</span>
                            </div>
                            <div class="preview-info-row preview-placeholder" v-else>
                                <span class="pi-icon">✉</span>
                                <span>Correo electrónico</span>
                            </div>

                            <div class="preview-info-row" v-if="form.procedencia">
                                <span class="pi-icon">📍</span>
                                <span>{{ form.procedencia }}</span>
                            </div>
                            <div class="preview-info-row preview-placeholder" v-else>
                                <span class="pi-icon">📍</span>
                                <span>Ciudad / Procedencia</span>
                            </div>
                        </div>
                        <div class="preview-footer">
                            <span class="preview-badge">Vista previa</span>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha: Formulario -->
                <div class="pd-form-col">

                    <!-- Título -->
                    <div class="form-header">
                        <div class="form-header-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path v-if="!isEdit" d="M12 4v16m-8-8h16" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                                <path v-else d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="form-title">{{ isEdit ? 'Editar cliente' : 'Nuevo cliente' }}</h1>
                            <p class="form-subtitle">{{ isEdit ? 'Actualiza la información del cliente' : 'Completa los datos para registrar al cliente' }}</p>
                        </div>
                    </div>

                    <!-- Card del formulario -->
                    <div class="form-card">

                        <!-- Sección 1 -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Información personal</span>
                            </div>
                            <div class="form-grid-2">
                                <div class="field-group">
                                    <label class="field-label">Nombre completo <span class="required">*</span></label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.nombre }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <input v-model="form.nombre" type="text" placeholder="Ej. Juan Pérez López" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.nombre" class="error-msg">{{ form.errors.nombre }}</span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Número de identidad</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.numero_identidad }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 15h3M14 15h3M7 11h10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <input v-model="form.numero_identidad" type="text" placeholder="0801-1990-XXXXX" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.numero_identidad" class="error-msg">{{ form.errors.numero_identidad }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Sección 2 -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Información de contacto</span>
                            </div>
                            <div class="form-grid-2">
                                <div class="field-group">
                                    <label class="field-label">Teléfono</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.telefono }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <input v-model="form.telefono" type="tel" placeholder="+504 9999-9999" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.telefono" class="error-msg">{{ form.errors.telefono }}</span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label">Correo electrónico</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.correo }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.5"/><path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <input v-model="form.correo" type="email" placeholder="cliente@correo.com" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.correo" class="error-msg">{{ form.errors.correo }}</span>
                                </div>
                            </div>
                            <div class="field-group mt-4">
                                <label class="field-label">Procedencia / Ciudad</label>
                                <div class="field-wrapper" :class="{ 'field-error': form.errors.procedencia }">
                                    <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z" stroke="currentColor" stroke-width="1.5"/></svg>
                                    <input v-model="form.procedencia" type="text" placeholder="Ej. Tegucigalpa, Francisco Morazán" class="pd-input" />
                                </div>
                                <span v-if="form.errors.procedencia" class="error-msg">{{ form.errors.procedencia }}</span>
                            </div>
                        </div>

                        <div class="form-sep" />

                        <!-- Acciones -->
                        <div class="form-actions">
                            <button class="btn-save" :disabled="form.processing" @click="submit">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="btn-icon"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="white" stroke-width="2"/><path d="M17 21v-8H7v8M7 3v5h8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                {{ form.processing ? 'Guardando...' : (isEdit ? 'Actualizar cliente' : 'Guardar cliente') }}
                            </button>
                            <Link :href="route('clientes.index')">
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
.pd-page {
    min-height: 100vh;
    background: #F7F8FA;
    padding: 28px 32px;
}

/* Breadcrumb */
.pd-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 28px;
    font-size: 0.82rem;
}
.breadcrumb-link {
    color: #C0192A;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: opacity 0.15s;
}
.breadcrumb-link:hover { opacity: 0.75; }
.breadcrumb-icon { font-size: 0.9rem; }
.breadcrumb-sep { color: #CBD5E0; }
.breadcrumb-current { color: #4A5568; font-weight: 500; }

/* Layout dos columnas */
.pd-layout {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 28px;
    max-width: 980px;
    margin: 0 auto;
}

/* Sidebar preview */
.pd-sidebar { }
.preview-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #EDF2F7;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    position: sticky;
    top: 24px;
}
.preview-card-accent {
    height: 80px;
    background: linear-gradient(135deg, #C0192A 0%, #9B1422 60%, #2D3748 100%);
}
.preview-body {
    padding: 0 20px 20px;
    margin-top: -36px;
}
.preview-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #4A5568;
    border: 3px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 12px;
    transition: background 0.2s;
    letter-spacing: 1px;
}
.preview-avatar.has-name { background: linear-gradient(135deg, #C0192A, #9B1422); }
.preview-name {
    font-size: 1rem;
    font-weight: 700;
    color: #2D3748;
    margin-bottom: 2px;
    line-height: 1.3;
}
.preview-id {
    font-size: 0.78rem;
    color: #A0AEC0;
    margin-bottom: 14px;
}
.preview-divider {
    height: 1px;
    background: #EDF2F7;
    margin-bottom: 14px;
}
.preview-info-row {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 0.8rem;
    color: #4A5568;
    margin-bottom: 8px;
    word-break: break-all;
    line-height: 1.4;
}
.preview-placeholder { color: #CBD5E0; }
.pi-icon { font-size: 0.75rem; margin-top: 1px; flex-shrink: 0; }
.preview-footer {
    padding: 10px 20px;
    border-top: 1px solid #EDF2F7;
    background: #F7F8FA;
}
.preview-badge {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    color: #A0AEC0;
}

/* Columna formulario */
.pd-form-col { }

.form-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px;
}
.form-header-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #C0192A, #9B1422);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(192,25,42,0.35);
    flex-shrink: 0;
}
.form-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #2D3748;
    margin: 0;
    letter-spacing: -0.3px;
}
.form-subtitle {
    font-size: 0.8rem;
    color: #A0AEC0;
    margin: 2px 0 0;
}

/* Card formulario */
.form-card {
    background: white;
    border-radius: 16px;
    border: 1px solid #EDF2F7;
    box-shadow: 0 4px 24px rgba(0,0,0,0.05);
    overflow: hidden;
}

.form-section { padding: 24px 28px; }

.section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}
.section-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #C0192A;
    flex-shrink: 0;
}
.section-title {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #4A5568;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-sep {
    height: 1px;
    background: #EDF2F7;
    margin: 0 28px;
}

.mt-4 { margin-top: 16px; }

/* Campos */
.field-group { display: flex; flex-direction: column; }
.field-label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #4A5568;
    margin-bottom: 6px;
    letter-spacing: 0.2px;
}
.required { color: #C0192A; }

.field-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #F7F8FA;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 0 14px;
    height: 44px;
    transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
}
.field-wrapper:focus-within {
    background: white;
    border-color: #C0192A;
    box-shadow: 0 0 0 3px rgba(192,25,42,0.1);
}
.field-wrapper.field-error {
    border-color: #E53E3E;
    background: #FFF5F5;
}
.field-icon {
    color: #A0AEC0;
    flex-shrink: 0;
    transition: color 0.15s;
}
.field-wrapper:focus-within .field-icon { color: #C0192A; }

.pd-input {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    font-size: 0.875rem;
    color: #2D3748;
    font-family: inherit;
}
.pd-input::placeholder { color: #CBD5E0; }

.error-msg {
    font-size: 0.75rem;
    color: #E53E3E;
    margin-top: 4px;
}

/* Acciones */
.form-actions {
    padding: 20px 28px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: #FAFBFC;
    border-top: 1px solid #EDF2F7;
}

.btn-save {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #C0192A, #9B1422);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0 24px;
    height: 44px;
    font-size: 0.875rem;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(192,25,42,0.35);
    transition: transform 0.15s, box-shadow 0.15s;
    font-family: inherit;
}
.btn-save:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(192,25,42,0.45);
}
.btn-save:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-icon { flex-shrink: 0; }

.btn-cancel {
    background: transparent;
    color: #718096;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: 0 20px;
    height: 44px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: border-color 0.15s, color 0.15s;
    font-family: inherit;
}
.btn-cancel:hover {
    border-color: #C0192A;
    color: #C0192A;
}

@media (max-width: 768px) {
    .pd-layout { grid-template-columns: 1fr; }
    .pd-sidebar { display: none; }
    .form-grid-2 { grid-template-columns: 1fr; }
    .pd-page { padding: 16px; }
}
</style>