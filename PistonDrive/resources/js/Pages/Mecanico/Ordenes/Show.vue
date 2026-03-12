<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  orden:              Object,
  estadoConfig:       Object,
  estadosPermitidos:  Array,
  todosEstados:       Object,
});

const formEstado = useForm({ estado: props.orden.estado });

function cambiarEstado() {
  formEstado.patch(route('mecanico.ordenes.estado', props.orden.id));
}

const estadoOpciones = computed(() =>
  props.estadosPermitidos.map(e => ({
    value: e,
    title: {
      en_proceso:           'En proceso',
      esperando_aprobacion: 'Enviar a aprobación',
      terminado:            'Marcar como terminado',
    }[e] ?? e,
  }))
);

const cotizacion = computed(() => props.orden.cotizacion);

const puedeCotizar = computed(() =>
  props.orden.estado === 'en_proceso' ||
  (props.orden.estado === 'rechazado' && cotizacion.value?.estado === 'rechazada')
);
</script>

<template>
  <AuthenticatedLayout>
        <v-container>

          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">Orden #{{ orden.id }}</h2>
            </v-col>
            <v-col cols="auto">
              <Link :href="route('mecanico.ordenes.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <!-- Alertas de cotización -->
          <v-alert
            v-if="cotizacion?.estado === 'rechazada'"
            type="error" class="mb-4" prepend-icon="mdi-close-circle"
          >
            Tu cotización fue <strong>rechazada</strong>.
            <span v-if="cotizacion.nota_secretaria">
              Motivo: <strong>{{ cotizacion.nota_secretaria }}</strong>
            </span>
            Puedes enviar una nueva cotización corregida.
          </v-alert>

          <v-alert
            v-else-if="cotizacion?.estado === 'aprobada'"
            type="success" class="mb-4" prepend-icon="mdi-check-circle"
          >
            Tu cotización fue <strong>aprobada</strong>. Puedes continuar con el trabajo.
            <span v-if="cotizacion.nota_secretaria">
              Nota: <strong>{{ cotizacion.nota_secretaria }}</strong>
            </span>
          </v-alert>

          <v-alert
            v-else-if="cotizacion?.estado === 'pendiente'"
            type="warning" class="mb-4" prepend-icon="mdi-timer-sand"
          >
            Tu cotización está <strong>pendiente de revisión</strong> por la secretaria.
          </v-alert>

          <v-row>
            <!-- Datos del vehículo -->
            <v-col cols="12" md="6">
              <v-card class="mb-4">
                <v-card-title class="text-subtitle-1">
                  <v-icon class="mr-1">mdi-car</v-icon> Vehículo
                </v-card-title>
                <v-card-text>
                  <p><strong>Cliente:</strong> {{ orden.vehiculo.cliente.nombre }}</p>
                  <p><strong>Teléfono:</strong> {{ orden.vehiculo.cliente.telefono ?? '—' }}</p>
                  <p><strong>Vehículo:</strong> {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }} {{ orden.vehiculo.anio }}</p>
                  <p><strong>Placa:</strong> {{ orden.vehiculo.placa ?? '—' }}</p>
                  <p><strong>Color:</strong> {{ orden.vehiculo.color ?? '—' }}</p>
                  <p v-if="orden.kilometraje"><strong>Kilometraje:</strong> {{ orden.kilometraje }} km</p>
                  <p v-if="orden.observaciones" class="mt-2">
                    <strong>Observaciones de secretaria:</strong> {{ orden.observaciones }}
                  </p>
                </v-card-text>
              </v-card>
            </v-col>

            <!-- Cambiar estado -->
            <v-col cols="12" md="6">
              <v-card class="mb-4">
                <v-card-title class="text-subtitle-1">
                  <v-icon class="mr-1">mdi-swap-horizontal</v-icon> Estado de la orden
                </v-card-title>
                <v-card-text>
                  <div class="mb-3">
                    Estado actual:
                    <v-chip
                      :color="todosEstados[orden.estado]?.color"
                      size="small" class="ml-2"
                    >
                      {{ todosEstados[orden.estado]?.label }}
                    </v-chip>
                  </div>

                  <v-form @submit.prevent="cambiarEstado">
                    <v-select
                      v-model="formEstado.estado"
                      :items="estadoOpciones"
                      item-title="title"
                      item-value="value"
                      label="Cambiar estado a"
                      variant="outlined"
                      density="compact"
                      class="mb-3"
                    />
                    <v-btn
                      type="submit"
                      color="primary"
                      :loading="formEstado.processing"
                      prepend-icon="mdi-content-save"
                    >
                      Actualizar estado
                    </v-btn>
                  </v-form>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <!-- Detalle de cotización enviada -->
          <v-card v-if="cotizacion" class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-1">mdi-file-document</v-icon>
              Mi cotización enviada
              <v-chip
                :color="cotizacion.estado === 'aprobada' ? 'green' : cotizacion.estado === 'rechazada' ? 'red' : 'orange'"
                size="x-small" class="ml-2"
              >
                {{ cotizacion.estado }}
              </v-chip>
            </v-card-title>
            <v-card-text>
              <p v-if="cotizacion.nota_mecanico" class="mb-3 text-body-2">
                <strong>Mi nota:</strong> {{ cotizacion.nota_mecanico }}
              </p>

              <v-table density="compact">
                <thead>
                  <tr>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Precio (L.)</th>
                    <th class="text-right">Subtotal (L.)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in cotizacion.items" :key="item.id">
                    <td>
                      <v-chip
                        :color="item.tipo === 'pieza' ? 'blue' : 'purple'"
                        size="x-small"
                      >
                        {{ item.tipo === 'pieza' ? 'Pieza' : 'Mano de obra' }}
                      </v-chip>
                    </td>
                    <td>{{ item.descripcion }}</td>
                    <td class="text-center">{{ item.cantidad }}</td>
                    <td class="text-right">{{ parseFloat(item.precio_unitario).toFixed(2) }}</td>
                    <td class="text-right">{{ parseFloat(item.subtotal).toFixed(2) }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="4" class="text-right font-weight-bold">Total piezas</td>
                    <td class="text-right">L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="text-right font-weight-bold">Total mano de obra</td>
                    <td class="text-right">L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</td>
                  </tr>
                  <tr>
                    <td colspan="4" class="text-right font-weight-bold text-primary">TOTAL GENERAL</td>
                    <td class="text-right font-weight-bold text-primary">
                      L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}
                    </td>
                  </tr>
                </tfoot>
              </v-table>
            </v-card-text>
          </v-card>

          <!-- Botón enviar cotización -->
          <div v-if="puedeCotizar" class="mt-2">
            <Link :href="route('mecanico.cotizacion.create', orden.id)">
              <v-btn color="warning" size="large" prepend-icon="mdi-file-document-plus">
                {{ cotizacion?.estado === 'rechazada' ? 'Enviar nueva cotización' : 'Enviar cotización' }}
              </v-btn>
            </Link>
          </div>

        </v-container>
        </AuthenticatedLayout>
</template>
