<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  vehiculosActivos:       Array,
  ganancias:              Array,
  garantiasVigentes:      Array,
  cotizacionesPendientes: Array,
  resumen:                Object,
});

const totalGanancias = props.ganancias.reduce((s, g) => s + parseFloat(g.ingreso_total), 0);

function diasRestantes(fecha) {
  return Math.max(0, Math.ceil((new Date(fecha) - new Date()) / 86400000));
}
</script>

<template>
  <AuthenticatedLayout>
    <v-container>
      <h2 class="text-h6 font-weight-bold mb-4">Panel principal</h2>

      <!-- Tarjetas resumen -->
      <v-row class="mb-4">
        <v-col cols="6" md="2">
          <v-card color="grey-lighten-4" variant="flat">
            <v-card-text class="text-center pa-3">
              <v-icon size="28" color="grey">mdi-clock-outline</v-icon>
              <div class="text-h4 font-weight-bold mt-1">{{ resumen.pendientes }}</div>
              <div class="text-caption text-grey">Pendientes</div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="6" md="2">
          <v-card color="blue-lighten-5" variant="flat">
            <v-card-text class="text-center pa-3">
              <v-icon size="28" color="blue">mdi-progress-wrench</v-icon>
              <div class="text-h4 font-weight-bold mt-1 text-blue">{{ resumen.en_proceso }}</div>
              <div class="text-caption text-blue">En proceso</div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="6" md="2">
          <v-card color="orange-lighten-5" variant="flat">
            <v-card-text class="text-center pa-3">
              <v-icon size="28" color="orange">mdi-timer-sand</v-icon>
              <div class="text-h4 font-weight-bold mt-1 text-orange">{{ resumen.esperando_aprobacion }}</div>
              <div class="text-caption text-orange">Esp. aprobación</div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="6" md="2">
          <v-card color="teal-lighten-5" variant="flat">
            <v-card-text class="text-center pa-3">
              <v-icon size="28" color="teal">mdi-flag-checkered</v-icon>
              <div class="text-h4 font-weight-bold mt-1 text-teal">{{ resumen.terminados }}</div>
              <div class="text-caption text-teal">Terminados</div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="6" md="2">
          <Link :href="route('secretaria.cotizaciones.index')">
            <v-card color="deep-orange-lighten-5" variant="flat">
              <v-card-text class="text-center pa-3">
                <v-icon size="28" color="deep-orange">mdi-file-document-check</v-icon>
                <div class="text-h4 font-weight-bold mt-1 text-deep-orange">{{ resumen.cotizaciones_pendientes }}</div>
                <div class="text-caption text-deep-orange">Cotizaciones</div>
              </v-card-text>
            </v-card>
          </Link>
        </v-col>
      </v-row>

      <v-row>
        <!-- Vehículos activos -->
        <v-col cols="12" md="8">
          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-2">mdi-car-wrench</v-icon> Vehículos en el taller
            </v-card-title>
            <v-table density="compact">
              <thead>
                <tr>
                  <th>Cliente</th><th>Vehículo</th><th>Mecánico</th><th>Estado</th><th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="orden in vehiculosActivos" :key="orden.id">
                  <td>{{ orden.vehiculo.cliente.nombre }}</td>
                  <td>{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</td>
                  <td>{{ orden.mecanico?.nombre ?? '—' }}</td>
                  <td>
                    <v-chip :color="orden.estado_config.color" size="x-small">
                      {{ orden.estado_config.label }}
                    </v-chip>
                  </td>
                  <td>
                    <Link :href="route('ordenes.show', orden.id)">
                      <v-btn icon="mdi-eye" size="x-small" variant="text" color="info" />
                    </Link>
                  </td>
                </tr>
                <tr v-if="vehiculosActivos.length === 0">
                  <td colspan="5" class="text-center text-grey py-3">No hay vehículos activos</td>
                </tr>
              </tbody>
            </v-table>
          </v-card>

          <!-- Cotizaciones pendientes -->
          <v-card v-if="cotizacionesPendientes.length > 0">
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-2" color="orange">mdi-timer-sand</v-icon>
              Cotizaciones pendientes de revisión
            </v-card-title>
            <v-table density="compact">
              <thead>
                <tr><th>Orden</th><th>Cliente</th><th>Mecánico</th><th>Total (L.)</th><th></th></tr>
              </thead>
              <tbody>
                <tr v-for="c in cotizacionesPendientes" :key="c.id">
                  <td>#{{ c.orden.id }}</td>
                  <td>{{ c.orden.vehiculo.cliente.nombre }}</td>
                  <td>{{ c.orden.mecanico?.nombre ?? '—' }}</td>
                  <td><strong>{{ parseFloat(c.total_general).toFixed(2) }}</strong></td>
                  <td>
                    <Link :href="route('secretaria.cotizaciones.show', c.id)">
                      <v-btn size="x-small" color="warning" variant="tonal" prepend-icon="mdi-file-document-edit">
                        Revisar
                      </v-btn>
                    </Link>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-card>
        </v-col>

        <!-- Panel derecho -->
        <v-col cols="12" md="4">
          <!-- Ganancias -->
          <v-card class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-2">mdi-chart-line</v-icon> Ingresos (6 meses)
            </v-card-title>
            <v-card-text>
              <div v-for="g in ganancias" :key="g.mes" class="d-flex justify-space-between py-1">
                <span class="text-body-2">{{ g.mes }}</span>
                <strong class="text-body-2 text-green">L. {{ parseFloat(g.ingreso_total).toFixed(2) }}</strong>
              </div>
              <v-divider class="my-2" />
              <div class="d-flex justify-space-between">
                <strong>Total</strong>
                <strong class="text-primary">L. {{ totalGanancias.toFixed(2) }}</strong>
              </div>
            </v-card-text>
          </v-card>

          <!-- Garantías vigentes -->
          <v-card>
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-2" color="green">mdi-shield-check</v-icon> Garantías vigentes
            </v-card-title>
            <v-card-text>
              <div v-for="g in garantiasVigentes" :key="g.id" class="mb-3 pa-2 rounded" style="background:rgba(0,0,0,0.03)">
                <div class="text-body-2 font-weight-bold">{{ g.ticket?.orden?.vehiculo?.cliente?.nombre }}</div>
                <div class="text-caption text-grey">Vence: {{ g.fecha_vencimiento }}</div>
                <v-chip :color="diasRestantes(g.fecha_vencimiento) <= 5 ? 'orange' : 'green'" size="x-small" class="mt-1">
                  {{ diasRestantes(g.fecha_vencimiento) }} días restantes
                </v-chip>
              </div>
              <div v-if="garantiasVigentes.length === 0" class="text-grey text-body-2">Sin garantías activas</div>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </AuthenticatedLayout>
</template>