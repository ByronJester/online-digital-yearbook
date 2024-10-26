<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    messages: {
        type: Array,
    },
});

const postDate = ref('');
const postMessage = ref('');

const createMessage = () => {
    swal({
        title: "Are you sure to save this message to future self?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            const formData = new FormData();
            formData.append('date', postDate.value);
            formData.append('message', postMessage.value);

            Inertia.post(route('alumni-mtfs-save-message'), formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
                onSuccess: (page) => {
                    // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                },
                onError: (errors) => {
                    // alert('There was an error uploading the file.');
                },
            });

        }
    });

    // }
};

const getImage = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const imageURL = getImage('images/message.jpg');

</script>

<template>
    <Head title="Message to Future Self" />

    <AuthenticatedLayout>
        <!-- Create new post form -->
        <div class="mb-10 border-2 border-black rounded-md p-5" >
            <h2 class="text-xl font-bold mb-8">Message To Future Self</h2>
            <input type="date" class="rounded-md mb-3" v-model="postDate"/>
            <textarea v-model="postMessage" placeholder="Type a message...." class="w-full p-2 border mb-4 rounded-lg" rows="5"></textarea>

            <button @click="createMessage" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4 mt-10">
            <div class="rounded-md border border-black h-[250px]" v-for="message in messages" :key="message.id">
                <img :src="imageURL" class="h-[220px] w-full rounded-md"/>
                <p class="text-center font-bold text-md"> {{message.formatted_date}}</p>
            </div>
          </div>

    </AuthenticatedLayout>
</template>

<style scoped>
textarea {
    resize: none;
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
