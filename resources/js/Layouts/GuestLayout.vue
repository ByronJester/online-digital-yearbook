<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <!-- <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div> -->

        <div
            class="w-screen h-screen shadow-md overflow-hidden sm:rounded-lg flex justify-center items-center"
            style="background: linear-gradient(to right, #00c6ff, #0072ff);"
        >
            <slot />
        </div>
    </div>
</template>
