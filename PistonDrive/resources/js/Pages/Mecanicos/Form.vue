<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    mecanico: Object,
});

const form = useForm({
    nombre:         props.mecanico?.nombre        ?? '',
    telefono:       props.mecanico?.telefono      ?? '',
    activo:         props.mecanico?.activo        ?? true,
    crear_usuario:  false,
    email:          '',
    password:       '',
});

const isEdit = !!props.mecanico;

function submit() {
    if (isEdit) {
        form.put(route('mecanicos.update', props.mecanico.id));
    } else {
        form.post(route('mecanicos.store'));
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <v-container>
            <v-row class="mb-4" align="center">
                <v-col>
                    <h2 class="text-h6 font-weight-bold">
                        {{ isEdit ? 'Editar mecánico' : 'Nuevo mecánico' }}
                    </h2>
                </v-col>
                <v-col cols="auto">
                    <Link :href="route('mecanicos.index')">
                        <v-btn variant="text" prepend-icon="mdi-arrow-left">Volver</v-btn>
                    </Link>
                </v-col>
            </v-row>

            <v-card max-width="600">
                <v-card-text>
                    <v-text-field
                        v-model="form.nombre"
                        label="Nombre completo"
                        :error-messages="form.errors.nombre"
                        required
                        class="mb-3"
                    />
                    <v-text-field
                        v-model="form.telefono"
                        label="Teléfono"
                        :error-messages="form.errors.telefono"
                        class="mb-3"
                    />
                    <v-switch
                        v-model="form.activo"
                        label="Mecánico activo"
                        color="primary"
                        class="mb-3"
                    />

                    <!-- Crear usuario solo en modo creación -->
                    <template v-if="!isEdit">
                        <v-divider class="mb-4" />
                        <v-switch
                            v-model="form.crear_usuario"
                            label="Crear usuario de acceso para este mecánico"
                            color="primary"
                            class="mb-3"
                        />
                        <template v-if="form.crear_usuario">
                            <v-text-field
                                v-model="form.email"
                                label="Correo electrónico"
                                type="email"
                                :error-messages="form.errors.email"
                                class="mb-3"
                            />
                            <v-text-field
                                v-model="form.password"
                                label="Contraseña"
                                type="password"
                                :error-messages="form.errors.password"
                                class="mb-3"
                            />
                        </template>
                    </template>
                </v-card-text>

                <v-card-actions class="px-4 pb-4">
                    <v-btn
                        color="primary"
                        :loading="form.processing"
                        @click="submit"
                    >
                        {{ isEdit ? 'Actualizar' : 'Guardar' }}
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-container>
    </AuthenticatedLayout>
</template>