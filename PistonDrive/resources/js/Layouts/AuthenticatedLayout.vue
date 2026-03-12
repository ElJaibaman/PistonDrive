<script setup>
import { Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const navItems = [
    { title: 'Dashboard',   icon: 'mdi-view-dashboard',    route: 'dashboard' },
    { title: 'Clientes',    icon: 'mdi-account-group',     route: 'clientes.index' },
    { title: 'Vehículos',   icon: 'mdi-car',               route: 'vehiculos.index' },
    { title: 'Mecánicos',   icon: 'mdi-account-hard-hat',  route: 'mecanicos.index' },
    { title: 'Órdenes',     icon: 'mdi-clipboard-list',    route: 'ordenes.index' },
    { title: 'Tickets',     icon: 'mdi-receipt',           route: 'tickets.index' },
    { title: 'Cotizaciones',icon: 'mdi-file-document-edit',route: 'secretaria.cotizaciones.index' },
];

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <v-app>
        <v-navigation-drawer expand-on-hover permanent rail>
            <v-list>
                <v-list-item
                    prepend-icon="mdi-car-wrench"
                    :title="page.props.auth.user.name"
                    :subtitle="page.props.auth.user.email"
                />
            </v-list>

            <v-divider />

            <v-list density="compact" nav>
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                >
                    <v-list-item
                        :prepend-icon="item.icon"
                        :title="item.title"
                        :active="route().current(item.route)"
                        color="primary"
                    />
                </Link>
            </v-list>

            <template #append>
                <v-list density="compact" nav>
                    <v-list-item
                        prepend-icon="mdi-logout"
                        title="Cerrar sesión"
                        @click="logout"
                    />
                </v-list>
            </template>
        </v-navigation-drawer>

        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>