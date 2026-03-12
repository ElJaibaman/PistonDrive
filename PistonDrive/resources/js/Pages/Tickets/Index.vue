<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ tickets: Object });

const estadoGarantia = (g) => {
  if (!g) return { color: 'grey', label: 'Sin garantía' };
  return { vigente: { color: 'green', label: 'Vigente' }, vencida: { color: 'red', label: 'Vencida' }, anulada: { color: 'grey', label: 'Anulada' } }[g.estado];
};
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-2"><v-col><h2 class="text-h6 font-weight-bold">Tickets emitidos</h2></v-col></v-row>

          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>ID</th><th>Cliente</th><th>Vehículo</th>
                <th>Total (L.)</th><th>Pago</th><th>Garantía</th><th>Fecha</th><th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="t in tickets.data" :key="t.id">
                <td>#{{ t.id }}</td>
                <td>{{ t.orden.vehiculo.cliente.nombre }}</td>
                <td>{{ t.orden.vehiculo.marca }} {{ t.orden.vehiculo.modelo }}</td>
                <td><strong>{{ parseFloat(t.total).toFixed(2) }}</strong></td>
                <td><v-chip size="x-small" color="blue" variant="outlined">{{ t.metodo_pago }}</v-chip></td>
                <td>
                  <v-chip :color="estadoGarantia(t.garantia).color" size="x-small">
                    {{ estadoGarantia(t.garantia).label }}
                  </v-chip>
                </td>
                <td>{{ t.created_at?.slice(0, 10) }}</td>
                <td>
                  <Link :href="route('tickets.show', t.id)">
                    <v-btn icon="mdi-eye" size="small" color="info" variant="text" />
                  </Link>
                </td>
              </tr>
              <tr v-if="tickets.data.length === 0">
                <td colspan="8" class="text-center text-grey py-6">Sin tickets registrados</td>
              </tr>
            </tbody>
          </v-table>
        </v-container>
        </AuthenticatedLayout>
</template>
