<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ clientes: Object });

const busqueda = ref('');
const dialog = ref(false);
const clienteAEliminar = ref(null);

function confirmarEliminar(c) { clienteAEliminar.value = c; dialog.value = true; }
function eliminar() {
  router.delete(route('clientes.destroy', clienteAEliminar.value.id), {
    onFinish: () => { dialog.value = false; },
  });
}
const filtrados = () => props.clientes.data.filter(c =>
  c.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) ||
  (c.telefono && c.telefono.includes(busqueda.value))
);
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-2" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">Clientes</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('clientes.create')">
                <v-btn color="primary" prepend-icon="mdi-plus">Nuevo cliente</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-row class="mb-3">
            <v-col cols="12" md="5">
              <v-text-field v-model="busqueda" label="Buscar por nombre o teléfono"
                prepend-inner-icon="mdi-magnify" variant="outlined" density="compact" clearable hide-details />
            </v-col>
          </v-row>

          <v-table striped="even" hover>
            <thead>
              <tr>
                <th>ID</th><th>Nombre</th><th>Identidad</th><th>Procedencia</th>
                <th>Teléfono</th><th>Correo</th><th>Vehículos</th><th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="c in filtrados()" :key="c.id">
                <td>{{ c.id }}</td>
                <td>{{ c.nombre }}</td>
                <td>{{ c.numero_identidad ?? '—' }}</td>
                <td>{{ c.procedencia ?? '—' }}</td>
                <td>{{ c.telefono ?? '—' }}</td>
                <td>{{ c.correo ?? '—' }}</td>
                <td><v-chip size="small" color="blue">{{ c.vehiculos_count }}</v-chip></td>
                <td>
                  <v-btn-group density="compact" variant="text">
                    <Link :href="route('clientes.edit', c.id)">
                      <v-btn icon="mdi-pencil" size="small" color="warning" />
                    </Link>
                    <v-btn icon="mdi-delete" size="small" color="error" @click="confirmarEliminar(c)" />
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
