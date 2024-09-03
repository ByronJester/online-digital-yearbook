<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <div class="w-full">
            <div class="p-5">
                User Management
            </div>
        </div>

    </AuthenticatedLayout>
</template>
