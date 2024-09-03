<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false);

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')

</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Collapsible Sidebar -->
        <div
            :class="{'w-64': sidebarOpen, 'w-14': !sidebarOpen}"
            class="transition-all duration-300 ease-in-out bg-[#2C3C4C] border-r border-gray-200"
        >
            <div class="flex items-center justify-between p-4 bg-[#04549C]">
                <!-- <ApplicationLogo class="block h-9 w-auto fill-current text-white" /> -->
                <img :src="logoUrl" alt="Logo" class="logo w-[50px] h-[50px]" v-if="sidebarOpen"
                >
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-400 hover:text-white focus:outline-none">

                    <svg
                        v-if="sidebarOpen"
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                    <svg
                        v-else
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                </button>
            </div>

            <nav class="flex flex-col" :class="{'flex': !sidebarOpen, 'items-center': !sidebarOpen, 'justify-center': !sidebarOpen}">
                <NavLink :href="route('home-page-management')" :active="route().current('home-page-management')">
                    <i class="fa fa-home text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Homepage Management</span>
                </NavLink>

                <NavLink :href="route('user-management')" :active="route().current('user-management')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">User Management</span>
                </NavLink>

                <NavLink :href="route('archive')" :active="route().current('archive')">
                    <i class="fa fa-file text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Archive</span>
                </NavLink>

                <NavLink :href="route('backup-and-restore')" :active="route().current('backup-and-restore')">
                    <i class="fa fa-gear text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Backup and Restore</span>
                </NavLink>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 w-full">
            <!-- Top Navbar -->
            <nav class="bg-[#04549C]">
                <div class="flex justify-between px-4 sm:px-6 lg:px-8" :class="{'h-[5.74vw]': sidebarOpen, 'h-[3.95vw]': !sidebarOpen}">
                    <div class="flex items-center">
                        <!-- <button
                            @click="sidebarOpen = !sidebarOpen"
                            class="mr-4 text-gray-500 focus:outline-none"
                        >
                            <svg v-if="sidebarOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                            <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                ></path>
                            </svg>
                        </button> -->

                        <!-- <Link :href="route('dashboard')" class="flex items-center">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                        </Link> -->
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6 ">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#04549C focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="ms-2 -me-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger (for responsive navbar) -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('home-page-management')" :active="route().current('home-page-management')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
