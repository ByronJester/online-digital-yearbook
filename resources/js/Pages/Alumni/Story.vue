<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, inject, watchEffect } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    stories: {
        type: Array
    }
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
    <Head title="Success Stories" />
    <AuthenticatedLayout>
        <div class="w-full h-full max-h-screen">
            <div class="flex flex-col p-2">
                <div class="w-full mt-5 border border-black rounded-md bg-white" v-for="story in stories" :key="story.id">

                    <div class="p-5">
                        <p class="font-bold text-md text-center">
                            {{ story.student_name }}
                        </p>
                        <p class="text-md text-justify mt-5">
                            {{ story.content }}
                        </p>

                        <img :src="story.file" class="w-full h-[400px] mt-5"/>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.student {
    border-radius: 10px;
    border: 1px solid black;
}

.scroll-container {
    overflow: hidden; /* Hides the scrollbar */
    overflow-y: auto; /* Enables vertical scrolling */
    scrollbar-width: none; /* For Firefox */
    -ms-overflow-style: none; /* For IE and Edge */
}

/* Hides the scrollbar in WebKit-based browsers (Chrome, Safari) */
.scroll-container::-webkit-scrollbar {
    display: none;
}

.notification-modal {
    position: absolute;
    right: 60px;
    top: 150px;
    width: 350px;
}
</style>
