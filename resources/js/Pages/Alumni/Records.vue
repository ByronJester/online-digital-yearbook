<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    users: {
        type: Array,
    },
    courses: {
        type: Array
    }
});

const searchQuery = ref(''); // Search input value

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

const status = ref('')
const program = ref('')
const batch = ref('')
const searchBy = ref('name')
const statusFilter = ref(null);
const programFilter = ref(null);
const batchFilter = ref(null);

// Computed property to filter users based on search query
const filteredUsers = computed(() => {
    let users = props.users;

    users = users.filter(user =>
        user.fullname.toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    // Filter by search query
    // if (searchBy.value === 'name') {
    //     users = users.filter(user =>
    //         user.fullname.toLowerCase().includes(searchQuery.value.toLowerCase())
    //     );
    // } else if (searchBy.value === 'program') {
    //     users = users.filter(user =>
    //         user.program.toLowerCase().includes(searchQuery.value.toLowerCase())
    //     );
    // } else if (searchBy.value === 'class_batch') {
    //     users = users.filter(user =>
    //         user.class_batch.toLowerCase().includes(searchQuery.value.toLowerCase())
    //     );
    // } else if (searchBy.value === 'last_logged_in') {
    //     users = users.filter(user =>
    //         user.last_logged_in.toLowerCase().includes(searchQuery.value.toLowerCase())
    //     );
    // }

    // Filter by status if a filter is applied
    if (statusFilter.value !== null) {
        if(statusFilter.value == 'all') {
            users = users
        } else {
            users = users.filter(user => user.status === statusFilter.value);
        }

    }

    if (programFilter.value !== null) {
        if(programFilter.value == 'all') {
            users = users
        } else {
            users = users.filter(user => user.program === programFilter.value);
        }
    }

    if (batchFilter.value !== null) {
        if(batchFilter.value == 'all') {
            users = users
        } else {
            users = users.filter(user => user.class_batch === batchFilter.value);
        }
    }

    return users;
});

// Method to update the status filter
const changeStatus = (value) => {
    statusFilter.value = value;
};

const changeProgram = (value) => {
    console.log(value)
    programFilter.value = value;
};

const changeBatch = (value) => {
    batchFilter.value = value;
};

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
                    placeholder="Search by>"
                    class="w-[30%] p-2 border rounded mr-2"
                />

                <span class="float-right text-lg font-bold">
                    {{ filteredUsers.length }} Alumni
                </span>

                <!-- <select class="rounded-md p-2 w-[20%] mr-2" v-model="searchBy">
                    <option :value="'name'">Alumni Name</option>
                    <option :value="'program'">Program</option>
                    <option :value="'class_batch'">Class Batch</option>
                    <option :value="'last_logged_in'">Last Logged In</option>
                </select> -->

                <!-- <select class="rounded-md p-2 w-[12%]" v-model="status" @change="changeStatus($event.target.value)">
                    <option :value="''" disabled>Status</option>
                    <option :value="'all'">All</option>
                    <option :value="'active'">Active</option>
                    <option :value="'in_active'">Inactive</option>
                </select> -->
            </div>

            <div class="w-full flex flex-row mt-16 mb-5 text-xl">
                <div class="w-full text-sm">
                    Alumni Name
                </div>

                <div class="w-full text-left flex flex-row">
                    <div class="w-[30%] text-sm mr-1 mt-1 ">
                        Program
                    </div>

                    <div class="w-[60%]">
                        <select class="rounded-md p-2 w-[100%] text-xs" v-model="program" @change="changeProgram($event.target.value)">
                            <option :value="'all'">All</option>
                            <option :value="c.name" v-for="c in courses" :key="c.id">{{ c.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="w-full text-right flex flex-row mr-2">
                    <div class="w-[40%] text-sm mr-1 mt-1">
                        Class Batch
                    </div>

                    <div class="w-[50%]">
                        <select class="rounded-md p-2 w-[100%] text-xs" v-model="batch" @change="changeBatch($event.target.value)">
                            <option :value="'all'">All</option>
                            <option :value="'2020'">2020</option>
                            <option :value="'2021'">2021</option>
                            <option :value="'2022'">2022</option>
                            <option :value="'2023'">2023</option>
                            <option :value="'2024'">2024</option>
                            <option :value="'2025'">2025</option>
                        </select>
                    </div>
                </div>

                <div class="w-full text-sm">
                    Last Login
                </div>

                <div class="w-full text-right flex flex-row">
                    <div class="w-full text-sm mr-1 mt-1">
                        Active Status
                    </div>

                    <div class="w-full">
                        <select class="rounded-md p-2 w-[100%] text-xs" v-model="status" @change="changeStatus($event.target.value)">
                            <option :value="''" disabled>Status</option>
                            <option :value="'all'">All</option>
                            <option :value="'active'">Online</option>
                            <option :value="'in_active'">Offline</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <div class="w-full flex flex-row student mt-2" v-for="user in filteredUsers" :key="user.id">
                    <div class="w-full">
                        <p class="ml-2 p-2 text-xs">
                            {{ user?.fullname }}
                        </p>
                    </div>

                    <div class="w-full">
                        <p class=" p-2 text-xs">
                            {{ user?.program }}
                        </p>
                    </div>

                    <div class="w-full">
                        <p class="ml-4 p-2 text-xs">
                            {{ user?.class_batch }}
                        </p>
                    </div>

                    <div class="w-full ">
                        <p class="ml-3 p-2 text-xs">
                            {{ user?.last_logged_in_at }}
                        </p>
                    </div>

                    <div class="w-full text-right mr-2 p-2">
                        <!-- {{ !!user?.last_logged_in ? formatDate(user?.last_logged_in)  : 'N/A' }} -->
                        <i class="fa-solid fa-circle text-xl text-green-600" v-if="user.status == 'active'">

                        </i>
                        <i class="fa-solid fa-circle text-xl text-red-600" v-if="user.status == 'in_active'">

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
