<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

defineProps({
    batches: {
        type: Array,
    },
});

// Form data for new post
const postContent = ref('');
const postImage = ref(null);

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);
const createPost = async() => {
        const formData = new FormData();
        formData.append('content', postContent.value);
        formData.append('image', postImage.value);

        await Inertia.post(route('staff-save-batch'), formData, {
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
    // }
};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')


</script>

<template>
    <Head title="Class Batch" />

    <AuthenticatedLayout>
        <div class="w-full h-[80vh] ">
            <div class="mb-10 border-2 border-black rounded-md p-5">
                <h2 class="text-xl font-bold mb-2">New Batch</h2>
                <textarea v-model="postContent" placeholder="Enter Batch..." class="w-full p-2 border mb-4 rounded-lg" rows="1"></textarea>

                <!-- Upload Buttons with Hidden Inputs -->
                <div class="flex gap-4 mb-4">
                    <button @click="imageInput.click()" class="bg-blue-500 text-white px-4 py-2 rounded">Upload Photo</button>

                    <input ref="imageInput" type="file" @change="(e) => postImage = e.target.files[0]" accept="image/*" class="hidden" />
                </div>

                <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
            </div>

            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-1 border-2 border-black rounded-md">
                <div class="w-full p-5 flex justify-center items-center" v-for="batch in batches" :key="batch.id">
                    <div class="flex flex-col p-5 border border-black rounded-md">
                        <div class="w-full">
                            <p class="text-xl text-center">{{ batch.content }}</p>
                        </div>

                        <div class="w-full mt-3">
                            <img :src="batch.image || logoUrl" class="h-[180px] w-full"/>
                        </div>
                    </div>
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
