<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { VueSpinner } from 'vue3-spinners';
import InputError from '@/Components/InputError.vue';

const loading = ref(false)

const props = defineProps({
    batches: {
        type: Array,
    },
    courses: {
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

const batchData = ref(props.batches)

const search = (event) => {
    var search = event.target.value
    console.log(search)

    if(search != ''){
        classBatchesArr.value = classBatchesArr.value.filter(x => {
            console.log(x)
            return x.course.toLowerCase().includes(search.toLowerCase()) || x.section.toLowerCase().includes(search.toLowerCase()) || x.school_year.toLowerCase().includes(search.toLowerCase());
        })
    } else {
        classBatchesArr.value = props.batches.filter(x => {return x.school_year == bYear.value && x.section == bSection.value })
    }
}

const deleteBatch = (id) => {
    swal({
        title: "Are you sure to delete this batch?",
        text: "",
        icon: "success",
        buttons: true,
        dangerMode: true,
    })
    .then((proceed) => {
        if (proceed) {
            loading.value = true

            const formData = new FormData();
            formData.append('id', id);

            Inertia.post(route('staff-delete-batch'), formData, {
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


        }
    });
}

const classBatchesArr = ref([]);
const viewClassBatch = ref(false)
const bYear = ref(null)
const selectBatch = (batch) => {
    viewClassBatch.value = true
    classBatchesArr.value = props.batches.filter(x => { return x.school_year == batch})
    bYear.value = batch
}

const viewClassSection = ref(false)
const bSection = ref(null)
const selectSection = (section) => {
    viewClassBatch.value = false
    viewClassSection.value = true
    classBatchesArr.value = classBatchesArr.value.filter(x => { return x.section == section})
    bSection.value = section
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
                                <option v-for="c in courses" :key="c.id" :value="c.name"> {{ c.name }}</option>
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

            <div class="w-full mt-3 mb-10" v-if="!viewClassBatch && viewClassSection">
                <input type="text" placeholder="Search..." @keyup="search($event)"
                    class="w-[50%] rounded-xl"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4" v-if="!viewClassBatch && !viewClassSection">
                <!-- <div class="w-full h-[340px] border border-black rounded-md cursor-pointer" v-for="batch in batchData" >
                    <p v-if="$page.props.auth.user.user_type == 'school_staff'">
                        <span class="float-right text-red-400 m-2 text-xs"
                            @click="deleteBatch(batch.id)"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </span>
                    </p>
                    <img :src="batch.logo || logoUrl" class="w-full h-[225px]" @click="viewBatch(batch.id)"/>
                    <p class="text-center font-bold" @click="viewBatch(batch.id)">{{ batch.course }} - {{ batch.school_year }} (Section {{ batch.section }})</p>
                </div> -->

                <div class="w-full h-[250px] border border-black rounded-md cursor-pointer" v-for="batch in ['2020', '2021', '2022', '2023', '2024', '2025']" >
                    <img :src="logoUrl" class="w-full h-[225px]" @click="selectBatch(batch)"/>
                    <p class="text-center font-bold" @click="selectBatch(batch)"> Class Batch {{ batch }}</p>
                </div>
            </div>

            <div class="w-full mb-5" v-if="viewClassBatch && !viewClassSection">
                <button class="rounded-md bg-blue-500 px-5 py-1 text-white text-xl" @click="viewClassBatch = false"> Back</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4" v-if="viewClassBatch && !viewClassSection">
                <div class="w-full h-[250px] border border-black rounded-md cursor-pointer" v-for="sec in ['1', '2', '3', '4']" >
                    <img :src="logoUrl" class="w-full h-[225px]" @click="selectSection(sec)"/>
                    <p class="text-center font-bold" @click="selectSection(sec)"> Section {{ sec }}</p>
                </div>
            </div>

            <div class="w-full mb-5" v-if="!viewClassBatch && viewClassSection">
                <button class="rounded-md bg-blue-500 px-5 py-1 text-white text-xl" @click="viewClassBatch = true; viewClassSection = false"> Back</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-1 md:gap-4" v-if="!viewClassBatch && viewClassSection">
                <div class="w-full h-[340px] border border-black rounded-md cursor-pointer" v-for="batch in classBatchesArr" >
                    <p v-if="$page.props.auth.user.user_type == 'school_staff'">
                        <span class="float-right text-red-400 m-2 text-xs"
                            @click="deleteBatch(batch.id)"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </span>
                    </p>
                    <img :src="batch.logo || logoUrl" class="w-full h-[225px]" @click="viewBatch(batch.id)"/>
                    <p class="text-center font-bold" @click="viewBatch(batch.id)">{{ batch.course }} - {{ batch.school_year }} (Section {{ batch.section }})</p>
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
