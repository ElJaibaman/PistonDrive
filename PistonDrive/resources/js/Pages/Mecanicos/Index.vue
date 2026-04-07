<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    mecanicos: {
        type: Object,
        default: () => ({ data: [] })
    }
});

const dialog = ref(false);
const aEliminar = ref(null);

function confirmarEliminar(m) { aEliminar.value = m; dialog.value = true; }
function eliminar() {
    router.delete(route('mecanicos.destroy', aEliminar.value.id), {
        onFinish: () => { dialog.value = false; },
    });
}
function toggleActivo(m) {
    router.patch(route('mecanicos.toggle-activo', m.id));
}

const totalMecanicos = computed(() => props.mecanicos.data.length);
const activos = computed(() => props.mecanicos.data.filter(m => m.activo).length);
const totalOrdenes = computed(() => props.mecanicos.data.reduce((s, m) => s + (m.ordenes_count || 0), 0));

function iniciales(nombre) {
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}

const gradients = [
    ['#C0192A','#9B1422'], ['#2D3748','#1A202C'], ['#4A5568','#2D3748'],
    ['#9B1422','#7B1020'], ['#1A365D','#2C5282'], ['#744210','#5A3010'],
];
function avatarGrad(id) {
    const p = gradients[id % gradients.length];
    return `linear-gradient(135deg, ${p[0]}, ${p[1]})`;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Hero -->
            <div class="pd-hero">
                <div class="hero-content">
                    <div class="hero-left">
                        <div class="hero-icon-wrap">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="hero-title">Mecánicos</h1>
                            <p class="hero-sub">Personal técnico del taller</p>
                        </div>
                    </div>
                    <Link :href="route('mecanicos.create')">
                        <button class="btn-new">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="white" stroke-width="2.5" stroke-linecap="round"/></svg>
                            Nuevo mecánico
                        </button>
                    </Link>
                </div>

                <div class="hero-stats">
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#C0192A" />
                        <span class="stat-num">{{ totalMecanicos }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                    <div class="stat-divider" />
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#38A169" />
                        <span class="stat-num">{{ activos }}</span>
                        <span class="stat-label">Activos</span>
                    </div>
                    <div class="stat-divider" />
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#CBD5E0" />
                        <span class="stat-num">{{ totalMecanicos - activos }}</span>
                        <span class="stat-label">Inactivos</span>
                    </div>
                    <div class="stat-divider" />
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#4A5568" />
                        <span class="stat-num">{{ totalOrdenes }}</span>
                        <span class="stat-label">Órdenes totales</span>
                    </div>
                </div>
            </div>

            <!-- Cards de mecánicos -->
            <div class="pd-content">
                <div class="mecanicos-grid" v-if="mecanicos.data.length > 0">
                    <div v-for="m in mecanicos.data" :key="m.id" class="mec-card" :class="{ 'mec-inactive': !m.activo }">
                        <div class="mec-card-top">
                            <div class="mec-avatar" :style="{ background: avatarGrad(m.id) }">
                                {{ iniciales(m.nombre) }}
                            </div>
                            <div class="mec-status-wrap">
                                <button class="status-toggle" :class="m.activo ? 'status-active' : 'status-inactive'" @click="toggleActivo(m)" :title="m.activo ? 'Click para desactivar' : 'Click para activar'">
                                    <div class="status-dot" />
                                    {{ m.activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </div>
                        </div>

                        <div class="mec-info">
                            <h3 class="mec-nombre">{{ m.nombre }}</h3>
                            <div class="mec-meta" v-if="m.telefono">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#A0AEC0"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.01L6.6 10.8z"/></svg>
                                <span>{{ m.telefono }}</span>
                            </div>
                            <div class="mec-meta mec-meta-placeholder" v-else>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#E2E8F0"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.01L6.6 10.8z"/></svg>
                                <span>Sin teléfono</span>
                            </div>
                        </div>

                        <div class="mec-divider" />

                        <div class="mec-stats-row">
                            <div class="mec-stat">
                                <span class="mec-stat-num">{{ m.ordenes_count }}</span>
                                <span class="mec-stat-label">Órdenes</span>
                            </div>
                            <div class="mec-stat-div" />
                            <div class="mec-stat">
                                <span class="mec-stat-num">#{{ m.id }}</span>
                                <span class="mec-stat-label">ID</span>
                            </div>
                        </div>

                        <div class="mec-actions">
                            <Link :href="route('mecanicos.edit', m.id)" style="flex:1">
                                <button class="mec-btn mec-btn-edit">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                    Editar
                                </button>
                            </Link>
                            <button class="mec-btn mec-btn-del" @click="confirmarEliminar(m)">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><polyline points="3,6 5,6 21,6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty state -->
                <div class="empty-wrap" v-else>
                    <div class="empty-icon-ring">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" stroke="#CBD5E0" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <p class="empty-title">No hay mecánicos registrados</p>
                    <p class="empty-sub">Agrega el primer mecánico del taller</p>
                </div>
            </div>
        </div>

        <!-- Modal eliminar -->
        <v-dialog v-model="dialog" max-width="400">
            <div class="modal-card">
                <div class="modal-top-bar" />
                <div class="modal-body">
                    <div class="modal-danger-ring">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#C0192A" stroke-width="2"/>
                            <line x1="12" y1="8" x2="12" y2="12" stroke="#C0192A" stroke-width="2" stroke-linecap="round"/>
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke="#C0192A" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="modal-title">¿Eliminar mecánico?</h3>
                    <p class="modal-desc">Vas a eliminar a <strong>{{ aEliminar?.nombre }}</strong>.<br>Esta acción es permanente.</p>
                    <div class="modal-actions">
                        <button class="modal-cancel" @click="dialog = false">Cancelar</button>
                        <button class="modal-confirm" @click="eliminar">Sí, eliminar</button>
                    </div>
                </div>
            </div>
        </v-dialog>
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
.btn-new { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 12px; padding: 0 22px; height: 46px; font-size: 0.875rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 16px rgba(192,25,42,0.4); transition: transform 0.15s, box-shadow 0.15s; font-family: inherit; }
.btn-new:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(192,25,42,0.5); }
.hero-stats { display: flex; align-items: center; border-top: 1px solid #EDF2F7; }
.stat-pill { display: flex; align-items: center; gap: 8px; padding: 14px 24px 14px 0; margin-right: 24px; }
.stat-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.stat-num { font-size: 1.1rem; font-weight: 800; color: #2D3748; }
.stat-label { font-size: 0.78rem; color: #A0AEC0; font-weight: 500; }
.stat-divider { width: 1px; height: 20px; background: #EDF2F7; margin-right: 24px; }

.pd-content { padding: 28px 32px; }
.mecanicos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }

.mec-card { background: white; border-radius: 16px; border: 1px solid #EDF2F7; box-shadow: 0 2px 12px rgba(0,0,0,0.05); overflow: hidden; transition: box-shadow 0.2s, transform 0.2s; }
.mec-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); transform: translateY(-2px); }
.mec-inactive { opacity: 0.6; }

.mec-card-top { display: flex; align-items: flex-start; justify-content: space-between; padding: 20px 20px 0; }
.mec-avatar { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 800; color: white; flex-shrink: 0; letter-spacing: 0.5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

.status-toggle { display: flex; align-items: center; gap: 5px; border: none; border-radius: 20px; padding: 4px 10px; font-size: 0.72rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: opacity 0.15s; }
.status-toggle:hover { opacity: 0.8; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.status-active { background: rgba(56,161,105,0.1); color: #276749; }
.status-active .status-dot { background: #38A169; }
.status-inactive { background: #F7FAFC; color: #A0AEC0; }
.status-inactive .status-dot { background: #CBD5E0; }

.mec-info { padding: 14px 20px 0; }
.mec-nombre { font-size: 1rem; font-weight: 800; color: #1A202C; margin: 0 0 6px; }
.mec-meta { display: flex; align-items: center; gap: 6px; font-size: 0.8rem; color: #4A5568; }
.mec-meta-placeholder { color: #E2E8F0; }

.mec-divider { height: 1px; background: #EDF2F7; margin: 16px 20px; }

.mec-stats-row { display: flex; align-items: center; padding: 0 20px; margin-bottom: 16px; }
.mec-stat { flex: 1; text-align: center; }
.mec-stat-num { display: block; font-size: 1.1rem; font-weight: 800; color: #2D3748; }
.mec-stat-label { font-size: 0.7rem; color: #A0AEC0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
.mec-stat-div { width: 1px; height: 28px; background: #EDF2F7; }

.mec-actions { display: flex; gap: 8px; padding: 0 20px 20px; }
.mec-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; border-radius: 10px; border: none; height: 38px; font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s, transform 0.1s; }
.mec-btn:hover { transform: scale(1.02); }
.mec-btn-edit { background: rgba(74,85,104,0.08); color: #4A5568; }
.mec-btn-edit:hover { background: rgba(74,85,104,0.15); }
.mec-btn-del { background: rgba(192,25,42,0.08); color: #C0192A; }
.mec-btn-del:hover { background: rgba(192,25,42,0.15); }

.empty-wrap { text-align: center; padding: 80px 16px; }
.empty-icon-ring { width: 72px; height: 72px; border-radius: 50%; background: #F7FAFC; border: 1.5px dashed #E2E8F0; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
.empty-title { font-size: 0.9rem; font-weight: 600; color: #A0AEC0; margin: 0 0 4px; }
.empty-sub { font-size: 0.8rem; color: #CBD5E0; margin: 0; }

.modal-card { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
.modal-top-bar { height: 4px; background: linear-gradient(to right, #C0192A, #9B1422); }
.modal-body { padding: 32px; text-align: center; }
.modal-danger-ring { width: 72px; height: 72px; border-radius: 50%; background: rgba(192,25,42,0.06); border: 1.5px solid rgba(192,25,42,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.modal-title { font-size: 1.1rem; font-weight: 800; color: #1A202C; margin: 0 0 10px; }
.modal-desc { font-size: 0.875rem; color: #718096; line-height: 1.6; margin: 0 0 28px; }
.modal-desc strong { color: #2D3748; }
.modal-actions { display: flex; gap: 12px; justify-content: center; }
.modal-cancel { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.modal-cancel:hover { border-color: #4A5568; color: #2D3748; }
.modal-confirm { background: linear-gradient(135deg, #C0192A, #9B1422); border: none; color: white; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s; }
.modal-confirm:hover { transform: translateY(-1px); }
</style>