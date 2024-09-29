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
const course = ref('');
const section = ref('');
const school_year = ref('');
const logo = ref('');

// File input refs to trigger from buttons
const imageInput = ref(null);
const videoInput = ref(null);
const createPost = async() => {
        const formData = new FormData();
        formData.append('course', course.value);
        formData.append('section', section.value);
        formData.append('school_year', school_year.value);
        formData.append('logo', logo.value);

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
};

const getLogo = (imagePath) => {
    return `${window.location.origin}/${imagePath}`;
}

const logoUrl = getLogo('images/logo1.png')

const viewBatch = (id) => {
    Inertia.get(route('staff-view-batch', id))
}
</script>

<template>
    <Head title="Class Batch" />

    <AuthenticatedLayout>
        <div class="w-full h-[80vh] ">
            <div class="mb-10 border-2 border-black rounded-md p-5">
                <div class="w-full flex flex-col md:flex-row py-3">
                    <div class="w-full md:mr-1">
                        <label>Course</label>
                        <br>
                        <select class="rounded-md w-full" v-model="course">
                            <option value="BSIT">BSIT</option>
                        </select>
                    </div>

                    <div class="w-full md:mr-1">
                        <label>Section</label>
                        <br>
                        <select class="rounded-md w-full" v-model="section">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="w-full md:mr-1">
                        <label>School Year</label>
                        <br>
                        <select class="rounded-md w-full" v-model="school_year">
                            <option value="2024">2024</option>
                        </select>
                    </div>

                    <div class="w-full md:mr-1">
                        <label>Batch Logo</label>
                        <br>
                        <input type="file" class="w-full rounded-md" @change="(e) => logo = e.target.files[0]"/>
                    </div>
                </div>

                <button @click="createPost" class="bg-blue-500 text-white px-4 py-2 float-right rounded-md">Save</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4">
                <div class="w-full h-[250px] border border-black rounded-md cursor-pointer" v-for="batch in batches" @click="viewBatch(batch.id)">
                    <img :src="batch.logo || logoUrl" class="w-full h-[225px]"/>
                    <p class="text-center font-bold">{{ batch.course }} - {{ batch.school_year }} (Section {{ batch.section }})</p>
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
