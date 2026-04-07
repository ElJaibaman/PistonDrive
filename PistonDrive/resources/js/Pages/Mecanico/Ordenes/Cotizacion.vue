<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orden:             Object,
    serviciosManoObra: Array,
});

const form = useForm({
    nota_mecanico: '',
    piezas:    [{ descripcion: '', cantidad: 1, precio_unitario: '' }],
    mano_obra: [{ descripcion: '', cantidad: 1, precio_unitario: '' }],
});

function agregarPieza()   { form.piezas.push({ descripcion: '', cantidad: 1, precio_unitario: '' }); }
function quitarPieza(i)   { if (form.piezas.length > 1) form.piezas.splice(i, 1); }
function agregarManoObra() { form.mano_obra.push({ descripcion: '', cantidad: 1, precio_unitario: '' }); }
function quitarManoObra(i){ if (form.mano_obra.length > 1) form.mano_obra.splice(i, 1); }

const totalPiezas = computed(() =>
    form.piezas.reduce((s, p) => s + (parseFloat(p.precio_unitario) || 0) * (parseInt(p.cantidad) || 0), 0)
);
const totalManoObra = computed(() =>
    form.mano_obra.reduce((s, m) => s + (parseFloat(m.precio_unitario) || 0) * (parseInt(m.cantidad) || 0), 0)
);
const totalGeneral = computed(() => totalPiezas.value + totalManoObra.value);

function subtotalFila(item) {
    return ((parseFloat(item.precio_unitario) || 0) * (parseInt(item.cantidad) || 0)).toFixed(2);
}

function submit() {
    form.post(route('mecanico.cotizacion.store', props.orden.id));
}

// Combobox para servicios de mano de obra
const moSugerencias = computed(() => props.serviciosManoObra || []);
function filtrarSugerencias(texto) {
    if (!texto) return moSugerencias.value;
    return moSugerencias.value.filter(s => s.toLowerCase().includes(texto.toLowerCase()));
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('mecanico.ordenes.index')" class="breadcrumb-link">← Mis órdenes</Link>
                <span class="breadcrumb-sep">/</span>
                <Link :href="route('mecanico.ordenes.show', orden.id)" class="breadcrumb-link">Orden #{{ orden.id }}</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">Nueva cotización</span>
            </div>

            <!-- Header info del vehículo -->
            <div class="vehiculo-header">
                <div class="vh-left">
                    <div class="vh-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <polyline points="14,2 14,8 20,8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <line x1="12" y1="18" x2="12" y2="12" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <line x1="9" y1="15" x2="15" y2="15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="vh-title">Cotización — Orden #{{ orden.id }}</h1>
                        <p class="vh-sub">
                            <strong>{{ orden.vehiculo.cliente.nombre }}</strong>
                            &nbsp;·&nbsp;
                            {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}
                            <span v-if="orden.vehiculo.placa" class="placa-inline">{{ orden.vehiculo.placa }}</span>
                        </p>
                    </div>
                </div>
                <Link :href="route('mecanico.ordenes.show', orden.id)">
                    <button class="btn-back">← Volver</button>
                </Link>
            </div>
            <div class="header-line" />

            <div class="pd-content">

                <!-- Nota diagnóstico -->
                <div class="form-section mb-5">
                    <div class="section-header">
                        <div class="section-dot" style="background:#4A5568" />
                        <span class="section-title">Nota de diagnóstico</span>
                        <span class="section-optional">Opcional</span>
                    </div>
                    <div class="note-wrapper">
                        <textarea
                            v-model="form.nota_mecanico"
                            class="note-input"
                            rows="3"
                            placeholder="Describe el problema encontrado o una nota para la secretaria..."
                        />
                    </div>
                    <span v-if="form.errors.nota_mecanico" class="error-msg">{{ form.errors.nota_mecanico }}</span>
                </div>

                <!-- PIEZAS -->
                <div class="form-section mb-5">
                    <div class="section-header">
                        <div class="section-dot" style="background:#2B6CB0" />
                        <span class="section-title">Piezas / Repuestos</span>
                        <span class="section-count">{{ form.piezas.length }} ítem{{ form.piezas.length !== 1 ? 's' : '' }}</span>
                    </div>

                    <div class="items-card">
                        <div class="items-bar" style="background:#2B6CB0" />
                        <div class="items-header-row">
                            <span style="flex:4">Descripción de la pieza</span>
                            <span style="flex:1.2;text-align:center">Cant.</span>
                            <span style="flex:2;text-align:right">Precio unit. (L.)</span>
                            <span style="flex:1.5;text-align:right">Subtotal</span>
                            <span style="flex:0.5" />
                        </div>

                        <div v-for="(pieza, i) in form.piezas" :key="'p'+i" class="item-row">
                            <div style="flex:4">
                                <input v-model="pieza.descripcion" type="text" class="pd-input" placeholder="Ej. Filtro de aceite, Pastillas de freno..." />
                            </div>
                            <div style="flex:1.2">
                                <input v-model="pieza.cantidad" type="number" min="1" class="pd-input text-center" />
                            </div>
                            <div style="flex:2">
                                <div class="price-field">
                                    <span class="price-prefix">L.</span>
                                    <input v-model="pieza.precio_unitario" type="number" step="0.01" min="0" class="pd-input" placeholder="0.00" />
                                </div>
                            </div>
                            <div style="flex:1.5;text-align:right">
                                <span class="subtotal-text">L. {{ subtotalFila(pieza) }}</span>
                            </div>
                            <div style="flex:0.5;display:flex;justify-content:center">
                                <button class="btn-remove" @click="quitarPieza(i)" :disabled="form.piezas.length === 1" title="Quitar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="items-footer">
                            <button class="btn-add-item" @click="agregarPieza">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                Agregar pieza
                            </button>
                            <div class="subtotal-row">
                                <span class="subtotal-label">Subtotal piezas</span>
                                <span class="subtotal-value" style="color:#2B6CB0">L. {{ totalPiezas.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MANO DE OBRA -->
                <div class="form-section mb-5">
                    <div class="section-header">
                        <div class="section-dot" style="background:#C0192A" />
                        <span class="section-title">Mano de obra</span>
                        <span class="section-count">{{ form.mano_obra.length }} ítem{{ form.mano_obra.length !== 1 ? 's' : '' }}</span>
                    </div>

                    <div class="items-card">
                        <div class="items-bar" style="background:#C0192A" />
                        <div class="items-header-row">
                            <span style="flex:4">Servicio</span>
                            <span style="flex:1.2;text-align:center">Cant.</span>
                            <span style="flex:2;text-align:right">Monto (L.)</span>
                            <span style="flex:1.5;text-align:right">Subtotal</span>
                            <span style="flex:0.5" />
                        </div>

                        <div v-for="(mo, i) in form.mano_obra" :key="'mo'+i" class="item-row">
                            <div style="flex:4;position:relative">
                                <input
                                    v-model="mo.descripcion"
                                    type="text"
                                    class="pd-input"
                                    placeholder="Selecciona o escribe un servicio..."
                                    list="servicios-list"
                                />
                                <datalist id="servicios-list">
                                    <option v-for="s in moSugerencias" :key="s" :value="s" />
                                </datalist>
                            </div>
                            <div style="flex:1.2">
                                <input v-model="mo.cantidad" type="number" min="1" class="pd-input text-center" />
                            </div>
                            <div style="flex:2">
                                <div class="price-field">
                                    <span class="price-prefix">L.</span>
                                    <input v-model="mo.precio_unitario" type="number" step="0.01" min="0" class="pd-input" placeholder="0.00" />
                                </div>
                            </div>
                            <div style="flex:1.5;text-align:right">
                                <span class="subtotal-text">L. {{ subtotalFila(mo) }}</span>
                            </div>
                            <div style="flex:0.5;display:flex;justify-content:center">
                                <button class="btn-remove" @click="quitarManoObra(i)" :disabled="form.mano_obra.length === 1" title="Quitar">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="items-footer">
                            <button class="btn-add-item btn-add-red" @click="agregarManoObra">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                                Agregar servicio
                            </button>
                            <div class="subtotal-row">
                                <span class="subtotal-label">Subtotal mano de obra</span>
                                <span class="subtotal-value" style="color:#C0192A">L. {{ totalManoObra.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RESUMEN TOTAL -->
                <div class="total-card">
                    <div class="total-card-bar" />
                    <div class="total-body">
                        <div class="total-rows">
                            <div class="total-row">
                                <span>Piezas / Repuestos</span>
                                <span>L. {{ totalPiezas.toFixed(2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Mano de obra</span>
                                <span>L. {{ totalManoObra.toFixed(2) }}</span>
                            </div>
                            <div class="total-sep" />
                            <div class="total-row total-row-main">
                                <span>TOTAL GENERAL</span>
                                <span>L. {{ totalGeneral.toFixed(2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="form-actions">
                    <button class="btn-send" :disabled="form.processing" @click="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><line x1="22" y1="2" x2="11" y2="13" stroke="white" stroke-width="2" stroke-linecap="round"/><polygon points="22,2 15,22 11,13 2,9" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        {{ form.processing ? 'Enviando...' : 'Enviar cotización' }}
                    </button>
                    <Link :href="route('mecanico.ordenes.show', orden.id)">
                        <button class="btn-cancel">Cancelar</button>
                    </Link>
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

.vehiculo-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 32px 0; }
.vh-left { display: flex; align-items: center; gap: 14px; }
.vh-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.vh-title { font-size: 1.3rem; font-weight: 800; color: #1A202C; margin: 0; letter-spacing: -0.3px; }
.vh-sub { font-size: 0.82rem; color: #718096; margin: 3px 0 0; }
.placa-inline { background: #EDF2F7; color: #2D3748; font-family: monospace; font-size: 0.75rem; font-weight: 700; padding: 2px 6px; border-radius: 4px; letter-spacing: 1px; margin-left: 6px; }
.btn-back { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 16px; height: 40px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-back:hover { border-color: #C0192A; color: #C0192A; }

.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); margin: 16px 32px 0; border-radius: 2px; }

.pd-content { padding: 24px 32px 40px; max-width: 900px; }

/* Secciones */
.form-section { }
.section-header { display: flex; align-items: center; gap: 8px; margin-bottom: 14px; }
.section-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.section-title { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.9px; color: #4A5568; }
.section-optional { font-size: 0.7rem; color: #CBD5E0; font-weight: 500; }
.section-count { font-size: 0.7rem; background: #EDF2F7; color: #718096; padding: 2px 8px; border-radius: 10px; font-weight: 600; }
.mb-5 { margin-bottom: 24px; }

/* Nota */
.note-wrapper { background: white; border: 1.5px solid #E2E8F0; border-radius: 12px; overflow: hidden; transition: border-color 0.15s; }
.note-wrapper:focus-within { border-color: #4A5568; }
.note-input { width: 100%; border: none; outline: none; padding: 14px; font-size: 0.875rem; color: #2D3748; font-family: inherit; resize: none; background: transparent; line-height: 1.6; }
.note-input::placeholder { color: #CBD5E0; }

/* Items card */
.items-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
.items-bar { height: 3px; }
.items-header-row { display: flex; align-items: center; gap: 12px; padding: 10px 16px; background: #F7FAFC; border-bottom: 1px solid #EDF2F7; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #A0AEC0; }
.item-row { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-bottom: 1px solid #F7FAFC; transition: background 0.1s; }
.item-row:hover { background: #FAFBFC; }
.item-row:last-of-type { border-bottom: none; }

.pd-input { width: 100%; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 8px; padding: 8px 10px; font-size: 0.83rem; color: #2D3748; outline: none; font-family: inherit; transition: border-color 0.15s, background 0.15s; }
.pd-input:focus { background: white; border-color: #C0192A; }
.pd-input::placeholder { color: #CBD5E0; }
.text-center { text-align: center; }

.price-field { display: flex; align-items: center; background: #F7F8FA; border: 1.5px solid #E2E8F0; border-radius: 8px; overflow: hidden; transition: border-color 0.15s; }
.price-field:focus-within { border-color: #C0192A; background: white; }
.price-prefix { padding: 0 8px; font-size: 0.78rem; font-weight: 700; color: #A0AEC0; background: #EDF2F7; height: 100%; display: flex; align-items: center; align-self: stretch; border-right: 1px solid #E2E8F0; white-space: nowrap; }
.price-field .pd-input { border: none; background: transparent; }
.price-field .pd-input:focus { border: none; background: transparent; }

.subtotal-text { font-size: 0.82rem; font-weight: 700; color: #2D3748; }

.btn-remove { width: 28px; height: 28px; border-radius: 7px; border: none; background: rgba(192,25,42,0.07); color: #C0192A; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: background 0.12s; }
.btn-remove:hover:not(:disabled) { background: rgba(192,25,42,0.14); }
.btn-remove:disabled { opacity: 0.3; cursor: not-allowed; }

.items-footer { display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-top: 1px solid #EDF2F7; background: #FAFBFC; }
.btn-add-item { display: flex; align-items: center; gap: 6px; background: rgba(43,108,176,0.08); color: #2B6CB0; border: none; border-radius: 8px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s; }
.btn-add-item:hover { background: rgba(43,108,176,0.15); }
.btn-add-red { background: rgba(192,25,42,0.08); color: #C0192A; }
.btn-add-red:hover { background: rgba(192,25,42,0.15); }

.subtotal-row { display: flex; align-items: center; gap: 12px; }
.subtotal-label { font-size: 0.78rem; color: #718096; font-weight: 600; }
.subtotal-value { font-size: 1rem; font-weight: 800; }

/* Total card */
.total-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); margin-bottom: 28px; max-width: 480px; margin-left: auto; }
.total-card-bar { height: 4px; background: linear-gradient(to right, #C0192A, #9B1422 50%, #2D3748); }
.total-body { padding: 20px 24px; }
.total-rows { display: flex; flex-direction: column; gap: 10px; }
.total-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: #4A5568; }
.total-sep { height: 1px; background: #EDF2F7; margin: 4px 0; }
.total-row-main { font-size: 1rem; font-weight: 800; color: #C0192A; }

/* Acciones */
.form-actions { display: flex; align-items: center; gap: 12px; }
.btn-send { display: flex; align-items: center; gap: 8px; background: linear-gradient(135deg, #C0192A, #9B1422); color: white; border: none; border-radius: 12px; padding: 0 28px; height: 48px; font-size: 0.9rem; font-weight: 700; cursor: pointer; font-family: inherit; box-shadow: 0 4px 16px rgba(192,25,42,0.4); transition: transform 0.15s, box-shadow 0.15s; }
.btn-send:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(192,25,42,0.5); }
.btn-send:disabled { opacity: 0.65; cursor: not-allowed; }
.btn-cancel { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 12px; padding: 0 24px; height: 48px; font-size: 0.875rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s, color 0.15s; }
.btn-cancel:hover { border-color: #C0192A; color: #C0192A; }
.error-msg { font-size: 0.75rem; color: #E53E3E; margin-top: 4px; display: block; }
</style>