<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    users: {
        type: Array,
    },
});

const searchQuery = ref(''); // Search input value

// Computed property to filter users based on search query
const filteredUsers = computed(() => {
    return props.users.filter(user =>
        user.fullname.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const formatDate = (date) => {
    var date = new Date(date);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: true // Enables AM/PM format
    });
}
</script>

<template>
    <Head title="Alumni Records" />

    <AuthenticatedLayout>
        <div class="w-full p-5">
            Dashboard

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
</style>
