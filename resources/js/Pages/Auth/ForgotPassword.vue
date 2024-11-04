<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    code: ''
});

var message = false;
var otpmessage = false

const submit = () => {
    message = false;

    form.post(route('password.email'), {
        onSuccess: (page) => {
            var modal = document.getElementById("defaultModal");

            modal.style.display = "block";
        },
        onError: (errors) => {
            message = true
        }
    });
}

const submitOTP = () => {
    otpmessage = false

    form.post(route('forgot-password-confirm'), {
        onSuccess: (page) => {

        },
        onError: (errors) => {
            otpmessage = true
        }
    });
};

// const submit = () => {
//     form.post(route('password.email'));
// };
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div
            id="defaultModal"
            tabindex="-1"
            aria-hidden="true"
            style="background-color: rgba(0, 0, 0, 0.7)"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
        >
            <div class="h-screen flex justify-center items-center">
                <div class="relative w-[20%] max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow">
                        <!-- Modal header -->
                        <div
                            class="flex items-start justify-between p-4 border-b rounded-t"
                        >
                            <h3
                                class="text-xl font-semibold text-black"
                            >
                                Code Verification
                            </h3>
                            <button
                                type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                @click="closeOtpModal()"
                            >
                                <svg
                                    class="w-3 h-3"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 14 14"
                                >
                                    <path
                                        stroke="currentColor"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                                    />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="p-6 space-y-6">
                            <form @submit.prevent="submitOTP">
                                <div class="w-full">

                                    <input type="text" v-model="form.code"
                                        class="rounded-md w-full text-center"
                                    />
                                </div>

                                <div class="w-full mt-5">
                                    <button class="w-full text-center bg-blue-500 text-white rounded-md py-2">
                                        Submit
                                    </button>
                                </div>

                                <div v-if="otpmessage" class="my-1 text-xs text-red-600"
                                    style="text-align: left"
                                >
                                    Incorrect Code
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="w-full flex justify-center items-center">
            <form @submit.prevent="submit" class="border border-black rounded-md">
                <div class="w-full p-5">
                    <div>
                        <InputLabel for="email" value="Email" />

                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
                        <div v-if="message" class="my-1 text-xs text-red-600"
                            style="text-align: left"
                        >
                            Email not found.
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Forgot Password
                        </PrimaryButton>
                    </div>
                </div>

            </form>
        </div>

    </GuestLayout>
</template>
