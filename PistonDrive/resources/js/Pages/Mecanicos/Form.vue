<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    mecanico: Object,
});

const form = useForm({
    nombre:        props.mecanico?.nombre   ?? '',
    telefono:      props.mecanico?.telefono ?? '',
    activo:        props.mecanico?.activo   ?? true,
    crear_usuario: false,
    email:         '',
    password:      '',
});

const isEdit = computed(() => !!props.mecanico);

function submit() {
    if (isEdit.value) {
        form.put(route('mecanicos.update', props.mecanico.id));
    } else {
        form.post(route('mecanicos.store'));
    }
}

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
                <Link :href="route('mecanicos.index')" class="breadcrumb-link">← Mecánicos</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ isEdit ? 'Editar mecánico' : 'Nuevo mecánico' }}</span>
            </div>

            <div class="pd-layout">

                <!-- Preview sidebar -->
                <div class="pd-sidebar">
                    <div class="preview-card">
                        <div class="preview-hero">
                            <div class="preview-avatar" :class="{ 'has-name': form.nombre }">
                                {{ iniciales(form.nombre) }}
                            </div>
                        </div>
                        <div class="preview-body">
                            <div class="preview-nombre">{{ form.nombre || 'Nombre del mecánico' }}</div>
                            <div class="preview-divider" />
                            <div class="preview-row">
                                <span class="preview-key">Teléfono</span>
                                <span class="preview-val" :class="{ 'preview-placeholder': !form.telefono }">
                                    {{ form.telefono || '—' }}
                                </span>
                            </div>
                            <div class="preview-row">
                                <span class="preview-key">Estado</span>
                                <span class="status-badge" :class="form.activo ? 'status-active' : 'status-inactive'">
                                    <span class="status-dot" />
                                    {{ form.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <div class="preview-row" v-if="!isEdit && form.crear_usuario && form.email">
                                <span class="preview-key">Usuario</span>
                                <span class="preview-val preview-email">{{ form.email }}</span>
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
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="form-title">{{ isEdit ? 'Editar mecánico' : 'Nuevo mecánico' }}</h1>
                            <p class="form-subtitle">{{ isEdit ? 'Actualiza los datos del mecánico' : 'Registra un nuevo mecánico en el taller' }}</p>
                        </div>
                    </div>

                    <div class="form-card">

                        <!-- Información personal -->
                        <div class="form-section">
                            <div class="section-header">
                                <div class="section-dot" />
                                <span class="section-title">Información personal</span>
                            </div>
                            <div class="form-grid-2">
                                <div class="field-group" style="grid-column: 1 / -1">
                                    <label class="field-label">Nombre completo <span class="required">*</span></label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.nombre }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="1.5"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <input v-model="form.nombre" type="text" placeholder="Ej. Carlos Martínez" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.nombre" class="error-msg">{{ form.errors.nombre }}</span>
                                </div>
                                <div class="field-group" style="grid-column: 1 / -1">
                                    <label class="field-label">Teléfono</label>
                                    <div class="field-wrapper" :class="{ 'field-error': form.errors.telefono }">
                                        <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.01L6.6 10.8z" stroke="currentColor" stroke-width="1.5"/></svg>
                                        <input v-model="form.telefono" type="tel" placeholder="+504 9999-9999" class="pd-input" />
                                    </div>
                                    <span v-if="form.errors.telefono" class="error-msg">{{ form.errors.telefono }}</span>
                                </div>
                            </div>

                            <!-- Toggle activo -->
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <span class="toggle-label">Mecánico activo</span>
                                    <span class="toggle-sub">Los mecánicos inactivos no aparecen al asignar órdenes</span>
                                </div>
                                <button
                                    type="button"
                                    class="toggle-btn"
                                    :class="form.activo ? 'toggle-on' : 'toggle-off'"
                                    @click="form.activo = !form.activo"
                                >
                                    <div class="toggle-thumb" />
                                </button>
                            </div>
                        </div>

                        <!-- Acceso al sistema (solo en creación) -->
                        <template v-if="!isEdit">
                            <div class="form-sep" />
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-dot" style="background:#4A5568" />
                                    <span class="section-title">Acceso al sistema</span>
                                    <span class="section-optional">Opcional</span>
                                </div>

                                <div class="toggle-row mb-4">
                                    <div class="toggle-info">
                                        <span class="toggle-label">Crear usuario de acceso</span>
                                        <span class="toggle-sub">Permite al mecánico iniciar sesión para ver sus órdenes</span>
                                    </div>
                                    <button
                                        type="button"
                                        class="toggle-btn"
                                        :class="form.crear_usuario ? 'toggle-on' : 'toggle-off'"
                                        @click="form.crear_usuario = !form.crear_usuario"
                                    >
                                        <div class="toggle-thumb" />
                                    </button>
                                </div>

                                <div v-if="form.crear_usuario" class="user-fields">
                                    <div class="field-group mb-3">
                                        <label class="field-label">Correo electrónico <span class="required">*</span></label>
                                        <div class="field-wrapper" :class="{ 'field-error': form.errors.email }">
                                            <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="1.5"/><path d="M22 6l-10 7L2 6" stroke="currentColor" stroke-width="1.5"/></svg>
                                            <input v-model="form.email" type="email" placeholder="mecanico@taller.com" class="pd-input" />
                                        </div>
                                        <span v-if="form.errors.email" class="error-msg">{{ form.errors.email }}</span>
                                    </div>
                                    <div class="field-group">
                                        <label class="field-label">Contraseña <span class="required">*</span></label>
                                        <div class="field-wrapper" :class="{ 'field-error': form.errors.password }">
                                            <svg class="field-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor" stroke-width="1.5"/></svg>
                                            <input v-model="form.password" type="password" placeholder="Mínimo 8 caracteres" class="pd-input" />
                                        </div>
                                        <span v-if="form.errors.password" class="error-msg">{{ form.errors.password }}</span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="form-sep" />

                        <!-- Acciones -->
                        <div class="form-actions">
                            <button class="btn-save" :disabled="form.processing" @click="submit">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" stroke="white" stroke-width="2"/><path d="M17 21v-8H7v8M7 3v5h8" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                                {{ form.processing ? 'Guardando...' : (isEdit ? 'Actualizar mecánico' : 'Guardar mecánico') }}
                            </button>
                            <Link :href="route('mecanicos.index')">
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

.pd-layout { display: grid; grid-template-columns: 220px 1fr; gap: 28px; max-width: 860px; margin: 0 auto; }

/* Preview */
.preview-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); position: sticky; top: 24px; }
.preview-hero { height: 80px; background: linear-gradient(135deg, #C0192A 0%, #9B1422 55%, #2D3748 100%); display: flex; align-items: flex-end; justify-content: center; padding-bottom: 0; }
.preview-avatar { width: 64px; height: 64px; border-radius: 50%; border: 3px solid white; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800; color: white; background: #4A5568; margin-bottom: -32px; transition: background 0.2s; letter-spacing: 1px; }
.preview-avatar.has-name { background: linear-gradient(135deg, #C0192A, #9B1422); }
.preview-body { padding: 44px 20px 16px; }
.preview-nombre { font-size: 0.95rem; font-weight: 800; color: #1A202C; margin-bottom: 12px; text-align: center; }
.preview-divider { height: 1px; background: #EDF2F7; margin-bottom: 12px; }
.preview-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
.preview-key { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.preview-val { font-size: 0.8rem; color: #2D3748; font-weight: 600; }
.preview-placeholder { color: #E2E8F0 !important; }
.preview-email { font-size: 0.72rem; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.status-badge { display: flex; align-items: center; gap: 5px; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
.status-active { background: rgba(56,161,105,0.1); color: #276749; }
.status-active .status-dot { background: #38A169; }
.status-inactive { background: #F7FAFC; color: #A0AEC0; }
.status-inactive .status-dot { background: #CBD5E0; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
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
.form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
.mb-3 { margin-bottom: 12px; }
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
.field-icon { color: #A0AEC0; flex-shrink: 0; transition: color 0.15s; }
.field-wrapper:focus-within .field-icon { color: #C0192A; }
.pd-input { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; color: #2D3748; font-family: inherit; }
.pd-input::placeholder { color: #CBD5E0; }
.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; }

/* Toggle switch */
.toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 14px 0; }
.toggle-info { flex: 1; }
.toggle-label { font-size: 0.875rem; font-weight: 600; color: #2D3748; display: block; }
.toggle-sub { font-size: 0.75rem; color: #A0AEC0; display: block; margin-top: 2px; }
.toggle-btn { width: 44px; height: 24px; border-radius: 12px; border: none; cursor: pointer; position: relative; transition: background 0.2s; flex-shrink: 0; padding: 0; }
.toggle-on  { background: linear-gradient(135deg, #C0192A, #9B1422); }
.toggle-off { background: #E2E8F0; }
.toggle-thumb { width: 18px; height: 18px; border-radius: 50%; background: white; position: absolute; top: 3px; transition: left 0.2s; box-shadow: 0 1px 4px rgba(0,0,0,0.2); }
.toggle-on  .toggle-thumb { left: 23px; }
.toggle-off .toggle-thumb { left: 3px; }

.user-fields { background: #F7F8FA; border-radius: 12px; padding: 16px; border: 1px solid #EDF2F7; }

.form-actions { padding: 20px 26px; display: flex; align-items: center; gap: 12px; background: #FAFBFC; border-top: 1px solid #EDF2F7; }
.btn-save { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s, box-shadow 0.15s; font-family: inherit; }
.btn-save:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(192,25,42,0.45); }
.btn-save:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-cancel { background: transparent; color: #718096; border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 0 20px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-cancel:hover { border-color: #C0192A; color: #C0192A; }

@media (max-width: 768px) { .pd-layout { grid-template-columns: 1fr; } .pd-sidebar { display: none; } .form-grid-2 { grid-template-columns: 1fr; } .pd-page { padding: 16px; } }
</style>