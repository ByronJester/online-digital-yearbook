<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'w-full bg-[#FAF006] text-gray-700 hover:bg-[#FAF006] h-16  inline-flex items-center text-sm font-medium leading-5 focus:outline-none focus:border-white transition duration-150 ease-in-out'
        : 'w-full hover:bg-[#FAF006] hover:text-gray-700 h-16 inline-flex items-center border-transparent text-sm font-medium leading-5 text-white focus:outline-none focus:text-white focus:border-gray-300 transition duration-150 ease-in-out'
);
</script>

<template>
    <div class="m-0 p-0 w-full">
        <Link :href="href" :class="classes">
            <slot />
        </Link>
    </div>

</template>
