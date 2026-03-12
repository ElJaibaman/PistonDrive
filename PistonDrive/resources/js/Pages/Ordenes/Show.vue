<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ orden: Object, estados: Object });

const cotizacion = props.orden.cotizacion;
</script>

<template>
  <AuthenticatedLayout>
        <v-container>
          <v-row class="mb-4" align="center">
            <v-col><h2 class="text-h6 font-weight-bold">Orden #{{ orden.id }}</h2></v-col>
            <v-col cols="auto">
              <Link :href="route('ordenes.index')">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <!-- Estado actual -->
          <v-row class="mb-4">
            <v-col cols="auto">
              <v-chip :color="estados[orden.estado]?.color" size="large">
                {{ estados[orden.estado]?.label }}
              </v-chip>
            </v-col>
            <!-- Botón ir a revisar cotización si está pendiente -->
            <v-col cols="auto" v-if="cotizacion?.estado === 'pendiente'">
              <Link :href="route('secretaria.cotizaciones.show', cotizacion.id)">
                <v-btn color="orange" prepend-icon="mdi-file-document-edit">
                  Revisar cotización pendiente
                </v-btn>
              </Link>
            </v-col>
            <!-- Botón generar ticket si está terminado -->
            <v-col cols="auto" v-if="orden.estado === 'terminado' && !orden.ticket">
              <Link :href="route('tickets.show', { orden_id: orden.id })">
                <v-btn color="success" prepend-icon="mdi-receipt-text-check">
                  Generar ticket
                </v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-row>
            <v-col cols="12" md="6">
              <v-card class="mb-4">
                <v-card-title class="text-subtitle-1">Datos del cliente y vehículo</v-card-title>
                <v-card-text>
                  <p><strong>Cliente:</strong> {{ orden.vehiculo.cliente.nombre }}</p>
                  <p><strong>Teléfono:</strong> {{ orden.vehiculo.cliente.telefono ?? '—' }}</p>
                  <p><strong>Vehículo:</strong> {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}</p>
                  <p><strong>Placa:</strong> {{ orden.vehiculo.placa ?? '—' }}</p>
                  <p><strong>Color:</strong> {{ orden.vehiculo.color ?? '—' }}</p>
                  <p><strong>Año:</strong> {{ orden.vehiculo.anio ?? '—' }}</p>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col cols="12" md="6">
              <v-card class="mb-4">
                <v-card-title class="text-subtitle-1">Datos de la orden</v-card-title>
                <v-card-text>
                  <p><strong>Mecánico:</strong> {{ orden.mecanico?.nombre ?? 'Sin asignar' }}</p>
                  <p><strong>Ingreso:</strong> {{ orden.fecha_ingreso }}</p>
                  <p><strong>Prometido:</strong> {{ orden.fecha_prometida ?? '—' }}</p>
                  <p><strong>Entrega:</strong> {{ orden.fecha_entrega ?? '—' }}</p>
                  <p><strong>Kilometraje:</strong> {{ orden.kilometraje ? orden.kilometraje + ' km' : '—' }}</p>
                  <p v-if="orden.observaciones"><strong>Observaciones:</strong> {{ orden.observaciones }}</p>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>

          <!-- Cotización resumen -->
          <v-card v-if="cotizacion" class="mb-4">
            <v-card-title class="text-subtitle-1">
              <v-icon class="mr-1">mdi-file-document</v-icon>
              Cotización del mecánico
              <v-chip :color="cotizacion.estado === 'aprobada' ? 'green' : cotizacion.estado === 'rechazada' ? 'red' : 'orange'"
                size="x-small" class="ml-2">
                {{ cotizacion.estado }}
              </v-chip>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col cols="12" md="4">
                  <div class="text-body-2">Piezas: <strong>L. {{ parseFloat(cotizacion.total_piezas).toFixed(2) }}</strong></div>
                </v-col>
                <v-col cols="12" md="4">
                  <div class="text-body-2">Mano de obra: <strong>L. {{ parseFloat(cotizacion.total_mano_obra).toFixed(2) }}</strong></div>
                </v-col>
                <v-col cols="12" md="4">
                  <div class="text-body-2 text-primary font-weight-bold">
                    Total: L. {{ parseFloat(cotizacion.total_general).toFixed(2) }}
                  </div>
                </v-col>
              </v-row>
              <div v-if="cotizacion.nota_mecanico" class="mt-2 text-body-2">
                <strong>Nota del mecánico:</strong> {{ cotizacion.nota_mecanico }}
              </div>
              <div v-if="cotizacion.nota_secretaria" class="mt-1 text-body-2">
                <strong>Nota de secretaria:</strong> {{ cotizacion.nota_secretaria }}
              </div>
              <div class="mt-3">
                <Link :href="route('secretaria.cotizaciones.show', cotizacion.id)">
                  <v-btn size="small" variant="outlined" prepend-icon="mdi-eye">Ver detalle completo</v-btn>
                </Link>
              </div>
            </v-card-text>
          </v-card>

        </v-container>
        </AuthenticatedLayout>
</template>
