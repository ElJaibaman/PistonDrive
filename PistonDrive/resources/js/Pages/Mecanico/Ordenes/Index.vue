<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  ordenes:  Array,
  mecanico: Object,
});

const estadoConfig = {
  pendiente:            { color: 'grey',       icon: 'mdi-clock-outline',     label: 'Pendiente'            },
  en_proceso:           { color: 'blue',       icon: 'mdi-progress-wrench',   label: 'En proceso'           },
  esperando_aprobacion: { color: 'orange',     icon: 'mdi-timer-sand',        label: 'Esperando aprobación' },
  aprobado:             { color: 'green',      icon: 'mdi-check-circle',      label: 'Aprobado'             },
  rechazado:            { color: 'red',        icon: 'mdi-close-circle',      label: 'Rechazado'            },
  terminado:            { color: 'teal',       icon: 'mdi-flag-checkered',    label: 'Terminado'            },
  entregado:            { color: 'purple',     icon: 'mdi-car-arrow-right',   label: 'Entregado'            },
};
</script>

<template>
  <AuthenticatedLayout>
        <v-container>

          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">Mis órdenes asignadas</h2>
            </v-col>
          </v-row>

          <!-- Tarjetas resumen por estado -->
          <v-row class="mb-4">
            <v-col
              v-for="(cfg, key) in estadoConfig"
              :key="key"
              cols="6"
              md="3"
            >
              <v-card variant="flat" :color="cfg.color + '-lighten-5'">
                <v-card-text class="text-center pa-3">
                  <v-icon :color="cfg.color" size="26">{{ cfg.icon }}</v-icon>
                  <div class="text-h5 font-weight-bold mt-1" :class="'text-' + cfg.color">
                    {{ ordenes.filter(o => o.estado === key).length }}
                  </div>
                  <div class="text-caption" :class="'text-' + cfg.color">{{ cfg.label }}</div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <!-- Tabla de órdenes -->
          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>Orden</th>
                <th>Cliente</th>
                <th>Vehículo</th>
                <th>Placa</th>
                <th>Ingreso</th>
                <th>Estado</th>
                <th>Cotización</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="orden in ordenes" :key="orden.id">
                <td>#{{ orden.id }}</td>
                <td>{{ orden.vehiculo.cliente.nombre }}</td>
                <td>{{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</td>
                <td>
                  <v-chip size="x-small" variant="outlined" color="blue">
                    {{ orden.vehiculo.placa ?? 'S/P' }}
                  </v-chip>
                </td>
                <td>{{ orden.fecha_ingreso }}</td>
                <td>
                  <v-chip
                    :color="estadoConfig[orden.estado]?.color"
                    :prepend-icon="estadoConfig[orden.estado]?.icon"
                    size="small"
                    dark
                  >
                    {{ estadoConfig[orden.estado]?.label }}
                  </v-chip>
                </td>
                <td>
                  <v-chip
                    v-if="orden.cotizacion"
                    :color="orden.cotizacion.estado === 'aprobada' ? 'green'
                          : orden.cotizacion.estado === 'rechazada' ? 'red' : 'orange'"
                    size="x-small"
                  >
                    {{ orden.cotizacion.estado }}
                  </v-chip>
                  <span v-else class="text-grey text-caption">Sin cotización</span>
                </td>
                <td>
                  <v-btn-group density="compact" variant="text">
                    <Link :href="route('mecanico.ordenes.show', orden.id)">
                      <v-btn icon="mdi-eye" size="small" color="info" />
                    </Link>
                    <Link
                      v-if="orden.estado === 'en_proceso' ||
                            (orden.estado === 'rechazado' && orden.cotizacion?.estado === 'rechazada') ||
                            (orden.estado === 'aprobado')"
                      :href="route('mecanico.cotizacion.create', orden.id)"
                    >
                      <v-btn icon="mdi-file-document-plus" size="small" color="warning" />
                    </Link>
                  </v-btn-group>
                </td>
              </tr>
              <tr v-if="ordenes.length === 0">
                <td colspan="8" class="text-center text-grey py-6">
                  No tienes órdenes asignadas
                </td>
              </tr>
            </tbody>
          </v-table>

        </v-container>
        </AuthenticatedLayout>
</template>
