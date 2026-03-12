<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ orden: Object, vehiculos: Array, mecanicos: Array, estados: Object });

const form = useForm({
  vehiculo_id:     props.orden?.vehiculo_id     ?? '',
  mecanico_id:     props.orden?.mecanico_id     ?? '',
  fecha_ingreso:   props.orden?.fecha_ingreso   ?? new Date().toISOString().slice(0, 10),
  fecha_prometida: props.orden?.fecha_prometida ?? '',
  estado:          props.orden?.estado          ?? 'pendiente',
  kilometraje:     props.orden?.kilometraje     ?? '',
  observaciones:   props.orden?.observaciones   ?? '',
});

// La secretaria solo puede poner pendiente o en_proceso al crear
const estadosSecretaria = ['pendiente', 'en_proceso'];

const vehiculosFormateados = computed(() =>
  props.vehiculos.map(v => ({
    id: v.id,
    label: `${v.cliente.nombre} — ${v.marca} ${v.modelo} (${v.placa ?? 'sin placa'})`,
  }))
);

function submit() {
  if (props.orden) form.put(route('ordenes.update', props.orden.id));
  else form.post(route('ordenes.store'));
}
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">
                {{ orden ? 'Editar orden #' + orden.id : 'Nueva orden de trabajo' }}
              </h2>
            </v-col>
            <v-col cols="auto">
              <Link :href="route('ordenes.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-form @submit.prevent="submit">
            <v-card class="mb-4">
              <v-card-title class="text-subtitle-1">Datos de la orden</v-card-title>
              <v-card-text>
                <v-row>
                  <v-col cols="12">
                    <v-select v-model="form.vehiculo_id" :items="vehiculosFormateados"
                      item-title="label" item-value="id" label="Vehículo / Cliente *"
                      variant="outlined" density="compact" prepend-inner-icon="mdi-car"
                      :error-messages="form.errors.vehiculo_id" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select v-model="form.mecanico_id" :items="mecanicos"
                      item-title="nombre" item-value="id" label="Mecánico asignado"
                      variant="outlined" density="compact" prepend-inner-icon="mdi-account-hard-hat"
                      clearable :error-messages="form.errors.mecanico_id" />
                  </v-col>
                  <v-col cols="12" md="6">
                    <v-select v-model="form.estado"
                      :items="(orden ? Object.entries(estados) : estadosSecretaria.map(k => [k, estados[k]])).map(([v, c]) => ({ value: v, title: c.label }))"
                      item-title="title" item-value="value"
                      label="Estado *" variant="outlined" density="compact"
                      :error-messages="form.errors.estado" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="form.fecha_ingreso" label="Fecha de ingreso *"
                      type="date" variant="outlined" density="compact"
                      :error-messages="form.errors.fecha_ingreso" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="form.fecha_prometida" label="Fecha prometida"
                      type="date" variant="outlined" density="compact"
                      :error-messages="form.errors.fecha_prometida" />
                  </v-col>
                  <v-col cols="12" md="4">
                    <v-text-field v-model="form.kilometraje" label="Kilometraje"
                      type="number" variant="outlined" density="compact"
                      prepend-inner-icon="mdi-gauge" :error-messages="form.errors.kilometraje" />
                  </v-col>
                  <v-col cols="12">
                    <v-textarea v-model="form.observaciones" label="Observaciones para el mecánico"
                      variant="outlined" density="compact" rows="3" />
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>

            <v-row>
              <v-col cols="auto">
                <v-btn type="submit" color="primary" size="large"
                  :loading="form.processing" prepend-icon="mdi-content-save">
                  {{ orden ? 'Actualizar orden' : 'Crear orden' }}
                </v-btn>
              </v-col>
              <v-col cols="auto">
                <Link :href="route('ordenes.index')">
                  <v-btn variant="outlined" size="large">Cancelar</v-btn>
                </Link>
              </v-col>
            </v-row>
          </v-form>
        </v-container>
        </AuthenticatedLayout>
</template>
