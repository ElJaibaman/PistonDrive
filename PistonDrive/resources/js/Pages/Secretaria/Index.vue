<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ cotizaciones: Object });

const estadoCot = {
  pendiente: { color: 'orange', label: 'Pendiente revisión', icon: 'mdi-timer-sand' },
  aprobada:  { color: 'green',  label: 'Aprobada',           icon: 'mdi-check-circle' },
  rechazada: { color: 'red',    label: 'Rechazada',          icon: 'mdi-close-circle' },
};
</script>

<template>
  <AuthenticatedLayout>
        <v-container>

          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">Cotizaciones de mecánicos</h2>
            </v-col>
            <v-col cols="auto">
              <v-chip color="orange" prepend-icon="mdi-timer-sand">
                {{ cotizaciones.data.filter(c => c.estado === 'pendiente').length }} pendientes
              </v-chip>
            </v-col>
          </v-row>

          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>ID</th>
                <th>Orden</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Mecánico</th>
                <th class="text-right">Piezas (L.)</th>
                <th class="text-right">Mano obra (L.)</th>
                <th class="text-right">Total (L.)</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in cotizaciones.data" :key="c.id">
                <td>#{{ c.id }}</td>
                <td>#{{ c.orden.id }}</td>
                <td>{{ c.orden.vehiculo.cliente.nombre }}</td>
                <td>{{ c.orden.vehiculo.marca }} {{ c.orden.vehiculo.modelo }}</td>
                <td>{{ c.orden.mecanico?.nombre ?? '—' }}</td>
                <td class="text-right">{{ parseFloat(c.total_piezas).toFixed(2) }}</td>
                <td class="text-right">{{ parseFloat(c.total_mano_obra).toFixed(2) }}</td>
                <td class="text-right"><strong>{{ parseFloat(c.total_general).toFixed(2) }}</strong></td>
                <td>
                  <v-chip
                    :color="estadoCot[c.estado].color"
                    :prepend-icon="estadoCot[c.estado].icon"
                    size="small"
                  >
                    {{ estadoCot[c.estado].label }}
                  </v-chip>
                </td>
                <td>
                  <Link :href="route('secretaria.cotizaciones.show', c.id)">
                    <v-btn
                      :color="c.estado === 'pendiente' ? 'warning' : 'info'"
                      :icon="c.estado === 'pendiente' ? 'mdi-file-document-edit' : 'mdi-eye'"
                      size="small"
                      variant="text"
                    />
                  </Link>
                </td>
              </tr>
              <tr v-if="cotizaciones.data.length === 0">
                <td colspan="10" class="text-center text-grey py-6">
                  No hay cotizaciones registradas
                </td>
              </tr>
            </tbody>
          </v-table>

        </v-container>
        </AuthenticatedLayout>
</template>
