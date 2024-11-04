<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';
import InputError from '@/Components/InputError.vue';

const loading = ref(false)

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

const createPost = () => {
    swal({
        title: "Are you sure to save this batch?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();
            formData.append('course', course.value);
            formData.append('section', section.value);
            formData.append('school_year', school_year.value);
            formData.append('logo', logo.value);



            setTimeout(() => {
                Inertia.post(route('staff-save-batch'), formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                    onSuccess: (page) => {
                        // location.reload()
                        // alert(page.props.flash.message || 'File uploaded and data inserted successfully!');
                        loading.value = false
                    },
                    onError: (errors) => {
                        // alert('There was an error uploading the file.');
                        loading.value = false
                    },
                });

            }, 1000);

        }
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
            <div class="mb-10 border-2 border-black rounded-md p-5" v-if="$page.props.auth.user.user_type == 'school_staff'">
                <div class="w-full flex justify-center items-center" v-if="loading">
                    <VueSpinner size="50" color="red" />
                </div>

                <div class="w-full" v-else>
                    <div class="w-full flex flex-col md:flex-row py-3">
                        <div class="w-full md:mr-1">
                            <label>Course</label>
                            <br>
                            <select class="rounded-md w-full" v-model="course">
                                <option value="Bachelor of Science in Information Technology (BSIT)">Bachelor of Science in Information Technology (BSIT)</option>
                                <option value="Bachelor of Science in Computer Engineering (BSCpE)">Bachelor of Science in Computer Engineering (BSCpE)</option>
                                <option value="Bachelor of Science in Tourism Management (BSTM)">Bachelor of Science in Tourism Management (BSTM)</option>
                                <option value="Bachelor of Science in Business Administration (BSBA)">Bachelor of Science in Business Administration (BSBA)</option>
                                <option value="Bachelor of Science in Technology Livehood Education (BTLE)">Bachelor of Science in Technology Livehood Education (BTLE)</option>
                                <option value="Bachelor of Science in Hospitality Management (BSHM)">Bachelor of Science in Hospitality Management (BSHM)</option>
                            </select>
                        </div>

                        <div class="w-full md:mr-1">
                            <label>Section</label>
                            <br>
                            <select class="rounded-md w-full" v-model="section">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="3">4</option>
                            </select>
                        </div>

                        <div class="w-full md:mr-1">
                            <label>School Year</label>
                            <br>
                            <select class="rounded-md w-full" v-model="school_year">
                                <option value="2001">2001</option>
                                <option value="2002">2002</option>
                                <option value="2003">2003</option>
                                <option value="2004">2004</option>
                                <option value="2005">2005</option>
                                <option value="2006">2006</option>
                                <option value="2007">2007</option>
                                <option value="2008">2008</option>
                                <option value="2009">2009</option>
                                <option value="2010">2010</option>
                                <option value="2011">2011</option>
                                <option value="2012">2012</option>
                                <option value="2013">2013</option>
                                <option value="2014">2014</option>
                                <option value="2015">2015</option>
                                <option value="2016">2016</option>
                                <option value="2017">2017</option>
                                <option value="2018">2018</option>
                                <option value="2019">2019</option>
                                <option value="2020">2020</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                                <option value="2023">2023</option>
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
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

            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4">
                <div class="w-full h-[310px] border border-black rounded-md cursor-pointer" v-for="batch in batches" @click="viewBatch(batch.id)">
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
