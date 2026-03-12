<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ vehiculo: Object, clientes: Array });

const form = useForm({
  cliente_id:    props.vehiculo?.cliente_id    ?? '',
  placa:         props.vehiculo?.placa         ?? '',
  marca:         props.vehiculo?.marca         ?? '',
  modelo:        props.vehiculo?.modelo        ?? '',
  anio:          props.vehiculo?.anio          ?? '',
  color:         props.vehiculo?.color         ?? '',
  observaciones: props.vehiculo?.observaciones ?? '',
});

const anioActual = new Date().getFullYear();
const anios = Array.from({ length: 40 }, (_, i) => anioActual - i);

function submit() {
  if (props.vehiculo) form.put(route('vehiculos.update', props.vehiculo.id));
  else form.post(route('vehiculos.store'));
}
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-4" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">{{ vehiculo ? 'Editar vehículo' : 'Nuevo vehículo' }}</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('vehiculos.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-card max-width="700">
            <v-card-text>
              <v-form @submit.prevent="submit">
                <v-row>
                  <v-col cols="12">
                    <v-select v-model="form.cliente_id" :items="clientes" item-title="nombre" item-value="id"
                      label="Cliente *" variant="outlined" density="compact" prepend-inner-icon="mdi-account"
                      :error-messages="form.errors.cliente_id" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.marca" label="Marca *" variant="outlined" density="compact"
                      :error-messages="form.errors.marca" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.modelo" label="Modelo *" variant="outlined" density="compact"
                      :error-messages="form.errors.modelo" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="form.placa" label="Placa" variant="outlined" density="compact"
                      :error-messages="form.errors.placa" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-select v-model="form.anio" :items="anios" label="Año" variant="outlined" density="compact"
                      :error-messages="form.errors.anio" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="form.color" label="Color" variant="outlined" density="compact"
                      :error-messages="form.errors.color" />
                  </v-col>
                  <v-col cols="12">
                    <v-textarea v-model="form.observaciones" label="Observaciones" variant="outlined"
                      density="compact" rows="3" :error-messages="form.errors.observaciones" />
                  </v-col>
                </v-row>
                <v-row class="mt-2">
                  <v-col cols="auto">
                    <v-btn type="submit" color="primary" :loading="form.processing" prepend-icon="mdi-content-save">
                      {{ vehiculo ? 'Actualizar' : 'Guardar' }}
                    </v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <Link :href="route('vehiculos.index')"><v-btn variant="outlined">Cancelar</v-btn></Link>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
          </v-card>
        </v-container>
        </AuthenticatedLayout>
</template>
