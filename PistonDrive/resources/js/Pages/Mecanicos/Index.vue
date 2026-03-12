<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    mecanicos: Object,
});

function toggleActivo(mecanico) {
    router.patch(route('mecanicos.toggle-activo', mecanico.id));
}

function destroy(mecanico) {
    if (confirm(`¿Eliminar a ${mecanico.nombre}?`)) {
        router.delete(route('mecanicos.destroy', mecanico.id));
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <v-container>
            <v-row class="mb-4" align="center">
                <v-col>
                    <h2 class="text-h6 font-weight-bold">Mecánicos</h2>
                </v-col>
                <v-col cols="auto">
                    <Link :href="route('mecanicos.create')">
                        <v-btn color="primary" prepend-icon="mdi-plus">Nuevo mecánico</v-btn>
                    </Link>
                </v-col>
            </v-row>

            <v-card>
                <v-table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Teléfono</th>
                            <th>Órdenes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="mecanico in mecanicos.data" :key="mecanico.id">
                            <td>{{ mecanico.id }}</td>
                            <td>{{ mecanico.nombre }}</td>
                            <td>{{ mecanico.telefono ?? '—' }}</td>
                            <td>{{ mecanico.ordenes_count }}</td>
                            <td>
                                <v-chip
                                    :color="mecanico.activo ? 'green' : 'grey'"
                                    size="small"
                                    @click="toggleActivo(mecanico)"
                                    style="cursor:pointer"
                                >
                                    {{ mecanico.activo ? 'Activo' : 'Inactivo' }}
                                </v-chip>
                            </td>
                            <td>
                                <Link :href="route('mecanicos.edit', mecanico.id)">
                                    <v-btn icon="mdi-pencil" size="x-small" variant="text" color="primary" />
                                </Link>
                                <v-btn
                                    icon="mdi-delete"
                                    size="x-small"
                                    variant="text"
                                    color="error"
                                    @click="destroy(mecanico)"
                                />
                            </td>
                        </tr>
                        <tr v-if="mecanicos.data.length === 0">
                            <td colspan="6" class="text-center text-grey py-4">Sin mecánicos registrados</td>
                        </tr>
                    </tbody>
                </v-table>
            </v-card>
        </v-container>
    </AuthenticatedLayout>
</template>