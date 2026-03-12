<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ ordenes: Object, estados: Object });

const dialog = ref(false);
const aEliminar = ref(null);

function confirmarEliminar(o) { aEliminar.value = o; dialog.value = true; }
function eliminar() {
  router.delete(route('ordenes.destroy', aEliminar.value.id), {
    onFinish: () => { dialog.value = false; },
  });
}
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-2" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">Órdenes de trabajo</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('ordenes.create')">
                <v-btn color="primary" prepend-icon="mdi-plus">Nueva orden</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>ID</th><th>Cliente</th><th>Vehículo</th><th>Mecánico</th>
                <th>Ingreso</th><th>Estado</th><th>Cotización</th><th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="o in ordenes.data" :key="o.id">
                <td>#{{ o.id }}</td>
                <td>{{ o.vehiculo.cliente.nombre }}</td>
                <td>{{ o.vehiculo.marca }} {{ o.vehiculo.modelo }}</td>
                <td>{{ o.mecanico?.nombre ?? '—' }}</td>
                <td>{{ o.fecha_ingreso }}</td>
                <td>
                  <v-chip :color="estados[o.estado]?.color" size="small" dark>
                    {{ estados[o.estado]?.label }}
                  </v-chip>
                </td>
                <td>
                  <v-chip v-if="o.cotizacion"
                    :color="o.cotizacion.estado === 'aprobada' ? 'green' : o.cotizacion.estado === 'rechazada' ? 'red' : 'orange'"
                    size="x-small">
                    {{ o.cotizacion.estado }}
                  </v-chip>
                  <span v-else class="text-caption text-grey">—</span>
                </td>
                <td>
                  <v-btn-group density="compact" variant="text">
                    <Link :href="route('ordenes.show', o.id)">
                      <v-btn icon="mdi-eye" size="small" color="info" />
                    </Link>
                    <Link :href="route('ordenes.edit', o.id)">
                      <v-btn icon="mdi-pencil" size="small" color="warning" />
                    </Link>
                    <v-btn icon="mdi-delete" size="small" color="error" @click="confirmarEliminar(o)" />
                  </v-btn-group>
                </td>
              </tr>
            </tbody>
          </v-table>
        </v-container>
        </AuthenticatedLayout>
</template>
