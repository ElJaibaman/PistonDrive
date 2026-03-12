<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({ cotizacion: Object });

const dialogAprobar  = ref(false);
const dialogRechazar = ref(false);

const formAprobar  = useForm({ nota_secretaria: '' });
const formRechazar = useForm({ nota_secretaria: '' });

function aprobar() {
  formAprobar.patch(route('secretaria.cotizaciones.aprobar', props.cotizacion.id), {
    onSuccess: () => { dialogAprobar.value = false; },
  });
}
function rechazar() {
  formRechazar.patch(route('secretaria.cotizaciones.rechazar', props.cotizacion.id), {
    onSuccess: () => { dialogRechazar.value = false; },
  });
}

const esPendiente = computed(() => props.cotizacion.estado === 'pendiente');
const orden = computed(() => props.cotizacion.orden);
</script>

<template>
  <AuthenticatedLayout>
        <v-container>

          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">
                Cotización #{{ cotizacion.id }} — Orden #{{ orden.id }}
              </h2>
            </v-col>
            <v-col cols="auto">
              <Link :href="route('secretaria.cotizaciones.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <!-- Estado banner -->
          <v-alert
            v-if="cotizacion.estado === 'aprobada'"
            type="success" class="mb-4" prepend-icon="mdi-check-circle"
          >
            Cotización <strong>aprobada</strong>.
            <span v-if="cotizacion.nota_secretaria"> Nota: {{ cotizacion.nota_secretaria }}</span>
          </v-alert>
          <v-alert
            v-else-if="cotizacion.estado === 'rechazada'"
            type="error" class="mb-4" prepend-icon="mdi-close-circle"
          >
            Cotización <strong>rechazada</strong>.
            <span v-if="cotizacion.nota_secretaria"> Motivo: {{ cotizacion.nota_secretaria }}</span>
          </v-alert>
          <v-alert
            v-else
            type="warning" class="mb-4" prepend-icon="mdi-timer-sand"
          >
            Cotización <strong>pendiente de revisión</strong>. Consulta con el cliente y toma una decisión.
          </v-alert>

          <!-- Datos del servicio -->
          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">Datos del servicio</v-card-title>
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
              <div v-if="cotizacion.nota_mecanico" class="mt-2">
                <strong>Nota del mecánico:</strong> {{ cotizacion.nota_mecanico }}
              </div>
            </v-card-text>
          </v-card>

          <!-- Piezas -->
          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon color="blue" class="mr-1">mdi-cog</v-icon> Piezas / repuestos
            </v-card-title>
            <v-table density="compact">
              <thead>
                <tr>
                  <th>Descripción</th>
                  <th class="text-center">Cant.</th>
                  <th class="text-right">Precio unit. (L.)</th>
                  <th class="text-right">Subtotal (L.)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in cotizacion.piezas" :key="item.id">
                  <td>{{ item.descripcion }}</td>
                  <td class="text-center">{{ item.cantidad }}</td>
                  <td class="text-right">{{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                  <td class="text-right">{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                </tr>
                <tr v-if="!cotizacion.piezas?.length">
                  <td colspan="4" class="text-grey text-center py-2">Sin piezas registradas</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Subtotal piezas</td>
                  <td class="text-right font-weight-bold text-blue">
                    L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}
                  </td>
                </tr>
              </tfoot>
            </v-table>
          </v-card>

          <!-- Mano de obra -->
          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon color="purple" class="mr-1">mdi-wrench</v-icon> Mano de obra
            </v-card-title>
            <v-table density="compact">
              <thead>
                <tr>
                  <th>Servicio</th>
                  <th class="text-center">Cant.</th>
                  <th class="text-right">Monto (L.)</th>
                  <th class="text-right">Subtotal (L.)</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in cotizacion.mano_obra" :key="item.id">
                  <td>{{ item.descripcion }}</td>
                  <td class="text-center">{{ item.cantidad }}</td>
                  <td class="text-right">{{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                  <td class="text-right">{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-right font-weight-bold">Subtotal mano de obra</td>
                  <td class="text-right font-weight-bold text-purple">
                    L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}
                  </td>
                </tr>
              </tfoot>
            </v-table>
          </v-card>

          <!-- Total general -->
          <v-card class="mb-6" color="blue-grey-lighten-5" variant="flat">
            <v-card-text class="text-right">
              <div class="text-h5 font-weight-bold text-primary">
                TOTAL GENERAL: L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}
              </div>
            </v-card-text>
          </v-card>

          <!-- Botones de acción -->
          <v-row v-if="esPendiente">
            <v-col cols="auto">
              <v-btn color="success" size="large" prepend-icon="mdi-check" @click="dialogAprobar = true">
                Aprobar cotización
              </v-btn>
            </v-col>
            <v-col cols="auto">
              <v-btn color="error" size="large" variant="outlined" prepend-icon="mdi-close" @click="dialogRechazar = true">
                Rechazar cotización
              </v-btn>
            </v-col>
          </v-row>

        </v-container>
        </AuthenticatedLayout>
</template>
