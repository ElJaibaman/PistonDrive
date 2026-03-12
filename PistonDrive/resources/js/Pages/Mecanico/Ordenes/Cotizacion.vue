<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
  orden:             Object,
  serviciosManoObra: Array,
});

const form = useForm({
  nota_mecanico: '',
  piezas: [{ descripcion: '', cantidad: 1, precio_unitario: '' }],
  mano_obra: [{ descripcion: '', cantidad: 1, precio_unitario: '' }],
});

function agregarPieza() {
  form.piezas.push({ descripcion: '', cantidad: 1, precio_unitario: '' });
}
function quitarPieza(i) {
  if (form.piezas.length > 1) form.piezas.splice(i, 1);
}
function agregarManoObra() {
  form.mano_obra.push({ descripcion: '', cantidad: 1, precio_unitario: '' });
}
function quitarManoObra(i) {
  if (form.mano_obra.length > 1) form.mano_obra.splice(i, 1);
}

const totalPiezas = computed(() =>
  form.piezas.reduce((s, p) =>
    s + (parseFloat(p.precio_unitario) || 0) * (parseInt(p.cantidad) || 0), 0)
);
const totalManoObra = computed(() =>
  form.mano_obra.reduce((s, m) =>
    s + (parseFloat(m.precio_unitario) || 0) * (parseInt(m.cantidad) || 0), 0)
);
const totalGeneral = computed(() => totalPiezas.value + totalManoObra.value);

function submit() {
  form.post(route('mecanico.cotizacion.store', props.orden.id));
}
</script>

<template>
  <AuthenticatedLayout>
        <v-container>

          <v-row class="mb-4" align="center">
            <v-col>
              <h2 class="text-h6 font-weight-bold">
                Nueva cotización — Orden #{{ orden.id }}
              </h2>
              <p class="text-body-2 text-grey mt-1">
                {{ orden.vehiculo.cliente.nombre }} |
                {{ orden.vehiculo.marca }} {{ orden.vehiculo.modelo }}
                ({{ orden.vehiculo.placa ?? 'sin placa' }})
              </p>
            </v-col>
            <v-col cols="auto">
              <Link :href="route('mecanico.ordenes.show', orden.id)">
                <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
              </Link>
            </v-col>
          </v-row>

          <v-form @submit.prevent="submit">

            <!-- Nota general -->
            <v-card class="mb-4">
              <v-card-title class="text-subtitle-1">Nota de diagnóstico</v-card-title>
              <v-card-text>
                <v-textarea
                  v-model="form.nota_mecanico"
                  label="Describe el problema encontrado o nota para la secretaria"
                  variant="outlined"
                  density="compact"
                  rows="3"
                  :error-messages="form.errors.nota_mecanico"
                />
              </v-card-text>
            </v-card>

            <!-- PIEZAS -->
            <v-card class="mb-4">
              <v-card-title class="text-subtitle-1">
                <v-icon class="mr-1" color="blue">mdi-cog</v-icon>
                Piezas / repuestos
              </v-card-title>
              <v-card-text>
                <v-row
                  v-for="(pieza, i) in form.piezas"
                  :key="'p' + i"
                  align="center"
                  class="mb-1"
                >
                  <v-col cols="12" md="5">
                    <v-text-field
                      v-model="pieza.descripcion"
                      :label="'Nombre de la pieza ' + (i + 1)"
                      variant="outlined"
                      density="compact"
                      hide-details
                      prepend-inner-icon="mdi-cog-outline"
                    />
                  </v-col>
                  <v-col cols="4" md="2">
                    <v-text-field
                      v-model="pieza.cantidad"
                      label="Cant."
                      type="number"
                      min="1"
                      variant="outlined"
                      density="compact"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="6" md="3">
                    <v-text-field
                      v-model="pieza.precio_unitario"
                      label="Precio unit. (L.)"
                      type="number"
                      step="0.01"
                      min="0"
                      variant="outlined"
                      density="compact"
                      prepend-inner-icon="mdi-currency-usd"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="2" class="text-right">
                    <span class="text-caption text-grey d-block">
                      L. {{ ((parseFloat(pieza.precio_unitario) || 0) * (parseInt(pieza.cantidad) || 0)).toFixed(2) }}
                    </span>
                    <v-btn
                      icon="mdi-minus-circle"
                      color="error"
                      variant="text"
                      size="small"
                      :disabled="form.piezas.length === 1"
                      @click="quitarPieza(i)"
                    />
                  </v-col>
                </v-row>

                <v-btn
                  prepend-icon="mdi-plus"
                  variant="tonal"
                  color="blue"
                  size="small"
                  class="mt-2"
                  @click="agregarPieza"
                >
                  Agregar pieza
                </v-btn>

                <div class="text-right mt-3 text-body-2">
                  Subtotal piezas:
                  <strong class="text-blue">L. {{ totalPiezas.toFixed(2) }}</strong>
                </div>
              </v-card-text>
            </v-card>

            <!-- MANO DE OBRA -->
            <v-card class="mb-4">
              <v-card-title class="text-subtitle-1">
                <v-icon class="mr-1" color="purple">mdi-wrench</v-icon>
                Mano de obra
              </v-card-title>
              <v-card-text>
                <v-row
                  v-for="(mo, i) in form.mano_obra"
                  :key="'mo' + i"
                  align="center"
                  class="mb-1"
                >
                  <v-col cols="12" md="5">
                    <!--
                      v-combobox: selecciona de la lista predefinida
                      O escribe un servicio personalizado que no esté en ella
                    -->
                    <v-combobox
                      v-model="mo.descripcion"
                      :items="serviciosManoObra"
                      :label="'Servicio ' + (i + 1)"
                      variant="outlined"
                      density="compact"
                      hide-details
                      prepend-inner-icon="mdi-wrench-outline"
                      clearable
                    />
                  </v-col>
                  <v-col cols="4" md="2">
                    <v-text-field
                      v-model="mo.cantidad"
                      label="Cant."
                      type="number"
                      min="1"
                      variant="outlined"
                      density="compact"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="6" md="3">
                    <!--
                      Monto libre: el mecánico escribe cualquier valor,
                      aunque el servicio no tenga precio predefinido
                    -->
                    <v-text-field
                      v-model="mo.precio_unitario"
                      label="Monto (L.)"
                      type="number"
                      step="0.01"
                      min="0"
                      variant="outlined"
                      density="compact"
                      prepend-inner-icon="mdi-currency-usd"
                      hide-details
                    />
                  </v-col>
                  <v-col cols="2" class="text-right">
                    <span class="text-caption text-grey d-block">
                      L. {{ ((parseFloat(mo.precio_unitario) || 0) * (parseInt(mo.cantidad) || 0)).toFixed(2) }}
                    </span>
                    <v-btn
                      icon="mdi-minus-circle"
                      color="error"
                      variant="text"
                      size="small"
                      :disabled="form.mano_obra.length === 1"
                      @click="quitarManoObra(i)"
                    />
                  </v-col>
                </v-row>

                <v-btn
                  prepend-icon="mdi-plus"
                  variant="tonal"
                  color="purple"
                  size="small"
                  class="mt-2"
                  @click="agregarManoObra"
                >
                  Agregar servicio
                </v-btn>

                <div class="text-right mt-3 text-body-2">
                  Subtotal mano de obra:
                  <strong class="text-purple">L. {{ totalManoObra.toFixed(2) }}</strong>
                </div>
              </v-card-text>
            </v-card>

            <!-- TOTAL GENERAL -->
            <v-card class="mb-6" color="blue-grey-lighten-5" variant="flat">
              <v-card-text>
                <v-row>
                  <v-col class="text-body-2">
                    <div>Piezas: <strong>L. {{ totalPiezas.toFixed(2) }}</strong></div>
                    <div>Mano de obra: <strong>L. {{ totalManoObra.toFixed(2) }}</strong></div>
                  </v-col>
                  <v-col class="text-right">
                    <div class="text-h6 font-weight-bold text-primary">
                      TOTAL: L. {{ totalGeneral.toFixed(2) }}
                    </div>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>

            <!-- Botones -->
            <v-row>
              <v-col cols="auto">
                <v-btn
                  type="submit"
                  color="warning"
                  size="large"
                  :loading="form.processing"
                  prepend-icon="mdi-send"
                >
                  Enviar cotización
                </v-btn>
              </v-col>
              <v-col cols="auto">
                <Link :href="route('mecanico.ordenes.show', orden.id)">
                  <v-btn variant="outlined" size="large">Cancelar</v-btn>
                </Link>
              </v-col>
            </v-row>

          </v-form>
        </v-container>
        </AuthenticatedLayout>
</template>
