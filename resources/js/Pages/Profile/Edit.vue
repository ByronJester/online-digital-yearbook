<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const editProfile = ref(false)

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;
const getDP = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const defaultProfilePicture = getDP('images/default-profile.jpeg')
const profilePicturePreview = ref(user.profile_picture || defaultProfilePicture);
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="w-full my-3 border-b border-black">
            <img :src="profilePicturePreview" class="rounded-[50%] border border-black w-[200px] h-[200px] my-2">
            <p class="w-[18%] text-center text-xl">
                <b>{{ user.fullname }}</b><span v-if="user.user_type == 'school_alumni'"> - {{ user.section }}</span>
            </p>
        </div>

        <div class="w-full mb-5">
            <button class="float-right bg-blue-500 text-white px-3 py-2 rounded-md mr-8"
                @click="editProfile = true"
                v-if="!editProfile"
            >
                Edit Profile
            </button>

            <button class="float-right bg-red-500 text-white px-3 py-2 rounded-md mr-8"
                @click="editProfile = false"
                v-if="editProfile"
            >
                Close
            </button>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" v-if="editProfile">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-screen"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-screen" />
                </div>

                <!-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
