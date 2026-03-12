<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ ticket: Object });

const orden = computed(() => props.ticket.orden);
const cotizacion = computed(() => orden.value?.cotizacion);
const garantia = computed(() => props.ticket.garantia);

const totalServicios = computed(() =>
  orden.value?.detalles?.reduce((s, d) => s + parseFloat(d.costo), 0) ?? 0
);
const total = computed(() =>
  cotizacion.value
    ? parseFloat(cotizacion.value.total_general)
    : parseFloat(props.ticket.total)
);

const diasRestantes = computed(() => {
  if (!garantia.value) return null;
  return Math.max(0, Math.ceil((new Date(garantia.value.fecha_vencimiento) - new Date()) / 86400000));
});

const form = useForm({
  orden_id:           orden.value?.id,
  subtotal_servicios: totalServicios.value,
  subtotal_repuestos: cotizacion.value ? parseFloat(cotizacion.value.total_piezas) : 0,
  total:              total.value,
  metodo_pago:        'efectivo',
  notas:              '',
});

const metodosPago = [
  { value: 'efectivo', title: 'Efectivo' },
  { value: 'tarjeta', title: 'Tarjeta' },
  { value: 'transferencia', title: 'Transferencia' },
];

function generarTicket() {
  form.post(route('tickets.store'));
}

const modoVista = computed(() => !!props.ticket.id && !!props.ticket.created_at);
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-4" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">Ticket #{{ ticket.id }}</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('ordenes.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-alert v-if="garantia" :type="diasRestantes > 0 ? 'success' : 'warning'" class="mb-4" prepend-icon="mdi-shield-check">
            Garantía {{ garantia.estado }} — vence el {{ garantia.fecha_vencimiento }}
            <span v-if="diasRestantes > 0">({{ diasRestantes }} días restantes)</span>
            <span v-else>(vencida)</span>
          </v-alert>

          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">Cliente y vehículo</v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="12" md="6">
                  <p><strong>Cliente:</strong> {{ orden.vehiculo.cliente.nombre }}</p>
                  <p><strong>Teléfono:</strong> {{ orden.vehiculo.cliente.telefono ?? '—' }}</p>
                </v-col>
                <v-col cols="12" md="6">
                  <p><strong>Vehículo:</strong> {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</p>
                  <p><strong>Placa:</strong> {{ orden.vehiculo.placa ?? '—' }}</p>
                  <p><strong>Mecánico:</strong> {{ orden.mecanico?.nombre ?? '—' }}</p>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>

          <!-- Resumen cotización aprobada -->
          <v-card v-if="cotizacion" class="mb-4">
            <v-card-title class="text-subtitle-1">Detalle de la cotización aprobada</v-card-title>
            <v-table density="compact">
              <thead>
                <tr><th>Tipo</th><th>Descripción</th><th class="text-center">Cant.</th><th class="text-right">Precio (L.)</th><th class="text-right">Subtotal (L.)</th></tr>
              </thead>
              <tbody>
                <tr v-for="item in cotizacion.items" :key="item.id">
                  <td>
                    <v-chip :color="item.tipo === 'pieza' ? 'blue' : 'purple'" size="x-small">
                      {{ item.tipo === 'pieza' ? 'Pieza' : 'Mano de obra' }}
                    </v-chip>
                  </td>
                  <td>{{ item.descripcion }}</td>
                  <td class="text-center">{{ item.cantidad }}</td>
                  <td class="text-right">{{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                  <td class="text-right">{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-right font-weight-bold text-primary">TOTAL GENERAL</td>
                  <td class="text-right font-weight-bold text-primary">L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}</td>
                </tr>
              </tfoot>
            </v-table>
          </v-card>

          <v-card class="mb-4" color="blue-grey-lighten-5" variant="flat">
            <v-card-text class="text-right">
              <div class="text-h5 font-weight-bold text-primary">TOTAL: L. {{ total.toFixed(2) }}</div>
              <div v-if="modoVista" class="text-body-2 mt-1">Método de pago: <strong>{{ ticket.metodo_pago }}</strong></div>
            </v-card-text>
          </v-card>

          <v-card v-if="!modoVista" class="mb-4">
            <v-card-title class="text-subtitle-1">Confirmar y emitir ticket</v-card-title>
            <v-card-text>
              <v-form @submit.prevent="generarTicket">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-select v-model="form.metodo_pago" :items="metodosPago" item-title="title" item-value="value"
                      label="Método de pago *" variant="outlined" density="compact" />
                  </v-col>
                  <v-col cols="12">
                    <v-textarea v-model="form.notas" label="Notas adicionales" variant="outlined" density="compact" rows="2" />
                  </v-col>
                </v-row>
                <v-btn type="submit" color="success" size="large" :loading="form.processing" prepend-icon="mdi-receipt-text-check">
                  Emitir ticket
                </v-btn>
              </v-form>
            </v-card-text>
          </v-card>

        </v-container>
        </AuthenticatedLayout>
</template>
