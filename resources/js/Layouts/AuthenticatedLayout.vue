<script setup>
import { ref, provide } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dashboard from '../Pages/Alumni/Dashboard.vue';
import { Inertia } from '@inertiajs/inertia';

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(true);

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png');

const notificationModalVisible = ref(false);

const openNotificationModal = () => {
    notificationModalVisible.value = true;
};

const closeNotificationModal = () => {
    notificationModalVisible.value = false;
};

const openNotification = (n) => {
    if(n.type =='album') {
        Inertia.get(route('album.view', n.redirect_id))
    } else {
        Inertia.get(route('achievement.view', n.redirect_id))
    }
}

const searchQuery = ref("");

let timeoutId;

const auth = usePage().props.auth.user;
const search = usePage().props.search


if(search != '') {
    searchQuery.value = search
}

const updateSearchQuery = (event) => {
    clearTimeout(timeoutId);

    searchQuery.value = event.target.value;

    // if(searchQuery.value == '') {
    //     searchQuery.value = 'search'
    // }

    timeoutId = setTimeout(() => {

        if(auth.user_type == "school_staff") {
            Inertia.get(route('staff-dashboard'), {search : searchQuery.value})
        }

        if(auth.user_type == "school_alumni") {
            Inertia.get(route('alumni-dashboard'), {search : searchQuery.value})
        }
    }, 3000);
};





////
</script>

<template>
    <div class="flex h-screen bg-gray-100">
        <!-- Collapsible Sidebar -->
        <div
            :class="{'w-64': sidebarOpen, 'w-14': !sidebarOpen}"
            class="fixed top-0 left-0 h-full transition-all duration-300 ease-in-out bg-[#2C3C4C] border-r border-gray-200 z-20"
        >
            <div class="flex items-center justify-between p-4 bg-[#04549C]">
                <img :src="!$page.props.logo ? logoUrl : $page.props.logo" alt="Logo" class="logo w-[50px] h-[50px]" v-if="sidebarOpen">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-white focus:outline-none">
                    <svg v-if="sidebarOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <svg v-else class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar navigation links -->
            <nav class="flex flex-col" v-if="$page.props.auth.user.user_type == 'system_admin'">
                <!-- System Admin links -->
                <NavLink :href="route('user-management')" :active="route().current('user-management')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">User Management</span>
                </NavLink>

                <NavLink :href="route('archived-users')" :active="route().current('archived-users')">
                    <i class="fa fa-file text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Archive</span>
                </NavLink>
                <NavLink :href="route('backup-and-restore')" :active="route().current('backup-and-restore')">
                    <i class="fa fa-gear text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Backup and Restore</span>
                </NavLink>
            </nav>

            <nav class="flex flex-col" v-if="$page.props.auth.user.user_type == 'school_staff'">
                <NavLink :href="route('home-page-management')" :active="route().current('home-page-management')">
                    <i class="fa fa-home text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Homepage Management</span>
                </NavLink>

                <NavLink :href="route('user-management')" :active="route().current('user-management')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">User Management</span>
                </NavLink>

                <NavLink :href="route('course-management')" :active="route().current('course-management')">
                    <i class="fa-solid fa-folder text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Course Management</span>
                </NavLink>

                <NavLink :href="route('staff-dashboard')" :active="route().current('staff-dashboard')">
                    <i class="fa-solid fa-chart-line text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Dashboard</span>
                </NavLink>

                <NavLink :href="route('staff-alumni-records')" :active="route().current('staff-alumni-records')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Alumni Login Status</span>
                </NavLink>

                <NavLink :href="route('staff-achievements-and-recogniations')" :active="route().current('staff-achievements-and-recogniations')">
                    <i class="fa-solid fa-medal text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Achievements & Recognition</span>
                </NavLink>

                <NavLink :href="route('staff-school-album')" :active="route().current('staff-school-album')">
                    <i class="fa-solid fa-image text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">School Album</span>
                </NavLink>

                <NavLink :href="route('staff-class-batches')" :active="route().current('staff-class-batches')">
                    <i class="fa-solid fa-graduation-cap text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Class Batches</span>
                </NavLink>

                <NavLink :href="route('staff-success-stories')" :active="route().current('staff-success-stories')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Alumni Success Stories</span>
                </NavLink>
            </nav>

            <nav class="flex flex-col" v-if="$page.props.auth.user.user_type == 'school_alumni'">
                <NavLink :href="route('alumni-dashboard')" :active="route().current('alumni-dashboard')">
                    <i class="fa-solid fa-chart-line text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Dashboard</span>
                </NavLink>

                <NavLink :href="route('alumni-mtfs-index')" :active="route().current('alumni-mtfs-index')">
                    <i class="fa-solid fa-message text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Message To Future Self</span>
                </NavLink>

                <NavLink :href="route('alumni-achievements-and-recogniations')" :active="route().current('alumni-achievements-and-recogniations')">
                    <i class="fa-solid fa-medal text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Achievements & Recognition</span>
                </NavLink>

                <NavLink :href="route('alumni-school-album')" :active="route().current('alumni-school-album')">
                    <i class="fa-solid fa-image text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">School Album</span>
                </NavLink>

                <NavLink :href="route('alumni-class-batches')" :active="route().current('alumni-class-batches')">
                    <i class="fa-solid fa-graduation-cap text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Class Batches</span>
                </NavLink>

                <NavLink :href="route('alumni-success-stories')" :active="route().current('alumni-success-stories')">
                    <i class="fa fa-users text-2xl" :class="{'pl-4': !sidebarOpen, 'pl-3': sidebarOpen}"></i>
                    <span v-if="sidebarOpen" class="text-md pl-5">Alumni Success Stories</span>
                </NavLink>
            </nav>
        </div>

        <!-- Main Content -->
        <div :class="{'ml-64': sidebarOpen, 'ml-14': !sidebarOpen}" class="flex flex-col flex-1 w-full min-h-screen h-full transition-all duration-300 ease-in-out">
            <!-- Top Navbar -->
            <nav :class="{'left-64': sidebarOpen, 'left-14': !sidebarOpen}" class="fixed top-0 right-0 bg-[#04549C] h-16 z-10 flex items-center justify-between px-4 sm:px-6 lg:px-8 transition-all duration-300 ease-in-out">
                <!-- Left Section (Add other items here if needed) -->
                <div class="flex items-center space-x-4">
                    <!-- Other left-aligned items (if any) -->
                </div>


                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search..." class="rounded-2xl w-[70%] mr-5"
                        @keyup="updateSearchQuery($event)"
                        v-if="(route().current('staff-dashboard') || route().current('alumni-dashboard')) &&
                            $page.props.auth.user.user_type != 'system_admin'
                        "
                        v-model="searchQuery"
                    />

                    <!-- User Dropdown -->
                    <span class="float-right text-xl text-white cursor-pointer mr-5 mt-1" @click="openNotificationModal"
                        v-if="$page.props.auth.user.user_type != 'school_alumni'"
                    >
                        <i class="fa-solid fa-earth-americas"> </i>
                    </span>
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md">

                                <button
                                    type="button"
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#04549C] focus:outline-none transition ease-in-out duration-150"
                                >
                                    {{ $page.props.auth.user.fullname }}
                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                        </template>
                    </Dropdown>
                </div>

                <div v-if="notificationModalVisible" class="notification-modal bg-white shadow-lg rounded-lg">
                    <div class="bg-white rounded-lg w-full">
                        <div class="w-full mb-5 p-5">
                            <span class="float-right cursor-pointer" @click="closeNotificationModal">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        <div class="w-full p-3 text-center text-xl mb-5" v-if="$page.props.notifications.length == 0">
                            You have no notification(s).
                        </div>

                        <div class="w-full flex flex-col p-2" v-else>
                            <div class="w-full border-b border-black mb-3" v-for="n in $page.props.notifications" :key="n.id"
                                @click="n.redirect_id ? openNotification(n) : ''"
                                :class="{'cursor-pointer': n.redirect_id}"
                            >
                                {{ n.message }}
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Hamburger for responsive view -->
                <div class="sm:hidden -me-2 flex items-center">
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
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </nav>

            <!-- Page Content -->
            <!-- <Dashboard :searchQuery="searchQuery" /> -->
            <main class="mt-16 p-4 sm:p-6 lg:p-8">

                <slot/>

            </main>
        </div>
    </div>
</template>

<style scoped>
nav.left-64 {
    left: 16rem;
}

nav.left-14 {
    left: 3.5rem;
}

.notification-modal {
    position: absolute;
    right: 200px;
    top: 50px;
    width: 350px;
}
</style>
