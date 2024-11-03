<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    stories: {
        type: Array,
    },
    histories: {
        type: Array,
    },
    mission: {
        type: Object
    },
    vision: {
        type: Object
    },
    programs: {
        type: Array
    }
});

console.log(props)

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

const carouseImage = [
    `${window.location.origin}/carousel/c1.jpg`,
    `${window.location.origin}/carousel/c2.jpg`,
    `${window.location.origin}/carousel/c3.jpg`,
    `${window.location.origin}/carousel/c4.jpg`
]

const carouselRef = ref(null);
let intervalId = null;

const startAutoplay = () => {
  if (carouselRef.value) {
    intervalId = setInterval(() => {
      if (carouselRef.value.next) {
        carouselRef.value.next();
      }
    }, 3000); // 3000ms = 3 seconds
  }
};

const stopAutoplay = () => {
  if (intervalId) {
    clearInterval(intervalId);
  }
};

onMounted(() => {
  startAutoplay();
});

onUnmounted(() => {
  stopAutoplay();
});


</script>

<script>
     // If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
    import 'vue3-carousel/dist/carousel.css'
    import { Carousel, Slide, Pagination, Navigation } from 'vue3-carousel'

    export default {
        name: 'App',
        components: {
            Carousel,
            Slide,
            Pagination,
            Navigation,
        },
    }
</script>

<template>
    <Head title="Welcome" />

    <nav class="bg-[#0772B3] text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div class="text-xl font-bold">
                <img :src="!$page?.props?.logo ? logoUrl : $page.props.logo" alt="Logo" class="logo w-[100px] h-[80px]"
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
        <div v-if="activeNavItem === 'Home'" class="mt-5">

            <div class="carousel-container">
                <carousel :items-to-show="1" ref="carouselRef">
                    <slide v-for="i in carouseImage" :key="i">
                        <img :src="i" class="w-full h-full"/>
                    </slide>

                    <template #addons>
                        <navigation />
                        <pagination />
                    </template>
                </carousel>
             </div>
        </div>

        <div v-else-if="activeNavItem === 'Alumni Success Stories'">
            <div class="w-full mb-10">
                <p class="text-2xl">
                    Alumni Success Story
                </p>
            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">


                <div v-for="story in stories" :key="story.id"
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
            <div class="w-full mb-10">
                <p class="text-2xl">
                   History
                </p>
            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
                <div v-for="history in histories" :key="history.id"
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
            <h2 class="text-2xl font-bold">Hymn</h2>
            <p class="text-xl">Listen to our hymns.</p>
        </div>

        <div v-else-if="activeNavItem === 'Mission and Vision'">
            <h2 class="text-2xl font-bold">Mission and Vision</h2>
            <p class="text-xl">Our mission and vision statements.</p>


            <div class="w-full flex flex-col md:flex-row h-[500px] max-h-screen mt-10" v-if="mission && vision">
                <div class="w-full h-full mx-1">
                    <p class="text-2xl text-center">Mission</p>
                    <p class="text-justify mt-5">
                        {{ mission.content }}
                    </p>
                </div>

                <div class="w-full h-full flex flex-col mx-10">
                    <div class="w-full mt-20">
                        <img :src="mission.file" class="h-full w-full rounded-md"/>
                    </div>

                    <div class="w-full mt-10">
                        <img :src="vision.file" class="h-full w-full rounded-md"/>
                    </div>
                </div>

                <div class="w-full h-full mx-1">
                    <p class="text-2xl text-center">Vision</p>
                    <p class="text-justify mt-5">
                        {{ vision.content }}
                    </p>
                </div>
            </div>
        </div>

        <div v-else-if="activeNavItem === 'Programs'">
            <h2 class="text-2xl font-bold">Programs</h2>
            <p class="text-xl">Check out our programs.</p>

            <div class="grid grid-cols-4 gap-4 w-full mt-10">
                <div v-for="program in programs" :key="program.id" class="flex flex-col">
                    <div class="w-full">
                        <img :src="program.file" class="w-full h-[250px] rounded-md"/>
                    </div>

                    <div class="w-full mt-5">
                        <p class="text-xl font-bold text-center">
                            {{  program.program_name }}
                        </p>
                    </div>

                    <div class="w-full mt-5">
                        <p class="text-lg text-justify">
                            {{  program.content }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-else-if="activeNavItem === 'FAQs'">
            <h2 class="text-2xl font-bold">FAQs</h2>
            <p class="text-xl">Frequently Asked Questions.</p>


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

.carousel-container {
  width: 100%;
  max-width: 1600px;
  margin: 0 auto;
  height: 100%;
  max-height: 100px;
}
</style>
