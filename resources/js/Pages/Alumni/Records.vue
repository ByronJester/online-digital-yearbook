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
            <!-- Search input field -->
            <div class="mb-5">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Search alumni by name"
                    class="w-[30%] p-2 border rounded"
                />
            </div>

            <div class="w-full flex flex-row mt-3 mb-5 text-xl">
                <div class="w-full">
                    Alumni Name
                </div>

                <div class="w-full">
                    Last Login
                </div>

                <div class="w-full text-right">
                    Active Status
                </div>
            </div>

            <div class="w-full">
                <div class="w-full flex flex-row student mt-2" v-for="user in filteredUsers" :key="user.id">
                    <div class="w-full">
                        <p class="ml-2 p-2">
                            {{ user?.fullname }}
                        </p>
                    </div>

                    <div class="w-full ">
                        <p class="p-2">
                            {{ user?.last_logged_in }}
                        </p>
                    </div>

                    <div class="w-full text-right mr-2 p-2">
                        <!-- {{ !!user?.last_logged_in ? formatDate(user?.last_logged_in)  : 'N/A' }} -->
                        <i class="fa-solid fa-circle text-xl text-green-600" v-if="user.last_logged_in && !user.logout_at">

                        </i>

                        <i class="fa-solid fa-circle text-xl text-red-600" v-if="!user.last_logged_in && !user.logout_at">

                        </i>
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
</style>
