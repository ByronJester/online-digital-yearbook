<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const profilePicture = ref(null);

const getDP = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const defaultProfilePicture = getDP('images/default-profile.jpeg')
const profilePicturePreview = ref(user.profile_picture || defaultProfilePicture);

const form = useForm({
    first_name: user.first_name,
    middle_name: user.middle_name,
    last_name: user.last_name,
    contact: user.contact,
    email: user.email,
    profile_picture: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.profile_picture = file;
        profilePicturePreview.value = URL.createObjectURL(file);

        console.log(form)
    }
};

const triggerFileUpload = () => {
    document.getElementById('profile_picture').click();
};

const updateProfile = () => {
    swal({
            title: "Are you sure to update your profile?",
            text: "",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((proceed) => {
            if (proceed) {
                // Inertia.patch(route('profile.update'), form, {
                //     onSuccess: (page) => {
                //         location.reload()
                //     },
                //     onError: (errors) => {

                //     },
                // });

                form.post(route('profile.update'), {
                    onSuccess: (page) => {
                        location.reload()
                    },
                    onError: (errors) => {
                        swal("Error saving.");
                    }
                });

            }
        });
}
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
        </header>


        <div class="w-full flex flex-col md:flex-row">
            <div class="w-full md:mx-1">
                <InputLabel for="first_name" value="First Name" />
                <TextInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required />
                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>

            <div class="w-full md:mx-1">
                <InputLabel for="middle_name" value="Middle Name" />
                <TextInput id="middle_name" type="text" class="mt-1 block w-full" v-model="form.middle_name" />
                <InputError class="mt-2" :message="form.errors.middle_name" />
            </div>

            <div class="w-full md:mx-1">
                <InputLabel for="last_name" value="Last Name" />
                <TextInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required />
                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>
        </div>

        <div class="w-full flex flex-col md:flex-row">
            <div class="w-full md:mx-1">
                <InputLabel for="contact" value="Contact" />
                <TextInput id="contact" type="text" class="mt-1 block w-full" v-model="form.contact" required />
                <InputError class="mt-2" :message="form.errors.contact" />
            </div>

            <div class="w-full md:mx-1">
                <InputLabel for="email" value="Email" />
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>
        </div>

        <div class="mt-4">
            <InputLabel for="profile_picture" value="Profile Picture" />

            <input
                id="profile_picture"
                type="file"
                class="hidden"
                accept="image/*"
                @change="handleFileChange"
            />

            <div class="mt-4 cursor-pointer" @click="triggerFileUpload">
                <img :src="profilePicturePreview" class="rounded-lg border border-black w-[200px] h-[200px]">
            </div>

            <InputError class="mt-2" :message="form.errors.profile_picture" />
        </div>



        <div class="flex items-center gap-4 mt-5">
            <PrimaryButton @click="updateProfile">Save</PrimaryButton>
        </div>
    </section>
</template>
