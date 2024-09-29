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
                <div class="w-full flex flex-row py-3">
                    <div class="w-full mr-1">
                        <label for="cars">Course</label>
                        <br>
                        <select class="rounded-md w-full">
                            <option value="BSIT">BSIT</option>
                        </select>
                    </div>

                    <div class="w-full mr-1">
                        <label for="cars">Section</label>
                        <br>
                        <select class="rounded-md w-full">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="w-full mr-1">
                        <label for="cars">School Year</label>
                        <br>
                        <select class="rounded-md w-full">
                            <option value="2024">2024</option>
                        </select>
                    </div>

                    <div class="w-full mr-1">
                        <label for="cars">Batch Logo</label>
                        <br>
                        <input type="file" class="w-full rounded-md"/>
                    </div>
                </div>

                <!-- <div class="w-full flex flex-row py-3">
                    <div class="w-full mr-1">
                        <label for="cars">Student Name</label>
                        <br>
                        <input type="text" class="w-full rounded-md"/>
                    </div>

                    <div class="w-full mr-1">
                        <label for="cars">Award</label>
                        <br>
                        <input type="text" class="w-full rounded-md"/>
                    </div>


                </div> -->


                <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
            </div>

            <!-- <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-1 border-2 border-black rounded-md">
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
            </div> -->
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
