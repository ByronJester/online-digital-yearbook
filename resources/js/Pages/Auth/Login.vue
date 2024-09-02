<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div> -->

        <!-- <form @submit.prevent="submit">
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

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
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
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>
        </form> -->

        <div class="">
            <div id="book-container" class="book-container">
                <div id="book-content" class="book-content">
                    <div id="book-cover" class="left-page">
                        <img :src="logoUrl" alt="Logo" class="logo">
                        <p class="yearbook-text">Online Digital Yearbook</p>
                    </div>
                    <div class="right-page">
                        <form @submit.prevent="submit">
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

                            <div class="forgot-password-container">
                                <a :href="route('password.request')" class="forgot-password">Forgot Password?</a>
                            </div>
                        </form>

                        <!-- <form @submit.prevent="submit">
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

                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mt-4">
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
                        </div>

                        <div class="block mt-4">
                            <label class="flex items-center">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Forgot your password?
                            </Link>

                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Log in
                            </PrimaryButton>
                        </div>
                    </form> -->
                    </div>
                </div>
            </div>
        </div>

    </GuestLayout>
</template>

<style>
/* Apply Rubik font globally */
/* body {
    font-family: 'Rubik', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(to right, #00c6ff, #0072ff);
    margin: 0;
} */

.book-container {
    width: 50vw;
    height: 400px;
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
