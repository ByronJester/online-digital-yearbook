<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const navItems = [
    { name: 'Home' },
    { name: 'Alumni Success Stories' },
    { name: 'History' },
    { name: 'Hymn' },
    { name: 'Mission and Vision' },
    { name: 'Programs' },
    { name: 'FAQs' },
];

// Track the active nav item
const activeNavItem = ref('Home');

// Toggle for mobile menu
const isOpen = ref(false);

// Function to change the UI based on the selected nav item
const setActiveNavItem = (item) => {
    activeNavItem.value = item;
};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')

console.log(logoUrl)
</script>

<template>
    <Head title="Welcome" />

    <nav class="bg-[#0772B3] text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold">
                <img :src="!$page?.props?.logo ? logoUrl : $page.props.logo.file" alt="Logo" class="logo w-[100px] h-[80px]"
                >
            </div>

            <!-- Mobile menu button -->
            <button
                class="block lg:hidden text-white"
                @click="isOpen = !isOpen"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden lg:flex space-x-6 items-center">
                <button
                    v-for="item in navItems"
                    :key="item.name"
                    @click="setActiveNavItem(item.name)"
                    class="hover:underline"
                    :class="activeNavItem === item.name ? 'underline font-bold' : ''"
                >
                    {{ item.name }}
                </button>

                <!-- Login Button -->
                <Link
                    href="/login"
                    class="ml-auto bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-200"
                >
                    Login
                </Link>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="isOpen" class="lg:hidden mt-4 bg-[#0772B3] space-y-2 p-4">
            <button
                v-for="item in navItems"
                :key="item.name"
                @click="setActiveNavItem(item.name)"
                class="block w-full text-left text-white hover:underline"
                :class="activeNavItem === item.name ? 'underline font-bold' : ''"
            >
                {{ item.name }}
            </button>

            <!-- Mobile Login Button -->
            <Link
                href="/login"
                class="block bg-white text-blue-600 text-center px-4 py-2 rounded hover:bg-gray-200"
            >
                Login
            </Link>
        </div>
    </nav>

    <!-- Dynamic UI based on the selected nav item -->
    <div class="p-6">
        <div v-if="activeNavItem === 'Home'">
            <h2>Welcome to Home</h2>
            <p>This is the home page content.</p>
        </div>

        <div v-else-if="activeNavItem === 'Alumni Success Stories'">
            <div class="w-full">

            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                <div v-for="story in $page?.props?.success_stories" :key="story.id"
                    class="flex flex-col mt-10 md:mt-0"
                >
                    <div class="w-full h-[300px] border-2 border-gray-600" v-if="!!story.file">
                        <img class="w-full h-full" :src="story.file" />
                    </div>


                    <div class="w-full h-[300px] border-2 border-gray-600" v-else>
                        <img :src="logoUrl" class="w-full h-full"/>
                    </div>

                    <div class="w-full">
                        <p class="text-xl mt-5">
                            {{ story.student_name }}
                        </p>

                        <p class="text-[12px] text-justify mt-2">
                            {{ story.content }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="activeNavItem === 'History'">
            <div class="w-full">

</div>

<div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
    <div v-for="history in $page?.props?.histories" :key="history.id"
        class="flex flex-col mt-10 md:mt-0"
    >
        <div class="w-full h-[300px] border-2 border-gray-600" v-if="!!history.file">
            <img class="w-full h-full" :src="history.file" />
        </div>


        <div class="w-full h-[300px] border-2 border-gray-600" v-else>
            <img :src="logoUrl" class="w-full h-full"/>
        </div>

        <div class="w-full">
            <p class="text-[12px] text-justify mt-2">
                {{ history.content }}
            </p>
        </div>
    </div>
</div>
        </div>

        <div v-else-if="activeNavItem === 'Hymn'">
            <h2>Hymn</h2>
            <p>Listen to our hymns.</p>
        </div>

        <div v-else-if="activeNavItem === 'Mission and Vision'">
            <h2>Mission and Vision</h2>
            <p>Our mission and vision statements.</p>
        </div>

        <div v-else-if="activeNavItem === 'Programs'">
            <h2>Programs</h2>
            <p>Check out our programs.</p>
        </div>

        <div v-else-if="activeNavItem === 'FAQs'">
            <h2>FAQs</h2>
            <p>Frequently Asked Questions.</p>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}

@media (max-width: 1024px) {
    .lg\\:hidden {
        display: block;
    }

    .lg\\:flex {
        display: none;
    }
}
</style>
