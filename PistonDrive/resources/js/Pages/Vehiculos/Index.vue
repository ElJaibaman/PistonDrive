<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({ vehiculos: Object });
const busqueda = ref('');
const dialog = ref(false);
const aEliminar = ref(null);

function confirmarEliminar(v) { aEliminar.value = v; dialog.value = true; }
function eliminar() {
    router.delete(route('vehiculos.destroy', aEliminar.value.id), {
        onFinish: () => { dialog.value = false; },
    });
}

const filtrados = computed(() => props.vehiculos.data.filter(v =>
    v.marca.toLowerCase().includes(busqueda.value.toLowerCase()) ||
    v.modelo.toLowerCase().includes(busqueda.value.toLowerCase()) ||
    (v.placa && v.placa.toLowerCase().includes(busqueda.value.toLowerCase())) ||
    v.cliente.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
));

const totalVehiculos = computed(() => props.vehiculos.data.length);
const conPlaca = computed(() => props.vehiculos.data.filter(v => v.placa).length);

const marcas = computed(() => {
    const set = new Set(props.vehiculos.data.map(v => v.marca));
    return set.size;
});

const colorMap = {
    'negro': '#1A202C', 'blanco': '#E2E8F0', 'gris': '#718096', 'rojo': '#C0192A',
    'azul': '#2B6CB0', 'verde': '#276749', 'plateado': '#A0AEC0', 'beige': '#D4A574',
    'amarillo': '#D69E2E', 'naranja': '#C05621', 'cafe': '#744210', 'marrón': '#744210',
};
function dotColor(color) {
    if (!color) return '#CBD5E0';
    const key = color.toLowerCase();
    for (const [k, v] of Object.entries(colorMap)) {
        if (key.includes(k)) return v;
    }
    return '#A0AEC0';
}

function iniciales(nombre) {
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
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
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z" stroke="white" stroke-width="1.5" fill="none"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="hero-title">Vehículos</h1>
                            <p class="hero-sub">Registro de vehículos del taller</p>
                        </div>
                    </div>
                    <Link :href="route('vehiculos.create')">
                        <button class="btn-new">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="white" stroke-width="2.5" stroke-linecap="round"/></svg>
                            Nuevo vehículo
                        </button>
                    </Link>
                </div>

                <div class="hero-stats">
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#C0192A" />
                        <span class="stat-num">{{ totalVehiculos }}</span>
                        <span class="stat-label">Total vehículos</span>
                    </div>
                    <div class="stat-divider" />
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#4A5568" />
                        <span class="stat-num">{{ conPlaca }}</span>
                        <span class="stat-label">Con placa</span>
                    </div>
                    <div class="stat-divider" />
                    <div class="stat-pill">
                        <div class="stat-dot" style="background:#CBD5E0" />
                        <span class="stat-num">{{ marcas }}</span>
                        <span class="stat-label">Marcas distintas</span>
                    </div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="pd-toolbar">
                <div class="search-wrap">
                    <svg class="search-ico" width="16" height="16" viewBox="0 0 24 24" fill="none">
                        <circle cx="11" cy="11" r="8" stroke="#A0AEC0" stroke-width="1.8"/>
                        <path d="m21 21-4.35-4.35" stroke="#A0AEC0" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                    <input v-model="busqueda" class="search-input" placeholder="Buscar por marca, modelo, placa o cliente..." />
                    <button v-if="busqueda" class="search-clear" @click="busqueda = ''">✕</button>
                </div>
                <div class="results-badge" v-if="busqueda">
                    {{ filtrados.length }} resultado{{ filtrados.length !== 1 ? 's' : '' }}
                </div>
            </div>

            <!-- Tabla -->
            <div class="pd-table-wrap">
                <div class="table-top-bar" />
                <table class="pd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Placa</th>
                            <th>Vehículo</th>
                            <th>Año</th>
                            <th>Color</th>
                            <th class="col-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="v in filtrados" :key="v.id" class="pd-row">
                            <td class="cell-id">
                                <span class="id-badge">#{{ v.id }}</span>
                            </td>
                            <td class="cell-data">
                                <div class="owner-wrap">
                                    <div class="owner-avatar">{{ iniciales(v.cliente.nombre) }}</div>
                                    <span class="owner-name">{{ v.cliente.nombre }}</span>
                                </div>
                            </td>
                            <td class="cell-data">
                                <span v-if="v.placa" class="placa-badge">{{ v.placa }}</span>
                                <span v-else class="data-empty">Sin placa</span>
                            </td>
                            <td class="cell-data">
                                <div class="vehiculo-wrap">
                                    <div class="vehiculo-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="#9B1422">
                                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="vehiculo-nombre">{{ v.marca }} {{ v.modelo }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="cell-data">
                                <span v-if="v.anio" class="anio-badge">{{ v.anio }}</span>
                                <span v-else class="data-empty">—</span>
                            </td>
                            <td class="cell-data">
                                <div v-if="v.color" class="color-wrap">
                                    <div class="color-dot" :style="{ background: dotColor(v.color) }" />
                                    <span>{{ v.color }}</span>
                                </div>
                                <span v-else class="data-empty">—</span>
                            </td>
                            <td class="cell-center">
                                <div class="actions-wrap">
                                    <Link :href="route('vehiculos.edit', v.id)">
                                        <button class="btn-action btn-edit" title="Editar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </Link>
                                    <button class="btn-action btn-del" title="Eliminar" @click="confirmarEliminar(v)">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                            <polyline points="3,6 5,6 21,6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filtrados.length === 0">
                            <td colspan="7" class="empty-cell">
                                <div class="empty-wrap">
                                    <div class="empty-icon-ring">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z" stroke="#CBD5E0" stroke-width="1.5" fill="none"/>
                                        </svg>
                                    </div>
                                    <p class="empty-title">No se encontraron vehículos</p>
                                    <p class="empty-sub" v-if="busqueda">Intenta con otro término de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
                    <h3 class="modal-title">¿Eliminar vehículo?</h3>
                    <p class="modal-desc">
                        Vas a eliminar <strong>{{ aEliminar?.marca }} {{ aEliminar?.modelo }}</strong>.<br>
                        Esta acción es permanente e irreversible.
                    </p>
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
.hero-icon-wrap {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, #C0192A, #9B1422);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 6px 18px rgba(192,25,42,0.35); flex-shrink: 0;
}
.hero-title { font-size: 1.55rem; font-weight: 800; color: #1A202C; margin: 0; letter-spacing: -0.5px; }
.hero-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }

.btn-new {
    display: flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg, #C0192A, #9B1422);
    color: white; border: none; border-radius: 12px;
    padding: 0 22px; height: 46px; font-size: 0.875rem; font-weight: 700;
    cursor: pointer; box-shadow: 0 4px 16px rgba(192,25,42,0.4);
    transition: transform 0.15s, box-shadow 0.15s; font-family: inherit;
}
.btn-new:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(192,25,42,0.5); }

.hero-stats { display: flex; align-items: center; border-top: 1px solid #EDF2F7; }
.stat-pill { display: flex; align-items: center; gap: 8px; padding: 14px 24px 14px 0; margin-right: 24px; }
.stat-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.stat-num { font-size: 1.1rem; font-weight: 800; color: #2D3748; }
.stat-label { font-size: 0.78rem; color: #A0AEC0; font-weight: 500; }
.stat-divider { width: 1px; height: 20px; background: #EDF2F7; margin-right: 24px; }

.pd-toolbar { padding: 20px 32px; display: flex; align-items: center; gap: 16px; }
.search-wrap { position: relative; display: flex; align-items: center; max-width: 420px; flex: 1; }
.search-ico { position: absolute; left: 14px; pointer-events: none; }
.search-input {
    width: 100%; height: 44px; background: white;
    border: 1.5px solid #E2E8F0; border-radius: 12px;
    padding: 0 40px 0 42px; font-size: 0.875rem; color: #2D3748;
    outline: none; font-family: inherit; transition: border-color 0.15s, box-shadow 0.15s;
}
.search-input::placeholder { color: #CBD5E0; }
.search-input:focus { border-color: #C0192A; box-shadow: 0 0 0 3px rgba(192,25,42,0.1); }
.search-clear { position: absolute; right: 12px; background: none; border: none; color: #A0AEC0; cursor: pointer; font-size: 0.75rem; padding: 4px; }
.results-badge { background: rgba(192,25,42,0.08); color: #C0192A; font-size: 0.75rem; font-weight: 700; padding: 6px 12px; border-radius: 8px; white-space: nowrap; }

.pd-table-wrap { margin: 0 32px 32px; background: white; border-radius: 16px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.05); }
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
.data-empty { color: #E2E8F0; font-size: 0.85rem; }

.owner-wrap { display: flex; align-items: center; gap: 10px; }
.owner-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, #4A5568, #2D3748);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; font-weight: 800; color: white; flex-shrink: 0; letter-spacing: 0.5px;
}
.owner-name { font-weight: 600; color: #2D3748; font-size: 0.85rem; }

.placa-badge {
    display: inline-block; background: #EDF2F7; color: #2D3748;
    font-size: 0.78rem; font-weight: 700; padding: 3px 10px;
    border-radius: 6px; letter-spacing: 1px; font-family: monospace;
    border: 1px solid #E2E8F0;
}

.vehiculo-wrap { display: flex; align-items: center; gap: 10px; }
.vehiculo-icon { width: 32px; height: 32px; background: rgba(192,25,42,0.06); border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.vehiculo-nombre { font-weight: 700; color: #1A202C; font-size: 0.875rem; }

.anio-badge { background: rgba(45,55,72,0.06); color: #2D3748; font-size: 0.8rem; font-weight: 700; padding: 3px 10px; border-radius: 6px; }

.color-wrap { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: #4A5568; }
.color-dot { width: 12px; height: 12px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,0.1); flex-shrink: 0; }

.actions-wrap { display: flex; align-items: center; justify-content: center; gap: 6px; }
.btn-action { width: 32px; height: 32px; border-radius: 8px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background 0.12s, transform 0.1s; }
.btn-action:hover { transform: scale(1.08); }
.btn-edit { background: rgba(74,85,104,0.08); color: #4A5568; }
.btn-edit:hover { background: rgba(74,85,104,0.16); }
.btn-del { background: rgba(192,25,42,0.08); color: #C0192A; }
.btn-del:hover { background: rgba(192,25,42,0.16); }

.empty-cell { padding: 80px 16px !important; }
.empty-wrap { text-align: center; }
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
.modal-cancel { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.modal-cancel:hover { border-color: #4A5568; color: #2D3748; }
.modal-confirm { background: linear-gradient(135deg, #C0192A, #9B1422); border: none; color: white; border-radius: 10px; padding: 0 24px; height: 44px; font-size: 0.875rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 14px rgba(192,25,42,0.35); transition: transform 0.15s, box-shadow 0.15s; }
.modal-confirm:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(192,25,42,0.45); }
</style>