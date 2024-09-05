<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    code: ''
});

var message = false;
var otpmessage = false

const getOtp = () => {
    message = false;

    form.post(route('send-otp'), {
        onSuccess: (page) => {
            if(page.props.flash.success == 1) {
                var modal = document.getElementById("defaultModal");

                modal.style.display = "block";
            } else {
                message = true
            }
        }
    });
}

const submit = () => {
    otpmessage = false
    form.post(route('login'), {
        onSuccess: (page) => {
            if(page.props.flash.success == 0) {
                otpmessage = true
            }


        }
    });
};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')

const closeOtpModal = () => {
    var modal = document.getElementById("defaultModal");

    modal.style.display = "none";
};

</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div> -->

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
                                OTP Verification
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
                            <form @submit.prevent="submit">
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
                                    Incorrect One-time Password Code
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div id="book-container" class="book-container">
                <div id="book-content" class="book-content">
                    <div id="book-cover" class="left-page">
                        <img :src="logoUrl" alt="Logo" class="logo">
                        <p class="yearbook-text">Online Digital Yearbook</p>
                    </div>
                    <div class="right-page">
                        <form @submit.prevent="getOtp">
                            <h2>Login</h2>
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

                            <InputError class="mt-2" :message="form.errors.email" />
                            <InputLabel for="password" value="Password" />

                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />

                            <InputError class="mt-2" :message="form.errors.password" />

                            <button type="submit">Login</button>

                            <div v-if="message" class="my-1 text-xs text-red-600"
                                style="text-align: left"
                            >
                                Email not found.
                            </div>


                            <div class="forgot-password-container">
                                <a :href="route('password.request')" class="forgot-password">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </GuestLayout>
</template>

<style>
.book-container {
    width: 50vw;
    height: 450px;
    position: relative;
    perspective: 1000px; /* Add perspective for 3D effect */
    font-family: 'Rubik', sans-serif;
}

.book-content {
    width: 100%;
    height: 100%;
    display: flex;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s;
}

.left-page, .right-page {
    width: 50%;
    padding: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
    background: #fff; /* White background for pages */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Box shadow for 3D effect */
}

.left-page {
    color: black;
}

.left-page::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 100%;
    width: 20px; /* Width of the book spine */
    background: linear-gradient(to left, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.3));
    box-shadow: -4px 0 8px rgba(0, 0, 0, 0.2);
}

.right-page {
    box-shadow: 0 0 0 2px #ccc inset; /* Adds an inset shadow to simulate page edges */
}

.right-page::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    right: 100%;
    width: 20px; /* Width of the book spine */
    background: linear-gradient(to right, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.3));
    box-shadow: 4px 0 8px rgba(0, 0, 0, 0.2);
}

.left-page .logo {
    max-width: 50%; /* Slightly larger logo size */
    height: auto;
    margin-bottom: 15px; /* Space between logo and text */
}

.left-page .yearbook-text {
    font-size: 24px; /* Larger font size for emphasis */
    color: #555; /* Text color */
    font-weight: 500; /* Semi-bold */
}

.right-page form {
    display: flex;
    flex-direction: column;
    align-items: center; /* Center the form elements */
    width: 100%; /* Increased width for better spacing */
    margin: 0 auto; /* Center the form within the right page */
    background: #fff; /* White background for the form */
    padding: 20px;
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Box shadow for 3D effect */
}

.right-page form h2 {
    font-size: 28px; /* Larger font size for the heading */
    color: #555;
    margin-bottom: 20px; /* Increased margin */
    font-weight: 500; /* Semi-bold */
}

.right-page form label {
    font-size: 16px; /* Consistent font size */
    margin-bottom: 8px; /* Increased margin */
    color: #555;
    align-self: flex-start; /* Align labels to the start */
}

.right-page form input {
    padding: 14px; /* Increased padding */
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
    font-size: 16px; /* Ensure input text is readable */
    transition: border-color 0.3s, box-shadow 0.3s; /* Smooth transition */
}

.right-page form input:focus {
    border-color: #0772B3; /* Highlight border on focus */
    box-shadow: 0 0 5px rgba(0, 114, 182, 0.5); /* Shadow effect */
    outline: none; /* Remove default outline */
}

.right-page form button {
    padding: 14px;
    background-color: #0772B3;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px; /* Adjust button text size */
    transition: background-color 0.3s, box-shadow 0.3s; /* Smooth transition */
}

.right-page form button:hover {
    background-color: #055a92;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); /* Shadow effect on hover */
}

.right-page .forgot-password-container {
    margin-top: 10px;
    text-align: center; /* Center the forgot password link */
}

.right-page .forgot-password {
    font-size: 14px; /* Adjust font size for consistency */
    color: #0772B3;
    text-decoration: none;
}

.right-page .forgot-password:hover {
    text-decoration: underline;
}

/* Add transitions for smooth animation */
.book-content {
    transition: transform 0.6s;
}

.left-page, .right-page {
    transition: transform 0.6s;
    transform-style: preserve-3d;
}


</style>
