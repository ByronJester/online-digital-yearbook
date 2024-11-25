<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';

defineProps({
    messages: {
        type: Array,
    },
});

const postDate = ref('');
const postMessage = ref('');
const loading = ref(false)

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

            loading.value = true

            setTimeout(() => {
                Inertia.post(route('alumni-mtfs-save-message'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: (page) => {
                        // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                        loading.value = false
                    },
                    onError: (errors) => {
                        // alert('There was an error uploading the file.');
                        loading.value = false
                    },
                });

            }, 2000);


        }
    });

    // }
};

const getImage = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const imageURL = getImage('images/message.jpg');


const viewMessage = ref(false);
const viewMessageId = ref(null);

const message = ref(null)

const view = (post) => {
    console.log(post)
    if(isToday(post.date)) {
        message.value = post.message
        viewMessageId.value = post.id

        viewMessage.value = true
    }

}

const closeMessage = () => {
    viewMessage.value = false
    message.value = null
    viewMessageId.value = null
}

const isToday = (date) => {
    const today = new Date(); // Current date

    // Format both dates to YYYY-MM-DD for comparison
    const formattedToday = today.toISOString().split('T')[0];
    const formattedDate = new Date(date).toISOString().split('T')[0];

    return formattedToday === formattedDate;
}

</script>

<template>
    <Head title="Message to Future Self" />

    <AuthenticatedLayout>
        <!-- Create new post form -->
         <div class="w-full">
            <div class="w-full flex justify-center items-center" v-if="loading">
                <VueSpinner size="50" color="red" />
             </div>



             <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4 mt-10">
                <div
                    class="rounded-md border border-black"
                    :class="{'h-[280px]': viewMessageId !== message.id, 'h-[500px]': viewMessageId === message.id}"
                    v-for="message in messages"
                    :key="message.id">

                    <!-- View / Hide Toggle -->
                    <p>
                        <span
                            v-if="viewMessageId !== message.id"
                            class="text-xs float-right text-green-400 m-2 cursor-pointer"
                            @click="view(message)">
                            View
                        </span>
                        <span
                            v-if="viewMessageId === message.id"
                            class="text-xs float-right text-red-400 m-2 cursor-pointer"
                            @click="closeMessage">
                            Hide
                        </span>
                    </p>

                    <!-- Message Content -->
                    <p class="text-xs mt-10 p-2" v-if="viewMessageId === message.id">
                        {{ message.message }}
                    </p>

                    <!-- Default Image -->
                    <img
                        :src="imageURL"
                        class="h-[220px] w-full rounded-md"
                        v-if="viewMessageId !== message.id" />

                    <!-- Date -->
                    <p class="text-center font-bold text-md">
                        {{ message.formatted_date }}
                    </p>
                </div>
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
