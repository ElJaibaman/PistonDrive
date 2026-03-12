<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

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
const filtrados = () => props.vehiculos.data.filter(v =>
  v.marca.toLowerCase().includes(busqueda.value.toLowerCase()) ||
  v.modelo.toLowerCase().includes(busqueda.value.toLowerCase()) ||
  (v.placa && v.placa.toLowerCase().includes(busqueda.value.toLowerCase())) ||
  v.cliente.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
);
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-2" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">Vehículos</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('vehiculos.create')">
                <v-btn color="primary" prepend-icon="mdi-plus">Nuevo vehículo</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-row class="mb-3">
            <v-col cols="12" md="5">
              <v-text-field v-model="busqueda" label="Buscar por marca, modelo, placa o cliente"
                prepend-inner-icon="mdi-magnify" variant="outlined" density="compact" clearable hide-details />
            </v-col>
          </v-row>

          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>ID</th><th>Cliente</th><th>Placa</th><th>Marca</th>
                <th>Modelo</th><th>Año</th><th>Color</th><th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="v in filtrados()" :key="v.id">
                <td>{{ v.id }}</td>
                <td>{{ v.cliente.nombre }}</td>
                <td><v-chip size="small" color="blue" variant="outlined">{{ v.placa ?? 'Sin placa' }}</v-chip></td>
                <td>{{ v.marca }}</td>
                <td>{{ v.modelo }}</td>
                <td>{{ v.anio ?? '—' }}</td>
                <td>{{ v.color ?? '—' }}</td>
                <td>
                  <v-btn-group density="compact" variant="text">
                    <Link :href="route('vehiculos.edit', v.id)">
                      <v-btn icon="mdi-pencil" size="small" color="warning" />
                    </Link>
                    <v-btn icon="mdi-delete" size="small" color="error" @click="confirmarEliminar(v)" />
                  </v-btn-group>
                </td>
              </tr>
              <tr v-if="filtrados().length === 0">
                <td colspan="8" class="text-center text-grey py-4">Sin resultados</td>
              </tr>
            </tbody>
          </v-table>
        </v-container>
        </AuthenticatedLayout>
</template>
