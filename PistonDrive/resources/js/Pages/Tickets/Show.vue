<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ ticket: Object });

const orden      = computed(() => props.ticket?.orden);
const cotizacion = computed(() => orden.value?.cotizacion);
const garantia   = computed(() => props.ticket?.garantia);

const total = computed(() =>
    cotizacion.value
        ? parseFloat(cotizacion.value.total_general)
        : parseFloat(props.ticket.total)
);

const diasRestantes = computed(() => {
    if (!garantia.value) return null;
    return Math.max(0, Math.ceil(
        (new Date(garantia.value.fecha_vencimiento) - new Date()) / 86400000
    ));
});

const garantiaConfig = computed(() => {
    if (!garantia.value) return null;
    if (garantia.value.estado === 'vigente' && diasRestantes.value > 0)
        return { bg: 'rgba(72,187,120,0.08)', border: 'rgba(72,187,120,0.25)', color: '#276749', dot: '#48BB78', label: 'Vigente' };
    if (garantia.value.estado === 'vencida' || diasRestantes.value === 0)
        return { bg: 'rgba(192,25,42,0.06)',  border: 'rgba(192,25,42,0.2)',  color: '#9B1422', dot: '#C0192A', label: 'Vencida'  };
    return { bg: '#F7FAFC', border: '#E2E8F0', color: '#A0AEC0', dot: '#CBD5E0', label: 'Anulada' };
});

const pagoConfig = {
    efectivo:      { bg: 'rgba(72,187,120,0.1)',  color: '#276749', icon: '💵' },
    tarjeta:       { bg: 'rgba(43,108,176,0.1)',  color: '#2B6CB0', icon: '💳' },
    transferencia: { bg: 'rgba(128,90,213,0.1)',  color: '#553C9A', icon: '🏦' },
};

function iniciales(nombre) {
    if (!nombre) return '?';
    return nombre.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="pd-page" v-if="ticket && orden">

            <!-- Breadcrumb -->
            <div class="pd-breadcrumb">
                <Link :href="route('tickets.index')" class="breadcrumb-link">← Tickets</Link>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">Ticket #{{ ticket.id }}</span>
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
                        <h1 class="header-title">Ticket #{{ ticket.id }}</h1>
                        <p class="header-sub">{{ orden.vehiculo?.marca }} {{ orden.vehiculo?.modelo }} · {{ orden.vehiculo?.cliente?.nombre }}</p>
                    </div>
                </div>
                <div class="header-actions">
                    <Link :href="route('ordenes.show', orden.id)">
                        <button class="btn-orden">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                            Ver orden #{{ orden.id }}
                        </button>
                    </Link>
                    <Link :href="route('tickets.index')">
                        <button class="btn-back">← Volver</button>
                    </Link>
                </div>
            </div>
            <div class="header-line" />

            <!-- Banner garantía -->
            <div class="pd-alerts" v-if="garantia">
                <div class="garantia-banner" :style="{ background: garantiaConfig?.bg, border: '1px solid ' + garantiaConfig?.border }">
                    <div class="garantia-shield">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" :stroke="garantiaConfig?.dot" stroke-width="2" stroke-linecap="round"/>
                            <path v-if="diasRestantes > 0" d="M9 12l2 2 4-4" :stroke="garantiaConfig?.dot" stroke-width="2" stroke-linecap="round"/>
                            <path v-else d="M15 9l-6 6M9 9l6 6" :stroke="garantiaConfig?.dot" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="garantia-body">
                        <span class="garantia-title" :style="{ color: garantiaConfig?.color }">
                            Garantía {{ garantiaConfig?.label }}
                        </span>
                        <span class="garantia-meta">
                            Vence el {{ garantia.fecha_vencimiento }}
                            <span v-if="diasRestantes > 0"> · {{ diasRestantes }} días restantes</span>
                            <span v-else> · Vencida</span>
                        </span>
                    </div>
                    <div class="garantia-dias" v-if="diasRestantes > 0" :style="{ color: garantiaConfig?.color }">
                        <span class="dias-num">{{ diasRestantes }}</span>
                        <span class="dias-label">días</span>
                    </div>
                </div>
            </div>

            <div class="pd-content">
                <div class="main-grid">

                    <!-- Columna izquierda -->
                    <div class="col-left">

                        <!-- Datos cliente/vehículo -->
                        <div class="info-card mb-4">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(192,25,42,0.08)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#C0192A"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
                                </div>
                                <span class="info-card-title">Cliente y vehículo</span>
                            </div>
                            <div class="info-grid">
                                <div class="info-item"><span class="info-key">Cliente</span><span class="info-val bold">{{ orden.vehiculo?.cliente?.nombre }}</span></div>
                                <div class="info-item"><span class="info-key">Teléfono</span><span class="info-val">{{ orden.vehiculo?.cliente?.telefono ?? '—' }}</span></div>
                                <div class="info-item"><span class="info-key">Vehículo</span><span class="info-val bold">{{ orden.vehiculo?.marca }} {{ orden.vehiculo?.modelo }}</span></div>
                                <div class="info-item"><span class="info-key">Placa</span>
                                    <span v-if="orden.vehiculo?.placa" class="placa-badge">{{ orden.vehiculo.placa }}</span>
                                    <span v-else class="info-val">—</span>
                                </div>
                                <div class="info-item"><span class="info-key">Mecánico</span>
                                    <div v-if="orden.mecanico" class="mecanico-wrap">
                                        <div class="mecanico-av">{{ iniciales(orden.mecanico.nombre) }}</div>
                                        <span class="info-val">{{ orden.mecanico.nombre }}</span>
                                    </div>
                                    <span v-else class="info-val">—</span>
                                </div>
                                <div class="info-item"><span class="info-key">Fecha emisión</span><span class="info-val mono">{{ ticket.created_at?.slice(0,10) }}</span></div>
                            </div>
                        </div>

                        <!-- Detalle cotización -->
                        <div class="cotiz-card" v-if="cotizacion">
                            <div class="cotiz-bar" />
                            <div class="cotiz-card-header">
                                <div class="cotiz-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#C0192A"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8" fill="white"/></svg>
                                </div>
                                <span class="info-card-title">Detalle de servicios</span>
                            </div>
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
                                    <tr v-for="item in cotizacion.items" :key="item.id" class="cotiz-row">
                                        <td>
                                            <span class="tipo-chip" :class="item.tipo === 'pieza' ? 'tipo-pieza' : 'tipo-mo'">
                                                {{ item.tipo === 'pieza' ? 'Pieza' : 'Servicio' }}
                                            </span>
                                        </td>
                                        <td class="desc-cell">{{ item.descripcion }}</td>
                                        <td class="text-center">{{ item.cantidad }}</td>
                                        <td class="text-right mono">L. {{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                                        <td class="text-right mono bold">L. {{ parseFloat(item.subtotal).toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="foot-row">
                                        <td colspan="4" class="text-right foot-label">Total piezas</td>
                                        <td class="text-right mono" style="color:#2B6CB0;font-weight:800">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</td>
                                    </tr>
                                    <tr class="foot-row">
                                        <td colspan="4" class="text-right foot-label">Total mano de obra</td>
                                        <td class="text-right mono" style="color:#C0192A;font-weight:800">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-right">

                        <!-- Total card -->
                        <div class="total-card mb-4">
                            <div class="total-bar" />
                            <div class="total-body">
                                <div class="total-label">Total cobrado</div>
                                <div class="total-amount">L. {{ total.toFixed(2) }}</div>
                                <div class="pago-badge" v-if="ticket.metodo_pago" :style="{ background: pagoConfig[ticket.metodo_pago]?.bg, color: pagoConfig[ticket.metodo_pago]?.color }">
                                    <span>{{ pagoConfig[ticket.metodo_pago]?.icon }}</span>
                                    <span>{{ ticket.metodo_pago }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="notas-card mb-4" v-if="ticket.notas">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:#F7FAFC">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#A0AEC0"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                </div>
                                <span class="info-card-title">Notas</span>
                            </div>
                            <p class="notas-text">{{ ticket.notas }}</p>
                        </div>

                        <!-- Orden info -->
                        <div class="orden-card">
                            <div class="info-card-header">
                                <div class="info-icon" style="background:rgba(74,85,104,0.08)">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2" stroke="#4A5568" stroke-width="1.5" stroke-linecap="round"/></svg>
                                </div>
                                <span class="info-card-title">Orden #{{ orden.id }}</span>
                            </div>
                            <div class="orden-rows">
                                <div class="orden-row"><span class="info-key">Ingreso</span><span class="info-val mono">{{ orden.fecha_ingreso }}</span></div>
                                <div class="orden-row"><span class="info-key">Entrega</span><span class="info-val mono">{{ orden.fecha_entrega ?? '—' }}</span></div>
                                <div class="orden-row" v-if="orden.kilometraje"><span class="info-key">Kilometraje</span><span class="info-val">{{ orden.kilometraje }} km</span></div>
                            </div>
                            <div class="orden-footer">
                                <Link :href="route('ordenes.show', orden.id)">
                                    <button class="btn-ver-orden">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                                        Ver orden completa
                                    </button>
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

.pd-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 32px 0; flex-wrap: wrap; gap: 12px; }
.header-left { display: flex; align-items: center; gap: 14px; }
.header-icon { width: 44px; height: 44px; background: linear-gradient(135deg, #C0192A, #9B1422); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px rgba(192,25,42,0.35); flex-shrink: 0; }
.header-title { font-size: 1.3rem; font-weight: 800; color: #1A202C; margin: 0; }
.header-sub { font-size: 0.8rem; color: #A0AEC0; margin: 3px 0 0; }
.header-actions { display: flex; align-items: center; gap: 8px; }
.header-line { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 30%, transparent); margin: 16px 32px 0; border-radius: 2px; }

.btn-back { background: white; border: 1.5px solid #E2E8F0; color: #718096; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: border-color 0.15s; }
.btn-back:hover { border-color: #C0192A; color: #C0192A; }
.btn-orden { display: flex; align-items: center; gap: 6px; background: rgba(74,85,104,0.08); color: #4A5568; border: none; border-radius: 10px; padding: 0 14px; height: 38px; font-size: 0.82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s; }
.btn-orden:hover { background: rgba(74,85,104,0.15); }

.pd-alerts { padding: 16px 32px 0; }
.garantia-banner { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-radius: 14px; }
.garantia-shield { width: 40px; height: 40px; border-radius: 10px; background: white; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
.garantia-body { flex: 1; }
.garantia-title { font-size: 0.875rem; font-weight: 800; display: block; margin-bottom: 2px; }
.garantia-meta { font-size: 0.78rem; color: #718096; }
.garantia-dias { text-align: center; }
.dias-num { display: block; font-size: 1.6rem; font-weight: 900; line-height: 1; }
.dias-label { font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7; }

.pd-content { padding: 20px 32px 40px; }
.main-grid { display: grid; grid-template-columns: 1fr 280px; gap: 24px; }
.col-left, .col-right { display: flex; flex-direction: column; }
.mb-4 { margin-bottom: 16px; }

.info-card, .cotiz-card, .notas-card, .orden-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.04); }
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
.mecanico-wrap { display: flex; align-items: center; gap: 7px; }
.mecanico-av { width: 24px; height: 24px; border-radius: 6px; background: linear-gradient(135deg, #4A5568, #2D3748); display: flex; align-items: center; justify-content: center; font-size: 0.55rem; font-weight: 800; color: white; flex-shrink: 0; }

.cotiz-bar { height: 3px; background: linear-gradient(to right, #C0192A, #9B1422 50%, #2D3748); }
.cotiz-card-header { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #EDF2F7; }
.cotiz-icon { width: 26px; height: 26px; background: rgba(192,25,42,0.08); border-radius: 7px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.cotiz-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
.cotiz-table thead tr { background: #F7FAFC; border-bottom: 1px solid #EDF2F7; }
.cotiz-table th { padding: 9px 16px; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: #A0AEC0; text-align: left; }
.cotiz-table td { padding: 10px 16px; color: #4A5568; border-bottom: 1px solid #F7FAFC; }
.cotiz-row:hover { background: #FAFBFC; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.desc-cell { max-width: 200px; }
.tipo-chip { padding: 2px 8px; border-radius: 6px; font-size: 0.7rem; font-weight: 700; }
.tipo-pieza { background: rgba(43,108,176,0.1); color: #2B6CB0; }
.tipo-mo    { background: rgba(192,25,42,0.1);  color: #9B1422; }
.foot-row td { padding: 10px 16px; background: #F7FAFC; border-top: 1px solid #EDF2F7; }
.foot-label { color: #4A5568; font-weight: 600; font-size: 0.82rem; }

.total-card { background: white; border-radius: 14px; border: 1px solid #EDF2F7; overflow: hidden; box-shadow: 0 4px 20px rgba(192,25,42,0.08); }
.total-bar { height: 4px; background: linear-gradient(to right, #C0192A, #9B1422 50%, #2D3748); }
.total-body { padding: 24px 20px; text-align: center; }
.total-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #A0AEC0; margin-bottom: 8px; }
.total-amount { font-size: 2rem; font-weight: 900; color: #C0192A; font-family: monospace; letter-spacing: -1px; margin-bottom: 14px; }
.pago-badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; text-transform: capitalize; }

.notas-card .notas-text { padding: 14px 20px; font-size: 0.875rem; color: #4A5568; line-height: 1.6; margin: 0; font-style: italic; }

.orden-rows { padding: 8px 0; }
.orden-row { display: flex; justify-content: space-between; padding: 8px 20px; }
.orden-row:hover { background: #FAFBFC; }
.orden-footer { padding: 12px 20px; border-top: 1px solid #EDF2F7; }
.btn-ver-orden { display: flex; align-items: center; gap: 6px; background: rgba(74,85,104,0.08); color: #4A5568; border: none; border-radius: 8px; padding: 7px 14px; font-size: 0.8rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background 0.12s; width: 100%; justify-content: center; }
.btn-ver-orden:hover { background: rgba(74,85,104,0.15); }

@media (max-width: 768px) { .main-grid { grid-template-columns: 1fr; } .info-grid { grid-template-columns: 1fr; } .pd-page { padding-bottom: 32px; } }
</style>