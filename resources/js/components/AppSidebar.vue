<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { LayoutGrid, Users } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Voting',
        href: '/voting',
        icon: Users,
    },
    {
        title: 'Kandidat',
        href: '/candidates',
        icon: Users,
    },
    {
        title: 'Users',
        href: '/users',
        icon: Users,
    },
];

const page = usePage();
const role = computed(() => (page.props?.auth?.user?.role ?? 'user'));
const filteredMainNavItems = computed(() => {
    return role.value === 'admin'
        ? mainNavItems
        : mainNavItems.filter((item) => ['Dashboard', 'Kandidat'].includes(item.title));
});

</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

    <SidebarContent>
            <NavMain :items="filteredMainNavItems" />
    </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
