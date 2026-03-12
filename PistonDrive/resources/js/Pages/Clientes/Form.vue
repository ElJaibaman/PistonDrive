<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({ cliente: Object });

const form = useForm({
  nombre:        props.cliente?.nombre        ?? '',
  numero_identidad: props.cliente?.numero_identidad ?? '',
  procedencia:   props.cliente?.procedencia   ?? '',
  telefono:      props.cliente?.telefono      ?? '',
  correo:        props.cliente?.correo        ?? '',
});

function submit() {
  if (props.cliente) form.put(route('clientes.update', props.cliente.id));
  else form.post(route('clientes.store'));
}
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-4" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">{{ cliente ? 'Editar cliente' : 'Nuevo cliente' }}</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('clientes.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-card max-width="700">
            <v-card-text>
              <v-form @submit.prevent="submit">
                <v-row>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.nombre" label="Nombre completo *" variant="outlined"
                      density="compact" :error-messages="form.errors.nombre" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.numero_identidad" label="Número de identidad" variant="outlined"
                      density="compact" :error-messages="form.errors.numero_identidad" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.telefono" label="Teléfono" variant="outlined"
                      density="compact" prepend-inner-icon="mdi-phone" :error-messages="form.errors.telefono" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-text-field v-model="form.correo" label="Correo electrónico" type="email"
                      variant="outlined" density="compact" prepend-inner-icon="mdi-email"
                      :error-messages="form.errors.correo" />
                  </v-col>
                  <v-col cols="12">
                    <v-text-field v-model="form.procedencia" label="Procedencia / Ciudad" variant="outlined"
                      density="compact" prepend-inner-icon="mdi-map-marker"
                      :error-messages="form.errors.procedencia" />
                  </v-col>
                </v-row>
                <v-row class="mt-2">
                  <v-col cols="auto">
                    <v-btn type="submit" color="primary" :loading="form.processing" prepend-icon="mdi-content-save">
                      {{ cliente ? 'Actualizar' : 'Guardar' }}
                    </v-btn>
                  </v-col>
                  <v-col cols="auto">
                    <Link :href="route('clientes.index')"><v-btn variant="outlined">Cancelar</v-btn></Link>
                  </v-col>
                </v-row>
              </v-form>
            </v-card-text>
          </v-card>
        </v-container>
        </AuthenticatedLayout>
</template>
